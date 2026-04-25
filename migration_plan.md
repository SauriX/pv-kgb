# Plan de Migración: De PHP Legacy a Vue.js + PHP (APIs REST)

**Fecha de creación:** 13 de abril de 2026  
**Proyecto:** pv-kgb (Sistema de Punto de Venta / Restaurante)  
**Autor:** GitHub Copilot (basado en análisis del proyecto)

## Introducción

Este documento detalla un plan estratégico para migrar el proyecto `pv-kgb` de su arquitectura actual (PHP legacy con HTML/JS embebido) a una arquitectura moderna separada: Vue.js para el frontend y PHP con APIs REST para el backend. La migración busca mejorar la mantenibilidad, escalabilidad y experiencia de usuario, aprovechando la librería ESC/POS personalizada y la app local con WebSockets para impresión automática desde el navegador.

### Contexto del Proyecto Actual
- **Tecnologías:** PHP puro, MySQL (con wrappers de compatibilidad `mysql_*`), jQuery, Bootstrap, HTML/CSS embebido.
- **Arquitectura:** Monolítica, con lógica de negocio mezclada en archivos como `venta_domicilio.php`, `includes/impresora.php`.
- **Funcionalidades clave:** Ventas, domicilios, productos, reportes, impresión térmica (usando `kescpos.drv.php` y puente local `localhost:8080/imprimir.php`).
- **Problemas identificados:** Código espagueti, difícil de mantener, sin separación frontend/backend, dependencias legacy.

### Beneficios Esperados de la Migración
- **Separación de responsabilidades:** Frontend (Vue.js) maneja UI/UX; Backend (PHP) expone APIs.
- **UX moderna:** Interfaces reactivas, sin recargas completas de página.
- **Mantenibilidad:** Código modular, fácil de testear y escalar.
- **Integración con impresión:** Mantener y mejorar la app local con WebSockets para impresión automática, integrándola con Vue.js vía APIs.
- **Escalabilidad:** Soporte para múltiples dispositivos, APIs reutilizables.

## Evaluación del Proyecto Actual

### Módulos Principales
1. **Ventas y Cobros:** `venta_domicilio.php`, `venta.php`, `ac/cobrar.php` – Lógica de transacciones, impresión de tickets.
2. **Productos e Inventario:** `productos.php`, `ingredientes.php`, `categorias.php` – Gestión de catálogo, precios, impresoras por categoría.
3. **Domicilios:** `domicilio.php`, `ac/agrega_domicilio.php`, `imprimir_domicilio()` – Pedidos a domicilio, impresión de tickets.
4. **Reportes y Cortes:** `reportes.php`, `cortes.php`, `ver_cortes.php` – Análisis de ventas, cortes de caja.
5. **Configuración:** `configuracion.general.php` – Impresoras (`impresora_sd`, `impresora_cuentas`), autoprint, etc.
6. **Impresión:** `includes/impresora.php`, puente local `localhost:8080/imprimir.php`, librería ESC/POS personalizada.

### Dependencias Externas
- **DB:** MySQL (servidor remoto: `db5020093809.hosting-data.io`).
- **Impresión:** Librería ESC/POS personalizada, app local con WebSockets para impresión automática desde navegador (usable pero necesita mejoras para robustez).
- **Otros:** AWS S3, Postmark, Nexmo, QR codes.

### Puntos Críticos
- **Impresión:** Actualmente usa `esc_pos_open()` directo o puente HTTP. Con WebSockets, se puede integrar mejor con Vue.js.
- **Sesiones y Autenticación:** Basado en PHP sessions; migrar a JWT o similar en APIs.
- **Compatibilidad:** Mantener DB y lógica de negocio intacta inicialmente.

## Objetivos de la Migración

1. **Arquitectura Moderna:** Separar frontend (Vue.js SPA) y backend (PHP APIs REST).
2. **Integrar Impresión:** Usar la app local con WebSockets para impresión automática, enviando datos desde Vue.js a APIs que comuniquen con el servicio.
3. **Mejorar UX:** Interfaces reactivas, responsive, con componentes reutilizables.
4. **Robustez:** Refactorizar código legacy, agregar tests, manejar errores.
5. **Escalabilidad:** Soporte para múltiples usuarios/dispositivos, APIs reutilizables.

