{% extends "_baseModel.tpl" %}
{% block body %}

{% if flags["add-includes"] %}

require_once "{{ route.getLib('ValidatorExceptionGenerator') }}";

{% endif %}

/**
 * Dependencias
 */



/**
 * Clase {{ Validator }} que representa una excepción de objetos {$Bean}
 *
 * @category   lib
 * @package    lib_models
 * @subpackage lib_models_validators
 * @copyright  {{ copyright }}
{% if showBenderSignature %}
 * @copyright  {{ benderSignature }}
 * @author     zetta & chentepixtol
{% endif %}
 * @version    {{ version }} SVN: $Revision$
 */
class {{ Validator }} 
{

  /**
   * Error messages
   * @var array
   */
  private $messages = array();
  
  /**
   * Validate this {{ Bean }}
   * @param {{ Bean }} ${{ bean }}
   */
  public function validate(${{ bean }})
  {
  
     $this->messages = array();

{% for field in fields %}
     $chain = new Zend_Validate();
{% if field.getMaxLength() %}
     $chain->addValidator(new Zend_Validate_StringLength({{ field.getMinLength() }},{{ field.getMaxLength() }}));
{% endif %}
{% if field.getType() == "email "%}
     $chain->addValidator(new Zend_Validate_EmailAddress());
{% endif %}
{% if field.isRequired() %}
     $chain->addValidator(new Zend_Validate_NotEmpty());
{% endif %}
     if( !$chain->isValid( ${{ bean }}->{{ field.getGetterName() }}()){% if field.isRequired() == false %} && ${{ bean }}->{{ field.getGetterName() }}() != NULL {% endif %})
       $this->messages['{{ field.getName() }}'] = $chain->getMessages();

{% endfor %}

     if(count($this->messages))
      throw new ValidatorException('Invalid Object',1,$this->messages);

  }
  
}

{% endblock %}

