Bender : Configuración
======================

Bender se configura a partir de 2 archivos **YAML** localizados en la carpeta `application/config`

Para mas información acerca de archivos YAML [visite la pagina oficial](http://www.yaml.org/)

  1. [settings.yml](#settings)
  2. [schema.yml](#schema)


settings.yml           {#settings}
------------

La configuración principal de Bender se encuentra en este archivo.
A continuación se presenta una tabla con los valores que contiene dicho archivo.


| Parámetro              | Example                              | Description
| ---------------------- | ------------------------------------ | -------------------------------------------------------------
| `dsn`                  | sqlite:application/config/bender.db  | EL DSN que ocupará Bender para conectarse a la base de datos
| `username`             | root                                 | Nombre de usuario de nuestra conexión (si la necesita)
| `password`             | my_secret                            | El password asociado a nuestro nombre de usuario (si lo necesita)
| `schema_file`          | default                              | Nombre del archivo schema que usaremos 
| `add_bender_signature` | true                                 | Boleano que determina si queremos agregar la firma de bender a nuestros archivos generados
| `encoding`             | UTF-8                                | El encoding que usaremos en nuestros archivos
| `author`               | <zetta> <chentepixtol>               | El autor que aparecerá en los archivos (pon aqui tu nick)
| `brand_name`           | Bender                               | El nombre de tu empresa o el de tu proyecto
| `copyright`            | &copy;Copyright                      | Si quieres agregar informacion de copyright
| `description`          | Our Simple Models                    | Una breve descripcion de tu proyecto



schema.yml              {#schema}
----------

En realidad este archivo de configuración se llama `schema`.schema.yml donde `schema` es
el valor que le hemos dado al parámetro `schema_file` dentro del archivo **settings.yml**.

    # schema.yml
    schema:
      Person:
        table:     persons
        extends:   false
        options:   [ generate-crud ]
      User:
        table:     users
        extends:   Person
        options:   [ generate-crud ]
        relations:
          Photo:   { type : OneToMany }  #TODO
      Photo:
        table:     photos
        extends:   false
        options:   [ generate-crud ]
        fields:
          title:   { comment : 'El titulo de la fotografia' }
        relations:
          Album:   { type : manyToMany, table : albums_photos }
      Album: 
        table:     albums
        extends:   false
        options:   [ generate-crud ]
        relations:
          Photo:   { type : manyToMany, table : albums_photos }
      Test:
        table:     non_primary_table
        extends:   false
        options:   [ ]
        
     #NombreDelObjeto
     #  table:     nombre_de_la_tabla
     #  extends:   extiende de algun otro objeto?
     #  options:   array con las opciones que se le añadirán al objeto.
     #             estas opciones pueden variar de acuerdo al script que se esté ejecutando
     #  fields:    
     #    field1: ~  
     #    field2: ~
     #    field3:
     #       comment:    'Un comentario acerca del campo'
     #       type:       varchar|string|integer|etc...
     #       required:   true|false
     #       unique:     true|false
     #       max:        entero que representa el maxlength
     #       min:        entero que representa el minlength
     #       default:    valor por default
     #    fieldn: ~
     


Para generar un schema a partir de una base de datos ya configurada en **settings.yml** basta con correr el comando

    [bash]
    $ ./bender model:generate-schema [nombre-de-schema]

Esto generará un archivo `schema` en el directorio `application/config` con el nombre de schema que se especificó, 
si no se especificó nombre de schema se guardará como `generated.schema.yml`