## Plan de Migración Paso a Paso

### Fase 1: Preparación y Evaluación (1-2 semanas)
- **Análisis detallado:** Mapear todos los archivos, identificar dependencias, crear diagrama de flujo (ej. ventas → impresión).
- **Configurar entorno:** Instalar Node.js, Vue CLI, Composer para PHP. Configurar servidor local para pruebas.
- **Backup y versionado:** Crear rama Git para migración, backups de DB.
- **Pruebas iniciales:** Ejecutar funcionalidades críticas (ventas, impresión) para baseline.

### Fase 2: Refactorización del Backend (PHP APIs) (4-6 semanas)
- **Elegir framework:** Usar Laravel o Slim para APIs REST. Instalar y configurar.
- **Crear APIs por módulo:**
  - `/api/auth/login` – Autenticación (migrar sesiones a JWT).
  - `/api/ventas` – CRUD de ventas, cobros.
  - `/api/domicilios` – Gestión de pedidos, impresión (llamar a `imprimir_domicilio()` o enviar a WebSockets).
  - `/api/productos` – Catálogo, precios, impresoras.
  - `/api/reportes` – Consultas de datos.
  - `/api/configuracion` – Impresoras, autoprint.
- **Integrar impresión:** Crear endpoint `/api/imprimir` que envíe datos a la app local con WebSockets (ej. WebSocket client en PHP para comunicar con el servicio).
- **DB:** Mantener MySQL, usar Eloquent (Laravel) para queries. Refactorizar `mysql_*` a PDO.
- **Sesiones:** Reemplazar con tokens JWT para APIs.
- **Testing:** Unit tests para APIs con PHPUnit.

### Fase 3: Desarrollo del Frontend (Vue.js) (6-8 semanas)
- **Configurar Vue:** Crear proyecto con Vue CLI, instalar Axios (para APIs), Vue Router, Pinia (estado).
- **Crear componentes por módulo:**
  - `Login.vue` – Autenticación.
  - `VentaDomicilio.vue` – Reemplaza `venta_domicilio.php`, consume `/api/ventas`.
  - `Productos.vue` – Gestión de catálogo.
  - `Domicilios.vue` – Pedidos, integración con impresión.
  - `Reportes.vue` – Dashboards con datos de APIs.
- **Integrar impresión:** Desde Vue, enviar datos a `/api/imprimir`, que use WebSockets para la app local.
- **UI/UX:** Usar BootstrapVue o Vuetify para mantener estilo similar, pero reactivo.
- **Rutas:** Configurar Vue Router para navegación SPA.
- **Testing:** Unit tests con Jest/Vue Test Utils.

### Fase 4: Migración Gradual y Integración (4-6 semanas)
- **Migrar módulos uno por uno:** Empezar con domicilios (menos crítico), luego ventas.
- **Compatibilidad:** Mantener páginas legacy activas mientras migras, usar flags para redirigir.
- **Impresión robusta:** Mejorar la app local con WebSockets (manejo de reconexiones, errores). Asegurar que Vue envíe datos correctos (JSON con nombre, teléfono, dirección).
- **DB migrations:** Si cambian esquemas, usar Laravel migrations.
- **Testing end-to-end:** Usar Cypress para flujos completos (venta → impresión).

### Fase 5: Optimización y Despliegue (2-4 semanas)
- **Performance:** Lazy loading en Vue, caching en APIs.
- **Seguridad:** Validar inputs, CORS, rate limiting.
- **Documentación:** APIs con Swagger/OpenAPI.
- **Despliegue:** Configurar servidor (ej. Nginx + PHP-FPM), CI/CD con GitHub Actions.
- **Monitoreo:** Logs para impresión, errores.

## Herramientas y Tecnologías Recomendadas

