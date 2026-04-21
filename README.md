# Pedidos - Sistema de Gestion de Delivery

Plataforma integral para la gestion de pedidos y delivery para restaurantes, desarrollada con Laravel 13, Livewire y Flux.

## Caracteristicas

- **Gestion de Restaurantes**: Administra multiples restaurantes con sus respective usuarios
- **Catalogo de Productos**: Gestion completa de productos y categorias
- **Sistema de Pedidos**: Creacion, seguimiento y gestion de pedidos en tiempo real
- **Panel de Control**: Dashboard con metricas y estadisticas
- **Autenticacion Segura**: Sistema de autenticacion con Laravel Fortify y autenticacion de dos factores (2FA)
- **API REST**: Endpoints para integracion con aplicaciones moviles
- **Interfaz Moderna**: Diseno responsivo con TailwindCSS 4 y animaciones

## Tecnologias

- **Backend**: Laravel 13 (PHP 8.2+)
- **Frontend**: Livewire 4, Flux 2, TailwindCSS 4
- **Base de Datos**: MySQL/PostgreSQL (configurable)
- **Autenticacion**: Laravel Fortify + Sanctum
- **Build**: Vite

## Requisitos

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL/PostgreSQL o SQLite (desarrollo)

## Instalacion

```bash
# Instalar dependencias PHP
composer install

# Copiar archivo de configuracion
cp .env.example .env

# Generar clave de aplicacion
php artisan key:generate

# Ejecutar migraciones
php artisan migrate

# Instalar dependencias Node.js
npm install

# Compilar assets
npm run build
```

## Configuracion

### Variables de entorno (.env)

```
APP_NAME=Pedidos
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pedidos
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
SANCTUM_STATEFUL_DOMAINS=localhost:5173
```

## Ejecutar en desarrollo

```bash
# Iniciar servidor PHP
php artisan serve

# Compilar assets con Hot Reload
npm run dev
```

O usar el comando combinado:

```bash
composer run dev
```

## Comandos utiles

```bash
# Limpiar cache
php artisan config:clear
php artisan cache:clear

# Ver rutas
php artisan route:list

# Ejecutar tests
composer test

# Linter
composer lint
composer test:lint
```

## Estructura del proyecto

```
app/
├── Http/Controllers/    # Controladores HTTP
├── Livewire/           # Componentes Livewire
├── Models/             # Modelos Eloquent
└── Providers/         # Proveedores de servicios

database/migrations/    # Migraciones de base de datos

resources/views/       # Vistas Blade

routes/
├── web.php           # Rutas web
├── api.php           # API REST
└── console.php       # Comandos artisan
```

## License

MIT License