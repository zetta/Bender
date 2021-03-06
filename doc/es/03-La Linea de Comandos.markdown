Bender : La línea de Comandos
=============================


Bender se ejecuta en la linea de comandos, la sintaxis es.

    [bash]
    $ ./bender comando[:accion] [arg1] [arg2] [arg3] [argn] [--flag1] [--flag2] [--flagn]

    [cmd]
    > bender.bat comando[:accion] [arg1] [arg2] [arg3] [argn] [--flag1] [--flag2] [--flagn]


Comandos Disponibles
--------------------

### help
 
Muestra la ayuda de los comandos disponibles y el número de version de Bender

Utilice `help` `controller` para mostrar la ayuda de un controlador especifico


### test:unit

Corre los test unitarios. Para poder ejecutar este comando es necesario que tenga instalado `phpunit` en su
sistema, por favor refierase al capitulo **1-Introducción** para mas información

### test:interactive

Emulador de php dentro el scope de bender


### cache:clear

Elimina los archivos generados de la carpeta `output`[^1] y el archivo creado por el autoloader

| Modificador            | Descripción
| ---------------------- | -----------------------------------------------------------------------
| `--keep-autoloader`    | No elimina el caché que genera el autoloader

### generator:create-new
 
Parámetros que recibe:

- **lang** El lenguaje de programacion que utilizaremos 
- **pattern** [opcional] Es como nombraremos a nuestro `patrón` si no se especifica se utilizará `default`

Este comando se utiliza para crear un nuevo `patrón` de diseño dentro de la carpeta `lang` especificada, al ejecutarse
generará las carpetas necesarias para comenzar a escribir un nuevo script.

>Las vistas del generador pueden encontrarse en la carpeta `application/views/[lang]/[pattern]/`

Una vez que haya terminado de escribir su test (o si quiere probarlo) puede ejecutar el siguiente comando 
con los mismos parámetros que especificó para crearlo.


### generator:remove 

Parámetros que recibe:
- **lang** El lenguaje de programacion que utilizaremos 
- **pattern** El patrón a eliminar


### pack

Parámetros que recibe:

- **lang** El lenguaje de programacion que utilizaremos 
- **pattern**  El patrón que vamos a empaquetar

Empaqueta un script para poder distribuirlo a otros desarrolladores


### generator:run

Parámetros que recibe:

- **lang** El lenguaje de programacion que utilizaremos.
- **pattern** [opcional] Es como nombraremos a nuestro `patrón` si no se especifica se utilizará `default`.

Corre el Script especificado por los parámetros enviados, en el siguiente capítulo se intentará entrar en detalle acerca
de los scrips disponibles en bender (por lo menos los scripts que vienen empaquetados).


| Modificador            | Descripción
| ---------------------- | -----------------------------------------------------------------------
| `--isolated`           | Elimina el contenido de la carpeta output

### model:generate-schema

Parámetros que recibe:

- **schema** Nombre del archivo donde se guardará el schema construido.


Modificadores Globales
----------------------

Dependiendo del comando ejecutado, existen ciertos modificadores, sin embargo hay modificadores que no importando
el controlador, o el script en ejecución, pueden alterar el comportamiento de Bender.


| Modificador            | Descripción
| ---------------------- | -----------------------------------------------------------------------
| `--debug`              | Muestra información adicional, que puede servir de ayuda al depurar scripts
| `--quiet`              | Suprime toda salida de texto
| `--no-truncate-text`   | Deja de cortar los textos en el standard output 
| `--output-dir=PATH`    | Cambia el PATH destino de los archivos que genera Bender
| `--ignore-database`    | Omite el paso de recavar información de la base de datos 
| `--no-color`           | No muestra colores en la salida standard (aún cuando estos sean soportados)

### settings

Existe una manera en que podemos cambiar las preferencias de bender sin tener que estar haciendo cambios
en nuestro archivo **settings.yml**, y esta es enviando modificadores para que de tal manera y solo por esa ocación
se utilicen valores distintos a los ya configurados. 

Para mayor información por favor consulte el Capítulo 2 [02-configuracion]

| Parámetro                   | Modifica a:
| --------------------------- | ------------------------------------ 
| `dsn=MY_DSN`                | `dsn`
| `username=USER`             | `username`
| `password=PASS`             | `password`
| `schema_file=FILE`          | `schema_file`
| `add_bender_signature=true` | `add_bender_signature`
| `encoding=ENCODING`         | `encoding`
| `author=AUTOR`              | `author`
| `brand_name=NAME`           | `brand_name`
| `copyright=COPY`            | `copyright`
| `description=SUMMARY`       | `description`


[^1]: Siempre y cuando el modificador `output-dir` no haya sido definido
