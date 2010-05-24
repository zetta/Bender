{% extends "_baseModel.tpl" %}
{% block body %}
{% if flags["add-includes"] and table.extends() %}
/**
 * Dependences
 */
require_once "{{ route.getObject('BeanGenerator',  table.getExtendedTable()  ) }}";
{% endif %}

/**
 * Clase {{ Bean }}
 *
 * @category   lib
 * @package    lib_models
 * @subpackage lib_models_beans
 * @copyright  {{ copyright }} 
{% if showBenderSignature %}
 * @copyright  {{ benderSignature }}
 * @author     <zetta> & <chentepixtol>
{% endif %}
 * @version    {{ version }} SVN: $Revision$
 */
class {{ Bean }}{% if table.extends() %} extends {{ table.getExtendedTable().getObject() }} {% endif %}{{ extendedSentence }}
{
    /**
     * Constante que contiene el nombre de la tabla 
     * @static TABLENAME
     */
    const TABLENAME = "{{ tableName }}";
{% for relation in table.getManyToManyRelations() %}
{% set const as 'TABLENAME_' ~ formatter.camelCaseToUpperCase(table.getObject()) ~ '_' ~ formatter.camelCaseToUpperCase(relation['related'].getObject())  %}
    const {{ const }} = '{{ relation['source'].getTableName() }}';
{% endfor %}

    /**
     * Constantes para los nombres de los campos
     */
{% for field in fields %}
    const {{ field.getConstantName() }} = "{{ table.getTableName() }}.{{ field.getName() }}";
{% endfor %}
{% for field in fields %}    

    /**
     * ${{ field.getVarName() }} 
     * {{ field.getComment() }}
     * @var {{ field.getDataType() }} ${{ field.getVarName() }}
     */
    private ${{ field.getVarName() }};
{% if flags["use-zend-date"] and field.getType() == 'date/time' %}

    /**
     * ${{ field.getVarName() }}ZendDate
     * {{ field.getComment() }}
     * @var Zend_Date ${{ field.getVarName() }}ZendDate
     */
    private ${{ field.getVarName() }}ZendDate;
{% endif %}
{% endfor %}
{% for field in fields %}

    /**
     * Set the {{ field.getVarName() }} value
     * {{ field.getComment() }}
     * @param {{ field.getDataType() }} {{ field.getVarName() }}
     * @return {{ Bean }} ${{ bean }}
     */
    public function {{ field.getSetterName() }}(${{ field.getVarName() }})
    {
        $this->{{ field.getVarName() }} = ${{ field.getVarName() }};
        return $this;
    }

    /**
     * Return the {{ field.getVarName() }} value
     * {{ field.getComment() }}
     * @return {{ field.getDataType() }}
     */
    public function {{ field.getGetterName() }}()
    {
        return $this->{{ field.getVarName() }};
    }
{% if flags["use-zend-date"] and field.getType() == 'date/time' %}

   /**
     * Return the {{ field.getVarName() }} value as Zend_Date Object
     * Using Lazy Loading for Zend Dates
     * {{ field.getComment() }}
     * @return Zend_Date
     */
    public function {{ field.getGetterName() }}AsZendDate()
    {
        if(!isset($this->{{ field.getVarName() }}ZendDate))
           $this->{{ field.getVarName() }}ZendDate = new Zend_Date($this->{{ field.getVarName() }},"{{ field.getFormat() }}");
        return $this->{{ field.getVarName() }}ZendDate;
    }
{% endif %}
{% endfor %}

{% if status %}
    /**
     * {{ status.getUpperCaseName() }}
     * @var Array
     */
    public static ${{ status.getUpperCaseName() }} = array(
        'Active' => 1,
        'Inactive' => 2,
    );
    
    /**
     * {{ status.getUpperCaseName() }} Labels
     * @var Array
     */
    public static ${{ status.getUpperCaseName() }}Label = array(
        1 => 'Activo',
        2 => 'Inactivo',
    );
{% endif %}
}
{% endblock %}