### Backend (PHP)
- **Framework:** Laravel (para APIs robustas) o Slim (ligero).
- **DB:** MySQL + Eloquent (Laravel).
- **Autenticación:** JWT (paquete `tymon/jwt-auth` en Laravel).
- **Impresión:** Mantener librería ESC/POS personalizada; integrar WebSockets con librería como `reactphp/websocket` o cliente PHP para conectar a la app local.

### Frontend (Vue.js)
- **Framework:** Vue 3 + Composition API.
- **Routing:** Vue Router.
- **Estado:** Pinia (reemplaza Vuex).
- **HTTP:** Axios para consumir APIs.
- **UI:** Vuetify (material design, responsive).
- **Build:** Vite (rápido).

### Otros
- **Testing:** PHPUnit (backend), Jest (frontend), Cypress (e2e).
- **Versionado:** Git con ramas (main, migration).
- **Impresión:** App local con WebSockets (mejorar para robustez: reconexiones, queue de impresiones).

## Consideraciones Especiales

### Impresión Automática
- **Actual:** Mezcla de `esc_pos_open()` directo y puente HTTP (`localhost:8080/imprimir.php`).
- **Migración:** Crear API `/api/imprimir/domicilio` que envíe JSON a la app local vía WebSockets. Ejemplo: `{ "tipo": "domicilio", "datos": { "nombre": "...", "telefono": "...", "direccion": "..." } }`.
- **Mejoras:** Añadir queue en la app local para manejar fallos, confirmaciones de impresión.

### Base de Datos
- Mantener esquema actual; refactorizar queries a prepared statements.
- Si se añade logging de impresiones, nueva tabla `impresiones_log`.

### Sesiones y Estado
- Migrar de PHP sessions a JWT en APIs; Vue maneja estado local con Pinia.

