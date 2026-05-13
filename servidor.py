#!/usr/bin/env python3
"""
Servidor local para Visor Esquineros SEDAPAL
Uso: python servidor.py
Luego abrir: http://localhost:8000
"""
import http.server, json, os, sys, urllib.parse
from pathlib import Path

BASE = Path(__file__).parent
PORT = 8000

class Handler(http.server.SimpleHTTPRequestHandler):
    def log_message(self, fmt, *args):
        pass  # silencioso

    def do_GET(self):
        parsed = urllib.parse.urlparse(self.path)
        
        # API: listar PDFs de una carpeta cod_cad
        if parsed.path == '/api/pdfs':
            params = urllib.parse.parse_qs(parsed.query)
            cod = params.get('cod', [''])[0]
            if not cod:
                self._json({'error': 'cod requerido'}, 400); return
            # Seguridad: no permitir ../ 
            cod_safe = cod.replace('/', '').replace('\\', '').replace('..', '')
            folder = BASE / 'pdfs' / cod_safe
            if not folder.exists():
                self._json({'cod': cod, 'pdfs': [], 'carpeta': f'pdfs/{cod_safe}'}); return
            pdfs = [f.name for f in sorted(folder.iterdir()) if f.suffix.lower() == '.pdf']
            self._json({'cod': cod, 'pdfs': pdfs, 'carpeta': f'pdfs/{cod_safe}'}); return

        # API: listar TODOS los cod_cad con sus PDFs
        if parsed.path == '/api/pdfs/all':
            result = {}
            pdfs_dir = BASE / 'pdfs'
            if pdfs_dir.exists():
                for folder in sorted(pdfs_dir.iterdir()):
                    if folder.is_dir():
                        pdfs = [f.name for f in sorted(folder.iterdir()) if f.suffix.lower() == '.pdf']
                        result[folder.name] = pdfs
            self._json(result); return

        # Servir archivos estáticos normalmente
        super().do_GET()

    def _json(self, data, code=200):
        body = json.dumps(data, ensure_ascii=False).encode()
        self.send_response(code)
        self.send_header('Content-Type', 'application/json; charset=utf-8')
        self.send_header('Content-Length', len(body))
        self.send_header('Access-Control-Allow-Origin', '*')
        self.end_headers()
        self.wfile.write(body)

if __name__ == '__main__':
    os.chdir(BASE)
    print(f"=" * 50)
    print(f"  Visor Esquineros SEDAPAL")
    print(f"  http://localhost:{PORT}")
    print(f"  Ctrl+C para detener")
    print(f"=" * 50)
    with http.server.HTTPServer(('', PORT), Handler) as httpd:
        httpd.serve_forever()
