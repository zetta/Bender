Bender : Scripts Disponibles
============================

La magia de Bener radica en los scripts que se hayan escrito, ya que la intención de escribir Bender
es que cada desarrollador pueda escribir sus scripts y compartirlos sería imposible escribir la documentación
a todos los scripts generados por terceros, sin embargo intentaremos ir agregando mas scripts  y su 
documentación de forma periódica.


PHP
---------


### default

    [bash]
    $ ./bender generator:run php

Se generan archivos en PHP, para su uso en `ZendFramework` que contiene la siguiente estructura.

  - application
    - controllers
    - models
      - beans
      - catalogs
      - collections
      - exceptions
    - views
  - lib
    - controller
    - db
    - search
    - utils

#### Modificadores

| Modificador            | Descripción
| ---------------------- | -----------------------------------------------------------------------
| `--use-validators`     | Genera clases que validan los que los objetos tengan datos válidos
| `--use-behaviors`      | Genera clases que modifican el comportamiento de los `catalogs`
| `--use-factories`      | Genera clases que instancian los `beans`
| `--generate-cruds`     | Genera controllers con un CRUD básico
| `--add-includes`       | Agrega los `require-once` necesarios en los archivos generados
| `--use-zend-date`      | Genera un método dentro de los beans para obtener las fechas como un objeto `Zend_Date`


DOT
----------

### default 

    [bash]
    $ ./bender generator:run dot

Utilizando lenguaje **dot** genera los siguientes diagramas.

- Diagrama de clases

>Para poder utilizar los archivos generados, será necesario tener instalada la herramienta [Graphviz](http://www.graphviz.org/)
>y basta con un solo comando 
>`dot -Tpng -O class-diagram.dot`




