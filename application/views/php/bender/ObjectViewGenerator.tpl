{% raw %}
/**
 * My PseudoClass
 * @author {{ author }}
 */





Class {{ Class }}

{% for field in fields  %}
    Property {{ field.getVarName() }}
{% endfor %}
 
{% for field in fields  %}
    Method {{ field.getGetterName() }} Returns {{ field.getDataType() }}
    Method {{ field.getSetterName() }} Receives {{ field.getDataType() }}

{% endfor %}

 
End {{ Class }} Class

{% endraw %}
