# 📚 ARQUITECTURA DEL SISTEMA - LAVANDERÍA BELÉN

**Proyecto:** Sistema de Gestión Empresarial para Lavandería  
**Tecnología Web - UMSS 2025**  
**Fecha:** Noviembre 26, 2025

---

## 📋 Índice

1. [Resumen Ejecutivo](#resumen-ejecutivo)
2. [Stack Tecnológico](#stack-tecnológico)
3. [Arquitectura General](#arquitectura-general)
4. [Sistema de Menú Dinámico](#sistema-de-menú-dinámico)
5. [Sistema de Temas](#sistema-de-temas)
6. [Autenticación](#autenticación)
7. [Base de Datos](#base-de-datos)
8. [Estructura de Archivos](#estructura-de-archivos)
9. [Diagramas UML](#diagramas-uml)
10. [Casos de Uso Implementados](#casos-de-uso-implementados)

---

## 🎯 Resumen Ejecutivo

Sistema web para la gestión integral de "Lavandería BELÉN", desarrollado con Laravel 12 y Vue 3. Implementa:

- ✅ Autenticación personalizada con tabla `usuario`
- ✅ Menú dinámico basado en roles (Propietario/Empleado)
- ✅ Sistema de temas con 3 variantes y modos claro/oscuro
- ✅ 17 tablas en base de datos PostgreSQL
- ✅ 16 modelos Eloquent con relaciones
- ✅ 15 controladores CRUD

---

## 🛠️ Stack Tecnológico

### Backend
- **Framework:** Laravel 12.39.0
- **PHP:** 8.2.12
- **Base de Datos:** PostgreSQL
- **Autenticación:** Laravel Breeze (modificado)
- **ORM:** Eloquent

### Frontend
- **Framework:** Vue 3 (Composition API)
- **Bridge:** Inertia.js
- **Build Tool:** Vite 6.4.1
- **Estilos:** Tailwind CSS + CSS Variables
- **Tipografía:** Inter (Google Fonts)

### Herramientas
- **Servidor Local:** php artisan serve (puerto 8000)
- **Gestión de Assets:** npm run dev / npm run build
- **Migraciones:** php artisan migrate
- **Seeders:** php artisan db:seed

---

## 🏗️ Arquitectura General

```
┌─────────────────────────────────────────────────────────────────┐
│                        NAVEGADOR (Cliente)                       │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────────────┐  │
│  │   Vue 3      │  │  Inertia.js  │  │  Sistema de Temas    │  │
│  │  Components  │◄─┤   (SPA)      │  │  (CSS Variables)     │  │
│  └──────────────┘  └──────────────┘  └──────────────────────┘  │
└───────────────────────────┬─────────────────────────────────────┘
                            │ HTTP Requests
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│                    LARAVEL (Servidor)                            │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │              MIDDLEWARE PIPELINE                          │   │
│  │  Auth → HandleInertiaRequests → Web                      │   │
│  └───────────────────────┬──────────────────────────────────┘   │
│                          ▼                                       │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │                    RUTAS (routes/)                        │   │
│  │  • web.php: Rutas principales                            │   │
│  │  • auth.php: Autenticación                               │   │
│  └───────────────────────┬──────────────────────────────────┘   │
│                          ▼                                       │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │              CONTROLADORES (Controllers/)                 │   │
│  │  • AuthController: Login/Logout                          │   │
│  │  • UsuarioController: CRUD Usuarios                      │   │
│  │  • 13+ Controllers: CRUD de entidades                    │   │
│  └───────────────────────┬──────────────────────────────────┘   │
│                          ▼                                       │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │                MODELOS (Models/)                          │   │
│  │  • Usuario: Usuario base                                 │   │
│  │  • Empleado: Herencia TPT                                │   │
│  │  • Propietario: Herencia TPT                             │   │
│  │  • 13+ Models: Relaciones Eloquent                       │   │
│  └───────────────────────┬──────────────────────────────────┘   │
│                          ▼                                       │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │           PROVIDERS (AppServiceProvider)                  │   │
│  │  • Menú Dinámico: Compartido vía Inertia                 │   │
│  └──────────────────────────────────────────────────────────┘   │
└───────────────────────────┬─────────────────────────────────────┘
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│                    BASE DE DATOS (PostgreSQL)                    │
│  • 17 Tablas principales                                         │
│  • Herencia TPT (usuario → empleado/propietario)                │
│  • Relaciones FK con integridad referencial                      │
└─────────────────────────────────────────────────────────────────┘
```

---

## 🔐 Sistema de Menú Dinámico

### Descripción

El menú se genera dinámicamente según el rol del usuario (`tipo_usuario`), utilizando dos tablas:

1. **`menu`**: Define los ítems del menú (nombre, ruta, padre, orden)
2. **`permisomenu`**: Asigna permisos a roles (propietario/empleado)

### Flujo de Funcionamiento

```
┌─────────────────────────────────────────────────────────────┐
│ 1. USUARIO INICIA SESIÓN                                    │
│    • AuthController::store() valida credenciales            │
│    • Laravel crea sesión con Auth::login($usuario)          │
└────────────────────────┬────────────────────────────────────┘
                         ▼
┌─────────────────────────────────────────────────────────────┐
│ 2. MIDDLEWARE HandleInertiaRequests                         │
│    • Detecta usuario autenticado                            │
│    • Lee $page.props.auth.user.tipo_usuario                 │
└────────────────────────┬────────────────────────────────────┘
                         ▼
┌─────────────────────────────────────────────────────────────┐
│ 3. AppServiceProvider::boot()                               │
│    • Ejecuta query con JOIN:                                │
│      SELECT menu.* FROM menu                                │
│      INNER JOIN permisomenu ON menu.id = permisomenu.id_menu│
│      WHERE permisomenu.tipo_usuario = 'propietario'         │
│      ORDER BY menu.orden ASC                                │
│    • Construye árbol jerárquico (padre → hijos)             │
│    • Comparte vía Inertia::share('menu', $menuTree)         │
└────────────────────────┬────────────────────────────────────┘
                         ▼
┌─────────────────────────────────────────────────────────────┐
│ 4. AuthenticatedLayout.vue                                  │
│    • Recibe $page.props.menu desde Inertia                  │
│    • Renderiza sidebar con v-for:                           │
│      <div v-for="item in $page.props.menu">                 │
│        • Si tiene hijos → <details> expandible              │
│        • Si no → <Link :href="item.ruta">                   │
│    • Aplica estilos con clases CSS                          │
└─────────────────────────────────────────────────────────────┘
```

### Archivos Involucrados

| Archivo | Función |
|---------|---------|
| `database/migrations/2025_11_26_051015_create_menu_table.php` | Define tabla `menu` (id, nombre, ruta, padre, icono, orden) |
| `database/migrations/2025_11_26_051031_create_permisomenu_table.php` | Define tabla `permisomenu` (id_menu, tipo_usuario) |
| `database/seeders/MenuSeeder.php` | Inserta 11 ítems de menú con permisos |
| `app/Providers/AppServiceProvider.php` | Query del menú y construcción del árbol jerárquico |
| `resources/js/Layouts/AuthenticatedLayout.vue` | Renderiza el sidebar dinámico |

### Ejemplo de Datos

**Tabla `menu`:**
```sql
id | nombre        | ruta               | padre | orden
---|---------------|--------------------| ------|------
1  | Dashboard     | /dashboard         | NULL  | 1
2  | Usuarios      | NULL               | NULL  | 2
3  | Empleados     | /usuarios/empleados| 2     | 1
4  | Clientes      | /usuarios/clientes | 2     | 2
5  | Órdenes       | /ordenes           | NULL  | 3
```

**Tabla `permisomenu`:**
```sql
id_menu | tipo_usuario
--------|-------------
1       | propietario
1       | empleado
2       | propietario
3       | propietario
4       | empleado
```

**Resultado:** 
- **Propietario** ve: Dashboard, Usuarios (con Empleados/Clientes), Órdenes
- **Empleado** ve: Dashboard, Clientes, Órdenes

### Diagrama de Secuencia (UML)

```
Usuario          AuthController    AppServiceProvider    Base de Datos    AuthenticatedLayout
  │                    │                   │                    │                  │
  │──Login Request────→│                   │                    │                  │
  │                    │──Validar──────────┼───→SELECT usuario─→│                  │
  │                    │                   │                    │                  │
  │                    │←──Usuario OK──────┼────────────────────┤                  │
  │                    │                   │                    │                  │
  │                    │──Auth::login()───→│                    │                  │
  │                    │                   │                    │                  │
  │                    │                   │──Query Menu────────┼───→SELECT menu──→│
  │                    │                   │  (tipo_usuario)    │                  │
  │                    │                   │                    │                  │
  │                    │                   │←──Menu Tree────────┼──────────────────┤
  │                    │                   │                    │                  │
  │                    │                   │──Inertia::share()─→│                  │
  │                    │                   │   ('menu', tree)   │                  │
  │                    │                   │                    │                  │
  │←──Redirect Dashboard─────────────────────────────────────────────────────────→│
  │                    │                   │                    │                  │
  │                    │                   │                    │      ┌───────────┤
  │                    │                   │                    │      │ Renderiza │
  │                    │                   │                    │      │  sidebar  │
  │                    │                   │                    │      │ con v-for │
  │                    │                   │                    │      └───────────┤
  │                    │                   │                    │                  │
  │←──HTML con Sidebar──────────────────────────────────────────────────────────────┤
```

---

## 🎨 Sistema de Temas

### Descripción

Sistema de personalización visual con 3 temas, 2 modos (claro/oscuro), 3 tamaños de fuente y alto contraste. Utiliza **CSS Variables** para cambio dinámico sin recargar página.

### Arquitectura del Sistema

```
┌─────────────────────────────────────────────────────────────┐
│              USUARIO CAMBIA TEMA (UI)                       │
│  ThemeSelector.vue → Botones de selección                   │
└────────────────────────┬────────────────────────────────────┘
                         ▼
┌─────────────────────────────────────────────────────────────┐
│              COMPOSABLE useTheme.js                         │
│  • reactive({ theme, mode, fontSize, highContrast })       │
│  • applyTheme() → Modifica body.classList                   │
│  • watch() → Guarda en localStorage                         │
└────────────────────────┬────────────────────────────────────┘
                         ▼
┌─────────────────────────────────────────────────────────────┐
│              APLICACIÓN EN DOM                              │
│  <body class="theme-young dark font-large high-contrast">   │
└────────────────────────┬────────────────────────────────────┘
                         ▼
┌─────────────────────────────────────────────────────────────┐
│              CSS VARIABLES (app.css)                        │
│  body.theme-young.dark {                                    │
│    --color-primary: #00D9FF;                                │
│    --color-bg: #0F172A;                                     │
│    --color-text: #E2E8F0;                                   │
│  }                                                          │
└────────────────────────┬────────────────────────────────────┘
                         ▼
┌─────────────────────────────────────────────────────────────┐
│          COMPONENTES UTILIZAN VARIABLES                     │
│  background-color: var(--color-primary);                    │
│  color: var(--color-text);                                  │
└─────────────────────────────────────────────────────────────┘
```

### Temas Disponibles

#### 1. 🧒 **Kids** - Divertido y Colorido
- **Colores:** Rosa (#FF6B9D), Naranja (#FFA07A), Amarillo (#FFD93D)
- **Tipografía:** Quicksand, Fredoka, Comic Sans MS
- **Público:** Ambiente familiar, infantil

#### 2. 🎮 **Young** - Moderno y Tecnológico
- **Colores:** Cyan (#00D9FF), Púrpura (#8B5CF6), Magenta (#EC4899)
- **Tipografía:** Inter, Poppins
- **Público:** Jóvenes, ambiente tech

#### 3. 👔 **Classic** - Profesional y Elegante
- **Colores:** Azul (#1E40AF), Gris (#64748B), Verde (#059669)
- **Tipografía:** Georgia, Times New Roman
- **Público:** Corporativo, formal

### Variables CSS Definidas

```css
/* Ejemplo: Tema Young - Modo Claro */
body.theme-young {
  --color-primary: #00D9FF;         /* Color principal */
  --color-primary-dark: #00A8CC;    /* Hover/Active */
  --color-secondary: #8B5CF6;       /* Secundario */
  --color-accent: #EC4899;          /* Acentos */
  --color-bg: #F8FAFC;              /* Fondo principal */
  --color-bg-secondary: #F1F5F9;    /* Fondo secundario */
  --color-text: #0F172A;            /* Texto principal */
  --color-text-secondary: #475569;  /* Texto secundario */
  --color-sidebar: #0F172A;         /* Sidebar background */
  --color-sidebar-hover: #1E293B;   /* Hover sidebar */
  --color-border: #CBD5E1;          /* Bordes */
  --color-card: #FFFFFF;            /* Tarjetas */
  --color-shadow: rgba(0,217,255,0.2); /* Sombras */
}
```

### Persistencia en localStorage

```javascript
// useTheme.js (Composable)
const theme = ref(localStorage.getItem('theme') || 'young');
const mode = ref(localStorage.getItem('mode') || 'light');
const fontSize = ref(localStorage.getItem('fontSize') || 'base');
const highContrast = ref(localStorage.getItem('highContrast') === 'true');

watch([theme, mode, fontSize, highContrast], () => {
  localStorage.setItem('theme', theme.value);
  localStorage.setItem('mode', mode.value);
  localStorage.setItem('fontSize', fontSize.value);
  localStorage.setItem('highContrast', highContrast.value.toString());
  applyTheme();
});
```

### Archivos Involucrados

| Archivo | Función |
|---------|---------|
| `resources/css/app.css` | Define CSS Variables para 3 temas × 2 modos |
| `resources/js/Composables/useTheme.js` | Lógica reactiva de temas (Vue Composable) |
| `resources/js/Components/ThemeSelector.vue` | UI para seleccionar tema/modo/fuente |
| `resources/js/Pages/Configuracion.vue` | Página de configuración (`/configuracion`) |
| `resources/js/Layouts/AuthenticatedLayout.vue` | Aplica `useTheme()` en layout principal |
| `resources/js/Layouts/GuestLayout.vue` | Aplica temas en login (fondo degradado) |
| `app/Http/Controllers/ConfiguracionController.php` | Controlador para ruta `/configuracion` |
| `routes/web.php` | Ruta `GET /configuracion` |

### Diagrama de Flujo (Cambio de Tema)

```
┌─────────────────────────────────────────┐
│ Usuario hace clic en selector de tema   │
└────────────────┬────────────────────────┘
                 ▼
         ┌───────────────┐
         │ ThemeSelector │
         │  @click event │
         └───────┬───────┘
                 ▼
    ┌────────────────────────┐
    │ useTheme.js            │
    │ theme.value = 'young'  │
    └────────┬───────────────┘
             ▼
    ┌────────────────────────┐
    │ watch() detecta cambio │
    └────────┬───────────────┘
             ▼
    ┌────────────────────────┐      ┌─────────────────────┐
    │ localStorage.setItem() │─────→│ Persistencia datos  │
    └────────┬───────────────┘      └─────────────────────┘
             ▼
    ┌────────────────────────┐
    │ applyTheme() función   │
    └────────┬───────────────┘
             ▼
    ┌────────────────────────┐
    │ body.classList.remove()│ (clases antiguas)
    │ body.classList.add()   │ (clases nuevas)
    └────────┬───────────────┘
             ▼
    ┌────────────────────────┐
    │ CSS Variables activas  │
    │ var(--color-primary)   │
    └────────┬───────────────┘
             ▼
    ┌────────────────────────┐
    │ UI actualizada sin     │
    │ recargar página        │
    └────────────────────────┘
```

### Excepción: Sidebar

El sidebar **NO cambia de color** con los temas para mantener consistencia visual. Se aplican estilos con `!important`:

```css
.sidebar {
  background-color: var(--color-sidebar) !important;
  color: white !important;
}

.sidebar-item {
  color: white !important;
}
```

---

## 🔒 Autenticación

### Tabla `usuario` Personalizada

Laravel Breeze usa por defecto la tabla `users`, pero este proyecto utiliza `usuario` con estructura TPT (Table Per Type) para herencia.

**Modificaciones realizadas:**

1. **Migración sin timestamps:**
```php
// database/migrations/2025_11_26_050952_create_usuario_table.php
Schema::create('usuario', function (Blueprint $table) {
    $table->id();
    $table->string('email')->unique();
    $table->string('password');
    $table->enum('tipo_usuario', ['propietario', 'empleado']);
    $table->boolean('estado')->default(true);
    // NO $table->timestamps();
    // NO $table->rememberToken();
});
```

2. **Modelo User.php modificado:**
```php
class User extends Authenticatable
{
    protected $table = 'usuario';
    public $timestamps = false;
    protected $rememberTokenName = null;
    
    protected $fillable = ['email', 'password', 'tipo_usuario', 'estado'];
    
    protected $hidden = ['password'];
    
    protected $casts = ['password' => 'hashed'];
}
```

3. **Configuración en `config/auth.php`:**
```php
'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],
],
```

### Herencia TPT (Table Per Type)

```
┌─────────────────────────┐
│  usuario (tabla base)   │
│  • id (PK)              │
│  • email                │
│  • password             │
│  • tipo_usuario         │
│  • estado               │
└─────────┬───────────────┘
          │
    ┌─────┴──────┐
    ▼            ▼
┌─────────┐  ┌──────────────┐
│empleado │  │ propietario  │
│• id (FK)│  │ • id (FK)    │
│• nombre │  │ • nombre     │
│• ci     │  │ • ci         │
│• cargo  │  │ • telefono   │
│• sueldo │  └──────────────┘
└─────────┘
```

**Modelos:**

```php
// app/Models/Empleado.php
class Empleado extends Model
{
    protected $table = 'empleado';
    public $timestamps = false;
    
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }
}

// app/Models/Propietario.php
class Propietario extends Model
{
    protected $table = 'propietario';
    public $timestamps = false;
    
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }
}
```

### Seeder de Usuario por Defecto

```php
// database/seeders/DatabaseSeeder.php
$usuario = Usuario::create([
    'email' => 'admin@lavanderiabelen.com',
    'password' => Hash::make('password'),
    'tipo_usuario' => 'propietario',
    'estado' => true,
]);

Propietario::create([
    'id' => $usuario->id,
    'nombre' => 'Administrador',
    'ci' => '12345678',
    'telefono' => '70000000',
]);
```

**Credenciales de acceso:**
- Email: `admin@lavanderiabelen.com`
- Password: `password`

---

## 💾 Base de Datos

### Estructura Completa (17 Tablas)

```
┌─────────────────────────────────────────────────────────────┐
│                     USUARIOS Y ROLES                         │
├─────────────────────────────────────────────────────────────┤
│ usuario        → Tabla base autenticación                   │
│ empleado       → Herencia TPT (FK a usuario.id)             │
│ propietario    → Herencia TPT (FK a usuario.id)             │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                    CLIENTES Y SERVICIOS                      │
├─────────────────────────────────────────────────────────────┤
│ cliente        → Clientes de la lavandería                  │
│ servicio       → Tipos de servicios (lavado, planchado...)  │
│ promocion      → Promociones/descuentos                     │
│ promocion_servicio → Relación N:M (promocion ↔ servicio)   │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                       ÓRDENES                                │
├─────────────────────────────────────────────────────────────┤
│ orden          → Órdenes principales (PK: nro VARCHAR)      │
│ orden_detalle  → Detalles de orden (servicios solicitados) │
│ orden_proceso  → Procesos de orden (lavado, secado...)     │
│ pago           → Pagos de órdenes                           │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                  EQUIPOS E INVENTARIO                        │
├─────────────────────────────────────────────────────────────┤
│ equipo         → Máquinas (lavadoras, secadoras...)         │
│ mantenimiento  → Registro de mantenimientos                 │
│ insumo         → Insumos (detergente, suavizante...)        │
│ inventario     → Movimientos de inventario                  │
│ proceso_insumo → Insumos usados en procesos                 │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                    SISTEMA Y MENÚ                            │
├─────────────────────────────────────────────────────────────┤
│ menu           → Ítems del menú                             │
│ permisomenu    → Permisos por tipo_usuario                  │
│ pagina         → Contador de visitas                        │
└─────────────────────────────────────────────────────────────┘
```

### Relaciones Principales

```sql
-- ORDEN (PK: nro VARCHAR)
orden.nro (PK) ← orden_detalle.nro_orden (FK)
orden.nro (PK) ← orden_proceso.nro_orden (FK)
orden.nro (PK) ← pago.nro_orden (FK)
orden.id_cliente (FK) → cliente.id (PK)
orden.id_empleado (FK) → empleado.id (PK)

-- ORDEN DETALLE
orden_detalle.id_servicio (FK) → servicio.id (PK)

-- ORDEN PROCESO
orden_proceso.id_equipo (FK) → equipo.id (PK)
orden_proceso.id (PK) ← proceso_insumo.id_proceso (FK)

-- PROCESO INSUMO
proceso_insumo.id_insumo (FK) → insumo.id (PK)

-- INVENTARIO
inventario.id_insumo (FK) → insumo.id (PK)

-- MANTENIMIENTO
mantenimiento.id_equipo (FK) → equipo.id (PK)

-- PROMOCION SERVICIO (N:M)
promocion_servicio.id_promocion (FK) → promocion.id (PK)
promocion_servicio.id_servicio (FK) → servicio.id (PK)

-- MENU
menu.padre (FK) → menu.id (PK) [Self-Reference]
permisomenu.id_menu (FK) → menu.id (PK)
```

### Formato de Claves Primarias VARCHAR

Algunas tablas usan `VARCHAR` como PK para códigos personalizados:

- **orden.nro:** `BEL-NNNNNN` (ej: BEL-000001, BEL-000002)
- **equipo.id:** `EQ-NNNN` (ej: EQ-0001)
- **insumo.id:** `INS-NNNN` (ej: INS-0001)

**Generación automática en controladores:**

```php
// OrdenController::store()
$ultimaOrden = Orden::orderBy('nro', 'desc')->first();
$numero = $ultimaOrden ? intval(substr($ultimaOrden->nro, 4)) + 1 : 1;
$nro = 'BEL-' . str_pad($numero, 6, '0', STR_PAD_LEFT);
```

---

## 📁 Estructura de Archivos

### Directorio `app/`

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php          [Login/Logout]
│   │   ├── ConfiguracionController.php [Temas]
│   │   ├── UsuarioController.php       [CRUD Usuarios]
│   │   ├── EmpleadoController.php      [CRUD Empleados]
│   │   ├── PropietarioController.php   [CRUD Propietarios]
│   │   ├── ClienteController.php       [CRUD Clientes]
│   │   ├── ServicioController.php      [CRUD Servicios]
│   │   ├── PromocionController.php     [CRUD Promociones]
│   │   ├── OrdenController.php         [CRUD Órdenes + Auto-nro]
│   │   ├── EquipoController.php        [CRUD Equipos]
│   │   ├── MantenimientoController.php [CRUD Mantenimientos]
│   │   ├── InsumoController.php        [CRUD Insumos]
│   │   ├── InventarioController.php    [CRUD Inventario]
│   │   └── PagoController.php          [CRUD Pagos]
│   └── Middleware/
│       └── HandleInertiaRequests.php   [Comparte auth.user]
├── Models/
│   ├── User.php                        [Modelo usuario]
│   ├── Empleado.php                    [Herencia TPT]
│   ├── Propietario.php                 [Herencia TPT]
│   ├── Cliente.php
│   ├── Servicio.php
│   ├── Promocion.php
│   ├── Orden.php                       [PK: nro VARCHAR]
│   ├── OrdenDetalle.php
│   ├── OrdenProceso.php
│   ├── Equipo.php                      [PK: id VARCHAR]
│   ├── Mantenimiento.php
│   ├── Insumo.php                      [PK: id VARCHAR]
│   ├── Inventario.php
│   ├── ProcesoInsumo.php
│   ├── Pago.php
│   └── Pagina.php                      [Visitas]
└── Providers/
    └── AppServiceProvider.php          [Menú dinámico]
```

### Directorio `resources/`

```
resources/
├── css/
│   └── app.css                         [CSS Variables + Temas]
├── js/
│   ├── app.js                          [Entry point Vite]
│   ├── Composables/
│   │   └── useTheme.js                 [Lógica de temas]
│   ├── Components/
│   │   ├── ApplicationLogo.vue
│   │   ├── ThemeSelector.vue           [Selector de temas]
│   │   ├── InputLabel.vue
│   │   ├── TextInput.vue
│   │   ├── PrimaryButton.vue
│   │   └── ... (10+ componentes Breeze)
│   ├── Layouts/
│   │   ├── AuthenticatedLayout.vue     [Layout principal]
│   │   └── GuestLayout.vue             [Layout login]
│   └── Pages/
│       ├── Dashboard.vue               [Home autenticado]
│       ├── Configuracion.vue           [Página de temas]
│       ├── Auth/
│       │   └── Login.vue               [Formulario login]
│       └── Usuarios/
│           ├── Empleados.vue           [CRUD Empleados]
│           └── Clientes.vue            [CRUD Clientes]
└── views/
    └── app.blade.php                   [Template Inertia]
```

### Directorio `database/`

```
database/
├── migrations/
│   ├── 2025_11_26_050952_create_usuario_table.php
│   ├── 2025_11_26_051100_create_empleado_table.php
│   ├── 2025_11_26_051101_create_propietario_table.php
│   ├── 2025_11_26_051200_create_cliente_table.php
│   ├── 2025_11_26_051201_create_proveedor_table.php
│   ├── 2025_11_26_051300_create_servicio_table.php
│   ├── 2025_11_26_051301_create_promocion_table.php
│   ├── 2025_11_26_051302_create_promocion_servicio_table.php
│   ├── 2025_11_26_051400_create_orden_table.php
│   ├── 2025_11_26_051401_create_orden_detalle_table.php
│   ├── 2025_11_26_051500_create_equipo_table.php
│   ├── 2025_11_26_051501_create_mantenimiento_table.php
│   ├── 2025_11_26_051600_create_insumo_table.php
│   ├── 2025_11_26_051601_create_inventario_table.php
│   ├── 2025_11_26_051602_create_orden_proceso_table.php
│   ├── 2025_11_26_051603_create_proceso_insumo_table.php
│   ├── 2025_11_26_051700_create_pago_table.php
│   └── 2025_11_26_051800_create_pagina_table.php
└── seeders/
    ├── DatabaseSeeder.php              [Usuario admin]
    └── MenuSeeder.php                  [11 ítems de menú]
```

### Directorio `routes/`

```
routes/
├── web.php                             [Rutas principales]
└── auth.php                            [Rutas de autenticación]
```

---

## 📊 Diagramas UML

### Diagrama de Casos de Uso

```
                    ┌─────────────────────────────────────┐
                    │   Sistema Lavandería BELÉN          │
                    └─────────────────────────────────────┘
                                    │
        ┌───────────────────────────┼───────────────────────────┐
        │                           │                           │
   ┌────▼────┐                 ┌────▼────┐               ┌─────▼─────┐
   │Propietario│                │Empleado│               │  Cliente  │
   └─────────┘                 └─────────┘               └───────────┘
        │                           │                           │
        │                           │                           │
        ├─── Gestionar Empleados    │                           │
        ├─── Ver Reportes           │                           │
        ├─── Gestionar Equipos      ├─── Registrar Órdenes     │
        ├─── Gestionar Insumos      ├─── Gestionar Clientes    │
        ├─── Configurar Sistema     ├─── Procesar Órdenes      │
        ├─── Gestionar Promociones  ├─── Registrar Pagos       │
        ├─── Ver Dashboard          ├─── Ver Dashboard         │
        └─── Cambiar Tema           └─── Cambiar Tema          │
```

### Diagrama de Clases (Modelos Principales)

```
┌─────────────────────┐
│      Usuario        │
├─────────────────────┤
│ - id: int           │
│ - email: string     │
│ - password: string  │
│ - tipo_usuario: enum│
│ - estado: boolean   │
└──────────┬──────────┘
           │ (TPT)
    ┌──────┴──────┐
    │             │
┌───▼──────┐  ┌───▼──────────┐
│ Empleado │  │ Propietario  │
├──────────┤  ├──────────────┤
│ - nombre │  │ - nombre     │
│ - ci     │  │ - ci         │
│ - cargo  │  │ - telefono   │
│ - sueldo │  └──────────────┘
└────┬─────┘
     │ 1:N
     │
┌────▼─────────────┐           ┌──────────────┐
│      Orden       │           │   Cliente    │
├──────────────────┤           ├──────────────┤
│ - nro: string(PK)│◄──────────│ - id: int    │
│ - fecha: date    │   N:1     │ - nombre     │
│ - estado: enum   │           │ - telefono   │
│ - total: decimal │           │ - direccion  │
└──────┬───────────┘           └──────────────┘
       │ 1:N
       │
┌──────▼────────────┐         ┌──────────────┐
│  OrdenDetalle     │         │   Servicio   │
├───────────────────┤         ├──────────────┤
│ - id: int         │◄────────│ - id: int    │
│ - nro_orden: FK   │   N:1   │ - nombre     │
│ - id_servicio: FK │         │ - precio     │
│ - cantidad: int   │         │ - duracion   │
│ - subtotal: decimal│        └──────────────┘
└───────────────────┘
```

### Diagrama de Componentes (Frontend)

```
┌─────────────────────────────────────────────────────────┐
│                  Aplicación Vue 3                       │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  ┌───────────────────────────────────────────────┐     │
│  │         Layouts/                              │     │
│  │  ┌──────────────────┐  ┌──────────────────┐  │     │
│  │  │Authenticated     │  │  GuestLayout     │  │     │
│  │  │Layout.vue        │  │  .vue            │  │     │
│  │  │ • Sidebar        │  │  • Login Form    │  │     │
│  │  │ • Header         │  │  • Tema gradient │  │     │
│  │  │ • Footer         │  └──────────────────┘  │     │
│  │  └──────────────────┘                        │     │
│  └───────────────────────────────────────────────┘     │
│                                                         │
│  ┌───────────────────────────────────────────────┐     │
│  │         Pages/                                │     │
│  │  ┌──────────────┐  ┌──────────────────────┐  │     │
│  │  │ Dashboard    │  │  Configuracion.vue   │  │     │
│  │  │ .vue         │  │  • ThemeSelector     │  │     │
│  │  └──────────────┘  └──────────────────────┘  │     │
│  │  ┌──────────────────────────────────────┐    │     │
│  │  │ Auth/Login.vue                       │    │     │
│  │  │ • Email/Password                     │    │     │
│  │  │ • Sin "Remember me"                  │    │     │
│  │  └──────────────────────────────────────┘    │     │
│  └───────────────────────────────────────────────┘     │
│                                                         │
│  ┌───────────────────────────────────────────────┐     │
│  │         Composables/                          │     │
│  │  ┌──────────────────────────────────────┐    │     │
│  │  │  useTheme.js                         │    │     │
│  │  │  • reactive(theme, mode, fontSize)   │    │     │
│  │  │  • applyTheme()                      │    │     │
│  │  │  • watch() → localStorage            │    │     │
│  │  └──────────────────────────────────────┘    │     │
│  └───────────────────────────────────────────────┘     │
│                                                         │
│  ┌───────────────────────────────────────────────┐     │
│  │         Components/                           │     │
│  │  • ThemeSelector.vue                          │     │
│  │  • TextInput.vue                              │     │
│  │  • PrimaryButton.vue                          │     │
│  │  • ... (10+ componentes)                      │     │
│  └───────────────────────────────────────────────┘     │
└─────────────────────────────────────────────────────────┘
```

---

## ✅ Casos de Uso Implementados

### 1. Autenticación

| Caso de Uso | Estado | Archivo |
|-------------|--------|---------|
| Login (español) | ✅ Completo | `Login.vue`, `AuthController.php` |
| Logout | ✅ Completo | `AuthenticatedLayout.vue` |
| Validación de credenciales | ✅ Completo | `AuthController.php` |
| Sesión persistente | ✅ Completo | Laravel Session |

### 2. Menú Dinámico

| Caso de Uso | Estado | Archivo |
|-------------|--------|---------|
| Menú por rol (propietario) | ✅ Completo | `AppServiceProvider.php`, `MenuSeeder.php` |
| Menú por rol (empleado) | ✅ Completo | `AppServiceProvider.php`, `MenuSeeder.php` |
| Renderizado jerárquico | ✅ Completo | `AuthenticatedLayout.vue` |
| Submenús expandibles | ✅ Completo | `<details>` HTML5 |

### 3. Sistema de Temas

| Caso de Uso | Estado | Archivo |
|-------------|--------|---------|
| Cambio de tema (Kids/Young/Classic) | ✅ Completo | `useTheme.js`, `ThemeSelector.vue` |
| Modo claro/oscuro | ✅ Completo | `useTheme.js` |
| Tamaños de fuente | ✅ Completo | `useTheme.js` |
| Alto contraste | ✅ Completo | `useTheme.js` |
| Persistencia en localStorage | ✅ Completo | `watch()` en `useTheme.js` |
| Aplicación en login | ✅ Completo | `GuestLayout.vue` |

### 4. CRUD Básico (Pendiente de UI)

| Entidad | Controlador | Modelo | Vista |
|---------|-------------|--------|-------|
| Usuarios | ✅ `UsuarioController.php` | ✅ `User.php` | ⏳ Pendiente |
| Empleados | ✅ `EmpleadoController.php` | ✅ `Empleado.php` | ⏳ Pendiente |
| Clientes | ✅ `ClienteController.php` | ✅ `Cliente.php` | ⏳ Pendiente |
| Servicios | ✅ `ServicioController.php` | ✅ `Servicio.php` | ⏳ Pendiente |
| Promociones | ✅ `PromocionController.php` | ✅ `Promocion.php` | ⏳ Pendiente |
| Órdenes | ✅ `OrdenController.php` | ✅ `Orden.php` | ⏳ Pendiente |
| Equipos | ✅ `EquipoController.php` | ✅ `Equipo.php` | ⏳ Pendiente |
| Insumos | ✅ `InsumoController.php` | ✅ `Insumo.php` | ⏳ Pendiente |
| Inventario | ✅ `InventarioController.php` | ✅ `Inventario.php` | ⏳ Pendiente |
| Pagos | ✅ `PagoController.php` | ✅ `Pago.php` | ⏳ Pendiente |

---

## 🚀 Comandos Importantes

### Desarrollo

```bash
# Iniciar servidor Laravel
php artisan serve

# Compilar assets en desarrollo (hot reload)
npm run dev

# Compilar assets para producción
npm run build
```

### Base de Datos

```bash
# Ejecutar todas las migraciones
php artisan migrate

# Ejecutar migraciones y seeders
php artisan migrate:fresh --seed

# Ejecutar solo MenuSeeder
php artisan db:seed --class=MenuSeeder

# Ejecutar solo DatabaseSeeder (usuario admin)
php artisan db:seed --class=DatabaseSeeder
```

### Cache

```bash
# Limpiar todos los caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 📝 Próximos Pasos

### Fase 2: Vistas CRUD

1. ✅ Crear páginas Vue para cada entidad
2. ✅ Implementar formularios con validación
3. ✅ Agregar tablas con paginación
4. ✅ Integrar búsqueda y filtros

### Fase 3: Funcionalidades Avanzadas

1. ⏳ Sistema de reportes (PDF/Excel)
2. ⏳ Integración PagoFácil (QR payments)
3. ⏳ Notificaciones en tiempo real
4. ⏳ Historial de órdenes

### Fase 4: Optimización

1. ⏳ Middleware de roles (propietario/empleado)
2. ⏳ Validación con FormRequests
3. ⏳ Contador de visitas en controladores
4. ⏳ Tests unitarios y de integración

---

## 📞 Contacto y Soporte

**Proyecto Académico:** Tecnología Web - UMSS 2025  
**Sistema:** Lavandería BELÉN  
**Versión:** 1.0.0  
**Fecha:** Noviembre 26, 2025

---

## 🔖 Glosario Técnico

- **TPT (Table Per Type):** Patrón de herencia en bases de datos donde cada clase concreta tiene su propia tabla
- **Inertia.js:** Framework que conecta Laravel con Vue/React sin necesidad de API
- **Eloquent:** ORM de Laravel para mapeo objeto-relacional
- **Composable:** Función reutilizable en Vue 3 Composition API
- **CSS Variables:** Variables CSS nativas (--nombre-variable) para temas dinámicos
- **localStorage:** Almacenamiento persistente en el navegador
- **SPA (Single Page Application):** Aplicación de una sola página que no recarga
- **CRUD:** Create, Read, Update, Delete (operaciones básicas)

---

**Fin de la documentación** 🎉
