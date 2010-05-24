{% extends "_baseModel.tpl" %}
{% block body %}
{% if flags["add-includes"] %}
/**
 * Dependences
 */
require_once "{{ route.getObject('BeanGenerator',  table  ) }}";
{% endif %}

/**
 * Clase {{ Factory }}
 *
 * @category   lib
 * @package    lib_models
 * @subpackage lib_models_factories
 * @copyright  {{ copyright }} 
{% if showBenderSignature %}
 * @copyright  {{ benderSignature }}
 * @author     <zetta> & <chentepixtol>
{% endif %}
 * @version    {{ version }} SVN: $Revision$
 */
class {{ Factory }}
{

   /**
    * Create a new {{ Bean }} instance
{% for field in fields %}
{% if primaryFields.contains(field) == false %}
    * @param {{ field.getDataType() }} ${{ field.getVarName() }}
{% endif %}
{% endfor %}
    * @return {{ Bean }}
    */
   public static function create({% for field in fields %}{% if primaryFields.contains(field) == false %}${{ field.getVarName() }}{% if loop.last == false %}, {%endif%}{%endif%}{% endfor %})
   {
      $new{{ Bean }} = new {{ Bean }}();
{% for field in fields %}
{% if primaryFields.contains(field) == false %}
      $new{{ Bean }}->{{ field.getSetterName() }}(${{ field.getVarName() }});
{% endif %}
{% endfor %}
      return $new{{ Bean }};
   }
   
    /**
     * Método que construye un objeto {{ Bean }} y lo rellena con la información del rowset
     * @param array $fields El arreglo que devolvió el objeto Zend_Db despues del fetch
     * @return {{ Bean }} 
     */
    public static function createFromArray($fields)
    {
        $new{{ Bean }} = new {{ Bean }}();
{% for primaryField in primaryFields %}
        $new{{ Bean }}->{{ primaryField.getSetterName() }}($fields['{{ primaryField.getName() }}']);
{% endfor %}
{% for field in fields %}
{% if primaryFields.contains(field) == false %}
        $new{{ Bean }}->{{ field.getSetterName() }}($fields['{{ field.getName() }}']);
{% endif %}
{% endfor %}
        return $new{{ Bean }};
    }
   
}
{% endblock %}
