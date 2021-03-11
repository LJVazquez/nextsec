# NextSecApp

NextSecApp es un gestor de emails y dominios donde puedes buscar si tus datos fueron filtrados en la web.

## Caracteristicas

-   Busqueda de emails asociados a un dominio.
-   Busqueda de email de un dominio segun datos personales.
-   Busqueda de filtraciones de datos (contraseñas, tarjetas de credito, datos personales sensibles, etc) que incluyan el dominio o email:
-   Preview al archivo donde se encontró el leak de información.
-   Guardado de busquedas en base de datos local (Las APIs utilizadas trabajan con peticiones que expiran al poco tiempo).
-   Sistema de login, autenticacion y autorizacion para el CRUD.

## Pre requisitos

Requiere tener previamente instalados [PHP](https://www.php.net/) y [Laravel](https://laravel.com/).
Usar npm para instalar todas las dependencias complementarias necesarias.

```
npm install
```

## Configuracion

Editar el archivo `.env.example` y completador con los datos de tu base de datos y tus keys de [intelligenceX](https://intelx.io/) y [Hunter](https://hunter.io/).

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

INTELX_KEY=tu_key
HUNTERIO_KEY=tu_key
```

## Deployment

Ingresar en la terminal del sistema los siguientes comandos de PHP y para cargar la aplicacion en el localhost.

```php
php artisan migrate # Crea las tablas necesarias de la base de datos
```

```php
php artisan serve # Monta el servidor local con el proyecto
```

## Uso

La interfaz principal funciona como un CRUD de gestion de dominios y emails.

![](https://i.imgur.com/3gUnx3n.png)

Una vez dentro de la gestion del dominio o email pueden utilizarse las herramientas de busqueda.

### Inteligencia del dominio o email

![](https://i.imgur.com/OPm09Ii.png)

-   Busca filtraciones de informacion relacionadas al dominio o email
-   Descarga en la base de datos los resultados
-   Da acceso al archivo donde se encontraron tales filtraciones (Puede estar limitado a solo un preview, dependiendo de la key utilizada)

### Busqueda de emails por dominio

![](https://i.imgur.com/sAGByrc.png)

-   Busca en la web los emails asociados al dominio
-   Brinda fuentes de donde los encontro de haberlas y datos adicionales del email
-   Permite añadirlos a la base de datos para realizar una busqueda de inteligencia en los mismos

### Busqueda de email por datos personales

![](https://i.imgur.com/yFGUj6X.png)

-   Busca en la web los posibles emails de la persona indicada
-   Brinda fuentes de donde los encontro de haberlas
-   En caso de no encontrar fuentes, realiza una estimacion de cual podria ser utilizando los emails confirmados del dominio como base

## Licencia

Este es un proyecto personal y esta licenciado bajo la licencia MIT - Ver el archivo [LICENSE.txt](LICENSE.txt) para mas detalles.
