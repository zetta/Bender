<?php 
/**
 * {{ brandName }}
 *
 * {{ description }}
 *
 * @category   lib
 * @package    lib_search
 * @copyright  {{ copyright }}
 * @author     {{ author }}
 * @version    {{ version }} SVN: $Id$
 */
 
{% if flags["add-includes"] %}
/**
 * Dependences
 */
require_once "{{ route.getLib('CriteriaGenerator') }}";
{% endif %}

/**
 * BuilderCriteria Class
 * @category   lib
 * @package    lib_seach
 * @copyright  {{ copyright }}
{% if showBenderSignature %}
 * @copyright  {{ benderSignature }}
 * @author     chentepixtol 
{% endif %}
 * @version    {{ version }} SVN: $Revision$
 */
class BuilderCriteria
{
{% for table in tables %}

   /**
    * Build a Criteria
{% for field in table.getFields() %}
{% if field.getBaseDataType() == 'timestamp' or field.getBaseDataType() == 'date' %}
    * @param {{ field.getDataType() }} ${{ field.getVarName() }}Start
    * @param {{ field.getDataType() }} ${{ field.getVarName() }}End
{% else %}
    * @param {{ field.getDataType() }} ${{ field.getVarName() }}
{% endif %}
{% endfor %}
    * @return Criteria
    */
    public static function {{ table.getLowerObject() }}({% for field in table.getFields() %}{% if field.getBaseDataType() == 'timestamp' or field.getBaseDataType() == 'date' %}${{ field.getVarName() }}Start = null, ${{ field.getVarName() }}End = null{% else %}${{ field.getVarName() }} = null{% endif%}{% if loop.last == false %}, {%endif%}{% endfor %})
    {
{% if flags["add-includes"] %}
        require_once "{{ route.getObject('BeanGenerator',  table  ) }}";

{% endif %}
        $criteria = new Criteria();
{% for field in table.getFields() %}
{% if field.getBaseDataType() == 'timestamp' or field.getBaseDataType() == 'date' %}
        if( null != ${{ field.getVarName() }}Start)
            $criteria->add({{ table.getObject }}::{{ field.getConstantName() }}, ${{ field.getVarName() }}Start, Criteria::GREATER_OR_EQUAL);
        if( null != ${{ field.getVarName() }}End)
            $criteria->add({{ table.getObject }}::{{ field.getConstantName() }}, ${{ field.getVarName() }}End, Criteria::LESS_OR_EQUAL);
{% elseif field.getDataType == 'string' %}
        if( null != ${{ field.getVarName() }})
            $criteria->add({{ table.getObject }}::{{ field.getConstantName() }}, ${{ field.getVarName() }}, Criteria::LIKE);
{% else %}
        if( null != ${{ field.getVarName() }})
            $criteria->add({{ table.getObject }}::{{ field.getConstantName() }}, ${{ field.getVarName() }}, is_array(${{ field.getVarName() }}) ? Criteria::IN : Criteria::EQUAL);
{% endif %}
{% endfor %}
        return $criteria;
    } 
	
   /**
    * Build a Criteria from array
    * @param array $fields
    * @return Criteria
    */
    public static function {{ table.getLowerObject() }}FromArray($fields)
    {
        $criteria = self::{{ table.getLowerObject() }}({% for field in table.getFields() %}{% if field.getBaseDataType() == 'timestamp' or field.getBaseDataType() == 'date' %}$fields['{{field.getSimpleName()}}_start'], $fields['{{field.getSimpleName()}}_end']{% if loop.last == false %}, {%endif%} {% else %}$fields['{{field.getSimpleName()}}']{% if loop.last == false %}, {%endif%}{% endif %}{% endfor %});
        return $criteria;
    } 
{% endfor %}
}
