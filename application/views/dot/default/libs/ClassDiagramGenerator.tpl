digraph G {
        fontname = "Bitstream Vera Sans"
        fontsize = 8

        node [
                fontname = "Bitstream Vera Sans"
                fontsize = 8
                shape = "record"
        ]

        edge [
                fontname = "Bitstream Vera Sans"
                fontsize = 8
        ]

       subgraph clusterBeanPackage {
                label = "Bean Package"
{% for table in tables %}

    {{ table.getObject() }} [
        label = "{ {{ table.getObject() }}|{% for field in table.getFields() %}+ {{ field.getVarName() }} : {{ field.getDataType() }}\l{% endfor %}|{% for field in table.getFields() %}+ {{ field.getSetterName() }}() : void\l+ {{ field.getGetterName() }}() : {{ field.getDataType() }}\l{% endfor %}\l}"
    ]
    
    {% if table.extends() %}
    {{ table.getObject() }} -> {{ table.getExtendedTable().getObject() }}
    {% endif %}
{% endfor %}

      }

       subgraph clusterCatalogPackage {
                label = "Catalog Package"
{% for table in tables %}
       {{ table.getObject() }}Catalog [
                label = "{ {{ table.getObject() }}Catalog | | + create({{ table.getObject() }}): void\l + update({{ table.getObject() }}): void\l + delete({{ table.getObject() }}): void\l + getById(int): {{ table.getObject() }}\l + getByIds(array): {{ table.getObject() }}Collection\l + getByCriteria(Criteria): {{ table.getObject() }}Collection\l  }"
       ]
       {% if table.extends() %}
       {{ table.getExtendedTable().getObject() }}Catalog -> {{ table.getObject() }}Catalog
       {% else %}
       {{ table.getObject() }}Catalog -> Catalog  
       {% endif%}
{% endfor %}

       }
      
       subgraph clusterFactoryPackage {
                label = "Factory Package"
{% for table in tables %}
       {{ table.getObject() }}Factory [
                label = "{ {{ table.getObject() }}Factory | | + create(): {{ table.getObject() }}\l }"
       ]
{% endfor %}
       }
}
