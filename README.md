# 📦 InventoryPro SaaS - Gestión de Stock Inteligente

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php)
![SQLite](https://img.shields.io/badge/SQLite-3.x-003B57?style=for-the-badge&logo=sqlite)
![Tailwind](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css)

**InventoryPro** es un sistema de gestión de inventario diseñado bajo un modelo SaaS. Su arquitectura destaca por un sistema de aislamiento de datos único, garantizando privacidad y rendimiento mediante instancias de bases de datos dedicadas.

[💼 Mi LinkedIn]((https://www.linkedin.com/in/fernando-ceballos-carreras-920825397/))

---

## 🌟 Características Principales

### 🔒 Smart Instance Isolation (Aislamiento de Datos)
A diferencia de los SaaS tradicionales que mezclan datos de miles de clientes en una sola tabla, **InventorySysPro** despliega una base de datos SQLite dedicada para cada sesión de usuario. 
- **Privacidad Total:** Los datos están físicamente separados.
- **Rendimiento:** Consultas ultrarrápidas al no compartir índices con otros clientes.
- **Seguridad:** Inmune a errores de filtración de datos entre cuentas (Cross-tenant data leaks).

---

## 🛠️ Arquitectura Técnica

### Database Engine & Triggers
El sistema utiliza **Triggers a nivel de base de datos** en SQLite para gestionar el `current_stock`. 
Esto asegura que la integridad de los datos se mantenga incluso si se realizan cambios fuera de la lógica de Laravel.



### Clonación Dinámica (Middleware)
Se ha implementado un Middleware personalizado que intercepta cada petición:
1. Identifica la sesión del usuario.
2. Si no existe, clona un archivo `master.sqlite` (molde pre-configurado).
3. Reconfigura la conexión de base de datos de Laravel en tiempo de ejecución (`Config::set`).

---

## 🚀 Instalación Local

Si deseas auditar el código o probarlo en tu entorno:

1. **Clonar repositorio:**
   ```bash
   git clone [https://github.com/tu-usuario/inventory-pro.git](https://github.com/tu-usuario/inventory-pro.git)
   cd inventory-pro

2. **Instalar dependencias:**
   composer install
   npm install && npm run build


3. **Configurar entorno:**
  cp .env.example .env
  php artisan key:generate


4. **Preparar Master Database:**
  # Crear la base de datos principal y el molde para las demos
  touch database/database.sqlite
  php artisan migrate --seed --seeder=MasterDemoSeeder
  mkdir -p database/demos
  cp database/database.sqlite database/demos/master.sqlite

5 **Iniciar server:**
  npm run dev
  php artisan serve

## 📄 Licencia
Este proyecto es una pieza de portfolio. Todos los derechos están reservados. El código está disponible para su revisión técnica, pero está prohibida su redistribución o uso comercial sin permiso expreso del autor.

Desarrollado con ❤️ por Fernando Ceballos Carreras.
   
   
