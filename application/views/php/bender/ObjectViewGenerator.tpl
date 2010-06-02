{% raw %}
/**
 * My PseudoClass
 * {{ description }}
 * {{ brandName }}
 *
 * @category   models
 * @package    models
 * @copyright  {{ copyright }}
 * @author     {{ author }}, $LastChangedBy$
{% if showBenderSignature %}
 * @copyright  {{ benderSignature }}
 * @author     <zetta> & <chentepixtol>
{% endif %}
 * @version    {{ version }} SVN: $Id$
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
