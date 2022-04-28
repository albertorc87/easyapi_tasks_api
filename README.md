# Proyecto de API Rest con EasyAPI

Este proyecto utiliza el framework EasyAPI y se utiliza de ejemplo para mi guía sobre como crear una API con arquitectura REST.

[Guía para aprender a trabajar con APIs](https://cosasdedevs.com/posts/guia-aprende-trabajar-con-apis/)

[EasyAPI Framework](https://packagist.org/packages/albertorc87/easyapi)

## Requisitos

- Versiones de PHP compatibles: 7.4 - 8.1
- Tener instalado composer.

## Instalar librerías

```bash
composer install
```

## Variables de entorno

Crear el archivo .env en base al .env.example y rellenar las variables de entorno. Para la JWT_KEY puedes generar una clave desde una terminal de linux y con el siguiente comando:

```bash
openssl rand -hex 32
```

## Levantar el servicio

### Desde PHP

En la raíz del proyecto lanzar:

```bash
php -S 0.0.0.0:8010 -t public/
```

### Desde Docker

```
docker-compose up
```

### Comprobar si está funcionando

    GET http://localhost:8010/v1/health