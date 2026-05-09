# 🗺️ Visor de Esquineros SEDAPAL

Sistema web interactivo para visualizar predios catastrales en mapa, gestionar archivos PDF por predio y administrar información geoespacial.

## 📋 Características

✅ **Carga automática de Excel** desde `datos/datos.xlsx`  
✅ **Mapa interactivo** con Leaflet + OpenStreetMap/Satélite  
✅ **Conversión UTM 18S → WGS-84** automática  
✅ **Gestión de PDFs** por carpeta `cod_cad`  
✅ **Drag & Drop** para subir archivos  
✅ **Búsqueda y filtrado** de predios  
✅ **Servidor PHP** para persistencia de archivos  
✅ **Modo local** (sin servidor) para pruebas  
✅ **Interfaz responsiva** y moderna  

## 🚀 Instalación Rápida

### Opción 1: Con PHP (Recomendado)

```bash
# 1. Navega a la carpeta del proyecto
cd visor_sedapal

# 2. Inicia el servidor PHP
php -S localhost:8000

# 3. Abre en el navegador
http://localhost:8000
```

### Opción 2: Sin servidor (Modo local)

- Abre directamente `index.html` en tu navegador
- Los PDFs se guardan en memoria (se pierden al recargar)
- No se crean carpetas en `pdfs/`

## 📁 Estructura de Carpetas

```
visor_sedapal/
├── index.html              ← Página principal
├── README.md               ← Este archivo
├── api/
│   ├── ping.php            ← Detecta servidor
│   ├── list.php            ← Lista PDFs de una carpeta
│   ├── list_all.php        ← Lista todos los PDFs
│   ├── upload.php          ← Sube PDFs (crea carpeta cod_cad)
│   └── delete.php          ← Elimina PDFs
├── datos/
│   └── datos.xlsx          ← Tu archivo Excel (colócalo aquí)
└── pdfs/                   ← Se crean carpetas automáticamente
    ├── COD001/
    │   ├── plano_1.pdf
    │   └── plano_2.pdf
    ├── COD002/
    │   └── documento.pdf
    └── ...
```

## 📊 Formato del Excel

Tu archivo `datos/datos.xlsx` debe tener estas columnas:

| cod_cad | x | y | lamina | Cuadrante | Secuencial | Imagen | cd_imagen |
|---------|---|---|--------|-----------|-----------|--------|-----------|
| COD001 | 500000 | 8755000 | 001 | A | 001 | https://... | IMG001 |
| COD002 | 500100 | 8755100 | 001 | B | 002 | https://... | IMG002 |

**Columnas requeridas:**
- `cod_cad` - Código único del predio
- `x` - Coordenada Este (UTM Zona 18S)
- `y` - Coordenada Norte (UTM Zona 18S)
- `lamina` - Número de lámina
- `Cuadrante` - Cuadrante (A, B, C, D, etc.)
- `Secuencial` - Número secuencial
- `Imagen` - URL del croquis en sistema SEDAPAL
- `cd_imagen` - Código de imagen

**Sistema de Coordenadas:**
- Proyección: UTM WGS-84 Zona 18S (EPSG:32718)
- Datum: WGS-84
- Se convierten automáticamente a WGS-84 (lat/lon) para el mapa

## 💾 Cómo Usar

### 1️⃣ Preparar el Excel

1. Crea un archivo `datos.xlsx` con las columnas especificadas
2. Colócalo en la carpeta `datos/`
3. El sistema lo cargará automáticamente al abrir

### 2️⃣ Visualizar Predios

- El mapa se centra automáticamente en los predios cargados
- **Colores de marcadores:**
  - 🔵 Azul: Sin archivos
  - 🟢 Verde: Con PDFs
  - 🟣 Violeta: Con imagen en SEDAPAL
  - Gradiente: Con ambos

### 3️⃣ Subir PDFs

1. Haz clic en un marcador del mapa
2. Selecciona la pestaña "📄 PDFs"
3. Arrastra PDFs o haz clic para seleccionar
4. Los archivos se guardan en `pdfs/{cod_cad}/`

### 4️⃣ Gestionar Archivos

- **Ver PDF:** Haz clic en el icono de ojo
- **Eliminar PDF:** Haz clic en el icono de papelera
- **Descargar:** Haz clic derecho en el PDF → Descargar

## 🔧 Configuración

Edita `index.html` (línea ~270) para cambiar:

```javascript
const CFG = {
  excelPath: 'datos/datos.xlsx',  // Ruta del Excel
  apiBase: 'api'                  // Carpeta de APIs
};
```

## 🌐 Permisos de Servidor

Si usas PHP, asegúrate de que la carpeta `pdfs/` tenga permisos de escritura:

```bash
chmod 755 pdfs/
```

## 📱 Compatibilidad

- ✅ Chrome/Edge (recomendado)
- ✅ Firefox
- ✅ Safari
- ✅ Dispositivos móviles (responsive)

## 🐛 Solución de Problemas

### "Excel no se carga automáticamente"
- Verifica que `datos/datos.xlsx` exista
- Revisa la consola del navegador (F12)

### "No puedo subir PDFs"
- Verifica que PHP esté corriendo (`php -S localhost:8000`)
- Revisa permisos de la carpeta `pdfs/` (debe ser 755)
- Mira la consola del navegador para errores

### "Los PDFs desaparecen al recargar"
- Estás en modo local (sin servidor)
- Inicia PHP: `php -S localhost:8000`

### "Las coordenadas no se ven bien"
- Verifica que uses UTM Zona 18S (EPSG:32718)
- Comprueba que X esté entre 400000-600000 y Y entre 8600000-8900000

## 📞 Soporte

Para reportar problemas o sugerencias, contacta al equipo de desarrollo.

## 📄 Licencia

Sistema desarrollado para SEDAPAL - 2024

---

**Última actualización:** Enero 2024  
**Versión:** 1.0
