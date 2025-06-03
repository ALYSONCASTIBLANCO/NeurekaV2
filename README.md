# 📆 Hey! Bienvenidos al Manual de Developer de Neureka GS!

### Querido dev, si estas aquí, es porque estás colaborando en el diseño y despliegue de Neureka GS sitio realizado en WordPress usando el tema hijo de Astra, Elementor y GitHub como control de versiones.

## 📆 Estructura del proyecto (repo en /wp-content/)

 ``` wp-content/ ├── themes/ │ └── mi-tema-hijo/ ← Tema hijo personalizado (basado en Astra) ├── plugins/ │ └── nuestros-plugins/ ← Plugins propios (si usamos alguno) ├── uploads/ ← Ignorado (archivos multimedia) ├── elementor-templates/ ← Plantillas .json exportadas desde Elementor ├── .gitignore └── README.md ``` 

## ✅ ¿Qué incluimos en el repo?

Sí se incluye:

El tema hijo (código, estilos, funciones personalizadas)

Plugins personalizados (si se crean)

Plantillas .json exportadas desde Elementor

Archivos de configuración del proyecto

## No se incluye:

Elementor ni otros plugins descargables (se instalan desde el panel de WP)

La carpeta uploads/ (archivos multimedia)

Archivos temporales o de caché

## 💡 ¿Qué es elementor-templates/?

Es una carpeta para guardar plantillas de diseño creadas con Elementor y exportadas en formato .json:

elementor-templates/
  ├── home.json        ← Página de inicio
  ├── login.json       ← Página de login
  └── contacto.json    ← Página de contacto

## Esto permite:

Compartir diseños entre el equipo

Versionar los cambios en Git

Reutilizar plantillas en cualquier instalación local

## 🤖 ¿Cómo usar las plantillas en WordPress?

Hacer git pull para traer la última versión del repo.

Entrar al panel de WordPress:

Ir a Elementor > Plantillas > Importar plantilla

Elegir el archivo .json correspondiente (ej. home.json)

Crear una nueva página y cargar la plantilla importada.

## ♻️ ¿Cómo colaboramos en equipo?

Cada miembro diseña su parte (home, login, contacto, etc.) en local.

Exporta la plantilla desde Elementor como .json.

Guarda el archivo en la carpeta elementor-templates/ del repo.

Hace git add, commit y push con ese archivo.

Los demás pueden hacer git pull e importar esa plantilla en su WP local.

# 🚀 Ready to build!
