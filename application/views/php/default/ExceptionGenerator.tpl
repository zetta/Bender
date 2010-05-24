{% extends "_baseModel.tpl" %}
{% block body %}

/**
 * Clase {{ Exception }} que representa una excepcion
 *
 * @category   lib
 * @package    lib_models
 * @subpackage lib_models_exceptions
 * @copyright  {{ copyright }}
{% if showBenderSignature %}
 * @copyright  {{ benderSignature }}
 * @author     zetta & chentepixtol
{% endif %}
 * @version    {{ version }} SVN: $Revision$
 */
class {{ Exception }} extends Exception
{

}

{% endblock %}
