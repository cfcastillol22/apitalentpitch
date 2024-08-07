<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Prueba técnica TalentPitch Backend - Laravel

Proyecto creado con laravel 10 y base de datos relacional Mysql.

## Para correr en local:

Clonar el proyecto

```bash
  git clone https://github.com/cfcastillol22/apitalentpitch
```

Ir al directorio del proyecto

```bash
  cd apitalentpitch
```

Instalar dependencias

```bash
composer install
```

Modificar archivo .env, se deja .env.example, deben modificarse las siguientes variables de acuerdo a sus necesidades

```bash
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=laravel
  DB_USERNAME=root
  DB_PASSWORD=
  APP_KEY=
  JWT_SECRET=
  OPENAI_API_KEY=
```

Para generar APP_KEY puede hacerlo con

```bash
php artisan key:generate
```

La JWT_SECRET puede crearla usando

```bash
php artisan jwt:secret
```

Correr migraciones

```bash
php artisan migrate
```

Crear Datos de pruebas

```bash
php artisan db:seed
```

## Documentación API

puede acceder al proyecto en postman desde

[<img src="https://run.pstmn.io/button.svg" alt="Run In Postman" style="width: 128px; height: 32px;">](https://god.gw.postman.com/run-collection/13666984-d909b4f1-d4c9-4afa-bbfd-af57123ffb0b?action=collection%2Ffork&source=rip_markdown&collection-url=entityId%3D13666984-d909b4f1-d4c9-4afa-bbfd-af57123ffb0b%26entityType%3Dcollection%26workspaceId%3De017faff-1709-4fd7-b1d6-0c174e6e73b4)

#### Para usar la aplicación por primera vez deberá hacer uso del método register para crear su usuario.

_**Nota: también existirán usuarios de prueba creados por los seeders**_

```http
  GET /api/register
```

| Parameter  | Type       | Description                                      |
| :--------- | :--------- | :----------------------------------------------- |
| `name`     | `string`   | **Obligatorio**. tu nombre                       |
| `email`    | `email`    | **Obligatorio**. Tu email                        |
| `password` | `password` | **Obligatorio**. Contraseña mayor a 8 caracteres |

#### Una vez registrado deberás hacer uso del método login que te devolverá el token jwt de autenticación

```http
  GET /api/login
```

#### Con el Authorization Bearer Token podrás consumir los endpoints que están en la documentación postman.

## 🔗 Links

[![postman](https://img.shields.io/static/v1?style=for-the-badge&message=Postman&color=FF6C37&logo=Postman&logoColor=FFFFFF&label=)](https://documenter.getpostman.com/view/13666984/2sA3rzJCbU)

[![linkedin](https://img.shields.io/badge/linkedin-0A66C2?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/cfcastillol/)
