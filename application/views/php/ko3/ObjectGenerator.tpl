<?php 
/**
 * {{ Class }}, Kohana 3.x  Model
 *
 * @category   models
 * @package    models
 * @copyright  {{ copyright }} 
 * @author     {{ author }}
{% if showBenderSignature %}
 * @copyright  {{ benderSignature }}
 * @author     <zetta> & <chentepixtol>
{% endif %}
 * @version    {{ version }} SVN: $Revision$
 */
class {{ Class }} extends ORM 
{

    /**
     * @access protected
     * @var string
     */
    protected $_db = 'default';
    
    /**
     * @access protected
     * @var boolean
     */
    protected $_table_names_plural = false;
    
    /**
     * @access protected
     * @var string
     */
    protected $_table_name = '{{ table.getTableName() }}';
{% if table.hasPrimaryField() %}

    /**
     * @access protected
     * @var string
     */
    protected $_primary_key = '{{ table.getPrimaryField().getName() }}';
{% endif %}

    /**
     * default for $_table_columns: use db introspection to find columns and info
     * @see http://v3.kohanaphp.com/guide/api/Database_MySQL#list_columns for all possible column attributes
     * @access protected
     * @var array
     */
    protected $_table_columns  = array(
{% for field in nonPrimaryFields %}
        '{{ field.getName }}' => array('data_type' => '{{ field.getDataType() }}', 'is_nullable' => {% if field.isRequired() %}FALSE{% else %}TRUE{% endif %}), 
{% endfor %}
    );
    
    /**
     * Nice names =) 
     * @access protected
     * @var array
     */
    protected $_labels  = array(
{% for field in fields %}
        '{{ field.getName }}' => '{% if field.getComment() %}{{ field.getComment() }}{% else %}{{ field.getVarName() }}{% endif %}', 
{% endfor %}
    );
{% for field in foreigns %}

    /**
     * get by {{ field.getName() }}
     * @param {{ field.getDataType() }} ${{ field.getName() }}
     * @return mixed
     */
    function get_by_{{ field.getName() }}(${{ field.getName() }})
    {
        return $this->where('{{ field.getName() }}','=',${{ field.getName() }})->find_all();
    }
{% endfor %}
{% for field in uniqueFields %}
  
    /**
     * get by {{ field.getName() }}
     * @param {{ field.getDataType() }} ${{ field.getName() }}
     * @return mixed
     */
    function get_by_{{ field.getName() }}(${{ field.getName() }})
    {
        return $this->where('{{ field.getName() }}','=',${{ field.getName() }})->find();
    }
{% endfor %}

}


