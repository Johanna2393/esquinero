# 🗺️ Mapa de Croquis - Gestión de PDFs

Sistema web completo para visualizar croquis en un mapa interactivo y gestionar archivos PDF organizados por código de carpeta.

## 📋 Características

✅ **Carga de datos desde Excel/CSV**
- Soporta formatos .xlsx, .xls, .csv
- Lee automáticamente coordenadas (latitud, longitud)
- Flexible con nombres de columnas

✅ **Mapa Interactivo**
- Basado en Leaflet + OpenStreetMap
- Marcadores interactivos con información
- Zoom automático a registros
- Popups con detalles del croquis

✅ **Gestión de PDFs**
- Carga de PDFs directamente desde la interfaz
- Drag & Drop para subir archivos
- Organización automática en carpetas por código
- Visualización de archivos existentes
- Eliminación de archivos
- Descarga directa de PDFs

✅ **Interfaz Intuitiva**
- Sidebar con lista de registros
- Panel flotante de detalles
- Búsqueda y filtrado de registros
- Diseño responsivo (desktop/móvil)
- Tema moderno con gradientes

## 🚀 Instalación

### Requisitos
- Servidor web con PHP 7.0+
- Navegador moderno (Chrome, Firefox, Safari, Edge)
- Permisos de escritura en carpeta `pdfs/`

### Pasos

1. **Descargar archivos**
   - Extrae el ZIP en tu servidor web
   - Ejemplo: `/var/www/html/mapa_croquis/`

2. **Configurar permisos**
   ```bash
   chmod 755 pdfs/
   chmod 644 *.php
   ```

3. **Abrir en navegador**
   ```
   http://localhost/mapa_croquis/
   o
   http://tu-servidor.com/mapa_croquis/
   ```

## 📊 Formato de Excel Esperado

Tu archivo debe contener estas columnas (mínimo):

| latitud  | longitud | cod_cad | descripcion | ubicacion    |
|----------|----------|---------|-------------|--------------|
| -12.0464 | -77.0428 | COD001  | Croquis 1   | Lima Centro  |
| -12.0500 | -77.0450 | COD002  | Croquis 2   | San Isidro   |

**Notas:**
- Nombres de columnas flexibles: `lat`/`latitude`, `lng`/`longitude`, `codigo`
- Las coordenadas deben estar en formato decimal
- El `cod_cad` se usa para crear carpetas de PDFs

## 💻 Cómo Usar

### 1. Cargar Datos
1. Haz clic en **"📥 Cargar Excel/CSV"**
2. Selecciona tu archivo
3. Los marcadores aparecerán en el mapa

### 2. Gestionar PDFs
1. Haz clic en un marcador del mapa
2. Selecciona **"📤 Gestionar PDFs"**
3. Arrastra PDFs o haz clic para seleccionar
4. Haz clic en **"✓ Subir PDFs"**
5. Los archivos se organizarán en `pdfs/COD_CAD/`

### 3. Ver PDFs Existentes
- En el gestor de PDFs verás todos los archivos cargados
- Puedes descargar o eliminar archivos
- Muestra "⚠️ Sin archivos" si no hay PDFs

### 4. Buscar Registros
- Usa el cuadro de búsqueda en el sidebar
- Filtra por código, descripción, etc.

### 5. Exportar Datos
- Haz clic en **"📤 Exportar Datos"**
- Se descargará un CSV con todos los registros

## 📁 Estructura de Carpetas

```
mapa_croquis/
├── index.html              # Página principal
├── upload_pdfs.php         # Backend para subir PDFs
├── get_pdfs.php            # Backend para obtener lista de PDFs
├── delete_pdf.php          # Backend para eliminar PDFs
├── README.md               # Este archivo
├── css/
│   └── styles.css          # Estilos CSS
├── js/
│   └── app.js              # Lógica JavaScript
└── pdfs/                   # Carpeta para organizar PDFs
    ├── COD001/
    │   ├── croquis_01.pdf
    │   └── croquis_02.pdf
    ├── COD002/
    │   └── croquis_03.pdf
    └── ...
```

## 🔧 Configuración Avanzada

### Cambiar límite de tamaño de archivo
En `upload_pdfs.php`, línea 5:
```php
$maxTamano = 50 * 1024 * 1024; // Cambiar 50 a otro valor en MB
```

### Cambiar ubicación inicial del mapa
En `js/app.js`, función `inicializarMapa()`:
```javascript
mapa = L.map('map').setView([-12.0464, -77.0428], 13);
// [-12.0464, -77.0428] = Lima, Perú
// 13 = nivel de zoom
```

### Cambiar colores de marcadores
En `js/app.js`, función `procesarRegistros()`:
```javascript
fillColor: '#667eea',  // Color de relleno
color: '#764ba2',      // Color del borde
```

## 🐛 Solución de Problemas

### Los PDFs no se suben
- Verifica que la carpeta `pdfs/` tenga permisos 755
- Comprueba que PHP está habilitado en tu servidor
- Revisa el tamaño del archivo (máx 50MB)

### El mapa no carga
- Verifica conexión a internet (usa OpenStreetMap)
- Comprueba que Leaflet está cargado correctamente
- Abre la consola del navegador (F12) para ver errores

### Los registros no aparecen
- Verifica que el Excel tiene columnas: `latitud`, `longitud`
- Comprueba que las coordenadas están en formato decimal
- Revisa la consola del navegador para mensajes de error

### Error "No se pudo crear la carpeta"
- Verifica permisos de la carpeta `pdfs/`
- Ejecuta: `chmod 755 pdfs/`
- Comprueba que PHP tiene permisos de escritura

## 📞 Soporte

Para reportar problemas o sugerencias:
1. Revisa la consola del navegador (F12)
2. Verifica los logs del servidor PHP
3. Comprueba que todos los archivos estén en su lugar

## 📄 Licencia

Uso libre para proyectos personales y comerciales.

## 🎯 Próximas Mejoras

- [ ] Visualizador de PDFs integrado
- [ ] Búsqueda avanzada con filtros
- [ ] Exportación de reportes en PDF
- [ ] Base de datos para almacenamiento
- [ ] Autenticación de usuarios
- [ ] Sincronización en tiempo real

---

**Versión:** 1.0
**Última actualización:** 2024
