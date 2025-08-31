# 📦 Kiosko App - Aplicación de Escritorio en PHP Native

**Kiosko App** es una aplicación de escritorio desarrollada con PHP nativo destinada a la gestión integral de recursos para pequeños negocios como kioskos, depósitos, entre otros. Su arquitectura está basada en MVC y permite operar tanto de forma online como local, con funciones completas de login, inventario, ventas, y más.

---

## 🧩 Características Principales

- **Login Seguro** usando una tabla `clients` y verificación de credenciales mediante API.
- **Modo Offline/Online** con sistema de inicio de sesión local en caso de pérdida de conexión.
- **Inventario** con CRUD completo, búsqueda, edición en línea, paginación y seedeers para datos simulados.
- **Gestión de Ventas**, incluyendo control de stock, carrito de compras y resumen de negocio.
- **Control de Sesión** por tiempo y seguridad adicional ante licencias vencidas.
- **Interfaz Personalizable** y mejora progresiva en vistas y preferencias del usuario.

---

## 🛠 Tecnologías Usadas

- **PHP Nativo** (Sin frameworks externos)
- **JavaScript** (Sincronización, validaciones, redirecciones)
- **HTML/CSS**
- **SweetAlert** para modales de confirmación y alertas visuales
- **API REST** para consumo de clientes y verificación de licencias

---

## 🗂 Versiones y Cambios

### ✅ v1.0
- Login básico usando tabla `clients`
- Verificación de seguridad vía API

### ✅ v1.1
- Vista principal agregada
- CRUD completo de productos (Inventario)

### ✅ v1.2
- Búsqueda, paginación y edición inline en inventario
- Agregado de campos adicionales para cálculos
- Seeder de productos para testeo

### ✅ v1.3 / v1.3.1
- Personalización de vista inventario
- Preferencias de usuario
- Nuevos campos en productos y controladores

### ✅ v1.4 / v1.4.1
- Sección **Ventas** completa (modelo, tabla, lógica)
- Login local en caso de conexión fallida

### ✅ v1.5 / v1.5.1
- Sección **Resumen** y **Mi negocio**
- Corte de sesión por tiempo inactivo
- Soporte de actualización de licencia al reinicio
- Cambio de nombre y nuevas traducciones

### ✅ v1.6
- Corrección de rutas API
- Configuración de ventana del escritorio
- Agregado de SweetAlert
- Prevención de agregar productos sin stock al carrito

### ✅ v1.7
- Mejora visual en modales
- Correcciones en sincronización de listas de productos

### ✅ v2.4
- Aviso de productos con bajo stock de bultos
- Tabla de productos con bajo stock por bulto
- Monitoreo en tiempo real del inventario
- Alertas visuales para stock mínimo
- Resumen de ventas y ganancias por período
- Selección de rango de días (1-30 días)
- Muestra:
- - Ventas totales
- - Ganancia total
- - Cantidad de productos vendidos
- Lista de productos más vendidos
- Filtrado por rango de días
- Muestra:
- - Nombre del producto
- - Código
- - Cantidad vendida
- - Total recaudado
- Selector de fecha específica
- Resumen detallado por día
- Muestra:
- - Ventas totales del día
- - Ganancia del día
- - Productos vendidos en el día
- Sistema de Actualizaciones
- - Botón para consultar actualizaciones
- - Barra de progreso de actualización
- - Mensajes de estado de actualización
---

## 📌 Instalación

1. Clonar el repositorio:
   ```bash
   git clone https://github.com/tuusuario/kiosko-app.git
