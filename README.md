# 🐾 Proyecto Web para Restaurante 🐾

# 📋 Contenido

---

### 🌟 **Índice de Secciones**

1. [💻 **Tecnologías Utilizadas**](#-tecnologías-utilizadas)
2. [📊 **Base de Datos**](#-base-de-datos)
3. [🧪 **Cómo Probar el Proyecto**](#-cómo-probar-el-proyecto)
   4. **Backend**
      5. API REST
      6. Web socket
   5. **Frontend**
5. [🧪 **Configuraciones Finales**](#-configuraciones-finales)
5. [🗂️ **Estructura del Proyecto**](#-estructura-del-proyecto)
6. [📞 **Contacto**](#-contacto)
---

## 💻 Tecnologías Utilizadas

### 🐶 Backend 🐶

- PDO (PHP Data Objects) es una extensión de PHP que define una interfaz ligera y consistente para acceder a bases de datos. 💾
- MySQL es un sistema de gestión de bases de datos relacional de código abierto. 🐬
- JSON (JavaScript Object Notation) es un formato ligero de intercambio de datos. 📑
- Las sesiones en PHP permiten almacenar datos de usuario en el servidor para ser utilizados en múltiples páginas. 🐾
- PSR HTTP Message es un estándar de PHP-FIG para representar mensajes HTTP. 📨
- Los encabezados HTTP en PHP se utilizan para enviar encabezados HTTP sin formato directamente al cliente. 📬
- PHP dotenv es una librería que carga variables de entorno desde un archivo .env al entorno de PHP. 🌱
- HTTP Interop Guzzle es una fábrica de solicitudes y respuestas HTTP basada en Guzzle. 🧩
- Guzzle PSR-7 es una implementación de PSR-7, que es un estándar para representar mensajes HTTP en PHP. 📨
- Markdown es un lenguaje de marcado ligero con una sintaxis de formato de texto plano. ✏️
- Nyholm PSR-7 es una implementación de PSR-7, que es un estándar para representar mensajes HTTP en PHP. 📬
- PHP es un lenguaje de programación de scripts del lado del servidor que se utiliza principalmente para el desarrollo web. 🐘

### 🐱 Frontend 🐱

- El módulo Path de Node.js proporciona utilidades para trabajar con rutas de archivos y directorios. 🛤️
- Babel Loader es un cargador para Webpack que permite transpilar archivos JavaScript utilizando Babel. 🔄
- Babel es un transpilador de JavaScript que permite utilizar las características más recientes del lenguaje. 🔄
- Webpack es un empaquetador de módulos para aplicaciones JavaScript modernas. 📦
- AJAX (Asynchronous JavaScript and XML) es una técnica para crear aplicaciones web interactivas. 🔄
- La depuración de JavaScript implica el uso de herramientas y técnicas para encontrar y corregir errores en el código JavaScript. 🐞
- CSS (Cascading Style Sheets) es un lenguaje de diseño gráfico para definir y crear la presentación de un documento escrito en un lenguaje de marcado. 🎨
- Babel Preset Env es un preset de Babel que permite utilizar las últimas características de JavaScript. 🌐
- Node.js es un entorno de ejecución para JavaScript que permite ejecutar código JavaScript en el servidor. 🟢
- JavaScript es un lenguaje de programación que se utiliza para crear contenido dinámico en el navegador. 🌐

## 🌈 ¡Gracias por visitar mi proyecto! 🌈
Esperamos que disfrutes explorando mi código tanto como nosotros disfrutamos escribiéndolo. 🐾

---

## 📊 Base de Datos

---

El script de la base de datos en **MySQL** está incluido en este proyecto junto con el README. Solo necesitas importarlo. El script incluye:

- 📁 **Creación de las tablas**
- 📝 **Registros iniciales**
- 🔄 **Rutinas utilizadas para automatizar procesos**

---

## 🧪 Cómo Probar el Proyecto

### Backend

Para iniciar el backend, sigue estos pasos:

1. Navega a la carpeta del backend:
    ```sh
    cd /restaurant-system/backend
    ```
2. Inicia el servidor PHP:
    ```sh
    php -S localhost:8000
    ```
3. Las APIs se pueden consumir en:
    ```
    http://localhost:8000/api/{endpoint}
    ```
4. Levantar el servicio de web socket para mensajeria en otro puerto
   ```sh
    php websocket_server.php
    ```

### Frontend

Para iniciar el frontend, sigue estos pasos:

1. Navega a la carpeta del frontend:
    ```sh
    cd /restaurant-system/frontend
    ```
2. Inicia el servidor PHP:
    ```sh
    php -S localhost:8100
    ```
3. Accede a la aplicación en:
    ```
    http://localhost:8100
    ```

---

## 🧪 Configuraciones Finales

---

### Backend

Es necesario configurar en composer:

1. Navega a la carpeta del backend:
    ```sh
    cd /restaurant-system/backend
    ```
2. Actualice el composer:
    ```sh
    composer update
    ```

### Frontend

No es necesario, pero para serializar los archivos js:

1. Navega a la carpeta del frontend:
    ```sh
    cd /restaurant-system/frontend
    ```
2. Use npm:
    ```sh
    npm run buil
    ```
3. Accede a la aplicación en:
    ```
    http://localhost:8100
    ```

---

## 🗂️ Estructura del Proyecto

---

```plaintext
/restaurant-system
├── .idea
├── backend
│   ├── .idea
│   ├── ApiReniecDni
│   │   ├── Env.php
│   │   └── PersonaReniec.php
│   ├── Auth
│   │   ├── Controller.php
│   │   ├── index.php
│   │   ├── Middleware.php
│   │   ├── Model.php
│   │   └── Router.php
│   ├── Conexion
│   │   ├── Database.php
│   │   └── Env.php
│   ├── Controller
│   │   └── BaseController.php
│   ├── Microservices
│   │   ├── ApiReniec
│   │   │   ├── Controller.php
│   │   │   ├── Model.php
│   │   │   └── Routes.php
│   │   ├── Category
│   │   ├── Costumer
│   │   ├── CostumerType
│   │   ├── Detalle
│   │   ├── Factura
│   │   ├── Food
│   │   ├── Modifications
│   │   ├── ModificationsOrders
│   │   ├── Rol
│   │   └── User
│   ├── Model
│   │   └── BaseModel.php
│   ├── Router
│   │   ├── IRouter.php
│   │   └── Router.php
│   ├── Test
│   │   └── TestConexion.php
│   ├── vendor
│   ├── .env
│   ├── composer.json
│   ├── composer.lock
│   └── index.php
├── frontend
│   ├── app
│   │   ├── login
│   │   ├── menu
│   │   │   ├── roles
│   │   │   │   ├── administrador
│   │   │   │   └── mesero
│   │   │   │      ├── modulos
│   │   │   │      │   ├── carta
│   │   │   │      │   └── orden
│   │   │   │      │      ├── css
│   │   │   │      │      ├── js
│   │   │   │      │      ├── logica
│   │   │   │      │      ├── orden.php
│   │   │   │      │      └── resumen.php
│   │   │   │      └── menu.php
│   │   │   └── menu.php
│   │   └── register
│   ├── assets
│   │   ├── dist
│   │   │   ├── images
│   │   │   │   └── tomato.png
│   │   │   ├── carta.bundle.js
│   │   │   ├── categoria.bundle.js
│   │   │   ├── dni.bundle.js
│   │   ├── icons
│   │   └── js
│   ├── config
│   ├── css
│   ├── includes
│   │   ├── footer.php
│   │   └── header.php
│   ├── node_modules
│   ├── .babelrc
│   ├── index.php
│   ├── package.json
│   ├── package-lock.json
│   └── webpack.config.js
├── .gitattributes
├── .hintrc
├── .htaccess
├── README.md
└── restaurant_db.sql
```

---

## 📞 **Contacto**

---

¿Tienes alguna duda o necesitas asistencia? ¡Estoy aquí para ayudarte!

### 📧 **Correo Electrónico**
 - Para consultas generales, puedes enviarme un correo a: [U20310552@utp.edu.pe](mailto:U20310552@utp.edu.pe)
 - Mi correo personal: [kenidingh@gmail.com](mailto:kenidingh@gmail.com)

### 📱 **Redes Sociales**
Sígueme en mis redes sociales para estar al día con las últimas novedades:
- [TikTok](https://www.tiktok.com/@henry.keniding.ta)
- [LinkedIn](https://www.linkedin.com/in/henry-keniding-tarazona-lazaro-277726249)
- [Facebook](https://www.facebook.com/henrykeniding.tarazonalazaro.3)
