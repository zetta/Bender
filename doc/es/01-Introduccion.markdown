Bender : Introducción
=====================

  1. [Prerequisitos](#prerequisitos)
  2. [Instalación](#instalacion)
  3. [Ejemplo de Uso](#ejemplo)


Prerequisitos        {#prerequisitos}
-------------

Para poder ejecutar Bender se necesita por lo menos:

- **PHP 5.2.4**

>Dependiendo de la complejidad de los scripts que se generen en Bender los requerimientos podrian variar
>para mas información remitase a la documentación del script


Instalación          {#instalacion}
------------

### Descargar la version mas reciente

  1. Descargar el archivo comprimido desde la [ultima version disponible](http://github.com/zetta/Bender/archives/master) 
  2. Descomprimir el archivo

### Instalar la version de desarrollo 
  
  1. Instalar Git
  2. `git clone git://github.com/zetta/Bender.git`
  

### Instalar phpunit para poder ejecutar los test

Bender utiliza [phpunit](http://www.phpunit.de/manual/current/en/index.html) para los test unitarios, 
si está interesado en realizar los test, o generar test para bender, primero debe instalar phpunit 
en su sistema.

Pada poder instalar *phpunit* es necesario tambien tener instalado [pear](http://pear.php.net/)

  1. `pear channel-discover pear.phpunit.de`
  2. `pear channel-discover pear.symfony-project.com`
  3. `pear install phpunit/PHPUnit`
  4. `pear channel-discover pear.php-tools.net`
  5. `pear install pat/vfsStream-alpha`


Ejemplo de Uso        {#ejemplo}
----------

    [bash]
    $ ./bender generator:run php
    
    [cmd]
    > bender.bat generator:run php