### Manejo de Roles y Usuarios
- **Actual:** Sistema básico de roles (ej. basado en `id_tipo_usuario` en tabla `usuarios`), sin permisos granulares.
- **Migración:** Implementar sistema avanzado de roles y permisos en backend (ej. con Laravel's Gates/Policies o paquete Spatie Laravel Permission).
  - **Roles:** Definir roles como Admin, Cajero, Mesero, Gerente, etc.
  - **Permisos:** Granulares, ej. `ver-ventas`, `editar-productos`, `imprimir-cortes`, `gestionar-usuarios`, `acceder-reportes`.
  - **Asignación:** Admin puede asignar permisos por rol (aplicable a todos los usuarios con ese rol) o directamente por usuario (overrides específicos).
  - **APIs:** Proteger endpoints con middleware (ej. `auth:sanctum` + `can:ver-ventas` en Laravel).
  - **Frontend:** Almacenar permisos en JWT o estado Vue (Pinia); mostrar/ocultar componentes/menús dinámicamente (ej. botón "Imprimir" solo si tiene permiso `imprimir`).
  - **DB:** Crear tablas `roles`, `permissions`, `role_user`, `permission_user` (usar migraciones de Laravel o Spatie).
  - **Funcionalidades:** Interfaz de admin (`Usuarios.vue`) para crear/editar roles, asignar permisos, gestionar usuarios. Validar permisos en cada acción (ej. no permitir editar productos sin permiso).
- **Beneficios:** Seguridad mejorada, flexibilidad para escalar negocio (ej. nuevos roles para sucursales).

## Mejoras y Plan de Migración de Base de Datos

### Evaluación Actual de la BD
- **Esquema:** Basado en respaldo `respaldo 25-03-2026.sql` y ALTER `altertable.sql`.
- **Tablas clave:** `ventas` (con campos como `id_metodo`, `monto_pagado`), `venta_detalle`, `productos`, `usuarios`, `categorias`, `cortes`, etc.
- **Problemas identificados:**
  - **Pagos:** Actualmente un solo método por venta (`id_metodo`). El ALTER agrega `monto_efectivo`, `monto_tarjeta`, `monto_transferencia` para múltiples formas, pero limita a una por tipo y no es normalizado (violación de 1NF).
  - **Normalización:** Falta en algunas áreas (ej. mesas como string en `ventas`, no tabla separada; datos redundantes).
  - **Índices:** Pocos índices en campos consultados frecuentemente (ej. `fecha`, `id_usuario` en `ventas`).
  - **Tipos de datos:** Algunos campos usan tipos subóptimos (ej. `varchar` largos donde bastan `int` o `decimal`).
  - **Auditoría:** Falta `created_at`, `updated_at` en muchas tablas.
  - **Constraints:** Pocos foreign keys o checks.
  - **Escalabilidad:** Sin particionamiento para tablas grandes como `ventas` (32k+ registros).

### Mejoras Propuestas
- **Normalización de Pagos:** Crear tabla `pagos` (id_pago, id_venta, tipo_pago, monto, num_cta, fecha_hora) para relación N:1 con `ventas`. Permite múltiples pagos por venta sin límite de tipos.
- **Otras mejoras:**
  - Añadir índices en `ventas.fecha`, `ventas.id_usuario`, `venta_detalle.id_venta`.
  - Separar `mesas` en tabla propia (`id_mesa`, `nombre`, `estado`) para mejor gestión.
  - Usar `DECIMAL(10,2)` consistentemente para montos.
  - Añadir campos de auditoría (`created_at`, `updated_at`) usando triggers o app.
  - Constraints: Foreign keys entre `ventas` y `usuarios`, `venta_detalle` y `productos`.
  - Particionamiento: Por fecha en `ventas` para consultas rápidas.
- **Compatibilidad con datos existentes:** Migrar datos actuales a nueva estructura (ej. convertir `id_metodo` y `monto_pagado` a registros en `pagos`).

### Plan de Migración de BD
- **Fase 1: Análisis y Backup (1 semana):**
  - Analizar dependencias (ej. queries en PHP que usan campos actuales).
  - Crear backup completo de BD.

- **Fase 2: Scripts de Migración (2-3 semanas):**
  - Usar Laravel migrations para cambios incrementales.
  - Ejemplo: Crear tabla `pagos`, migrar datos existentes, actualizar queries en PHP.
  - Scripts SQL para índices, constraints.

- **Fase 3: Testing y Validación (1-2 semanas):**
  - Probar en staging: Insertar ventas, consultar reportes, verificar integridad.
  - Asegurar que datos legacy se mantengan (ej. ventas antiguas con un pago).

- **Fase 4: Despliegue (1 semana):**
  - Ejecutar en producción con downtime mínimo (ej. durante cortes de caja).
  - Rollback plan: Restaurar backup si falla.

- **Herramientas:** Laravel Migrations, phpMyAdmin/HeidiSQL para scripts, PHPUnit para tests de DB.
- **Riesgos:** Pérdida de datos; mitigación con backups múltiples y validaciones.

## Riesgos y Mitigación

- **Riesgo:** Pérdida de funcionalidades durante migración.
  - **Mitigación:** Migración gradual, tests exhaustivos, rollback plan.

- **Riesgo:** Problemas con impresión (crítico para negocio).
  - **Mitigación:** Mantener puente legacy activo; probar integración WebSockets en staging.

- **Riesgo:** Curva de aprendizaje para equipo.
  - **Mitigación:** Capacitación en Vue.js/Laravel; empezar con prototipos.

- **Riesgo:** Compatibilidad con dispositivos legacy.
  - **Mitigación:** Asegurar responsive design, polyfills si necesario.

## Timeline Estimado
- **Total:** 16-26 semanas (4-6 meses), dependiendo del equipo.
- **Equipo sugerido:** 2-3 devs (1 backend, 1 frontend, 1 QA).
- **Costo aproximado:** Alto inicialmente, pero ROI en mantenibilidad.

## Conclusión

Esta migración transformará `pv-kgb` en una aplicación moderna, escalable y fácil de mantener, aprovechando tu librería ESC/POS y app con WebSockets para una impresión automática robusta. El enfoque gradual minimiza riesgos. Recomiendo empezar con un PoC (Proof of Concept) en domicilios para validar la arquitectura.

Para implementar, contacta para soporte detallado o ajustes al plan.</content>
<parameter name="filePath">c:\xampp\htdocs\pv-kgb\migration_plan.md