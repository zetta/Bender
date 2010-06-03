<?php 
/**
 * ZendFramework Model 
 * {{ description }}
 * {{ brandName }}
 *
 * @category   models
 * @package    models
 * @copyright  {{ copyright }}
 * @author     {{ author }}, $LastChangedBy$
{% if showBenderSignature %}
 * @copyright  {{ benderSignature }}
 * @author     zetta
{% endif %}
 * @version    {{ version }} SVN: $Id$
 * @see http://framework.zend.com/manual/en/learning.quickstart.create-model.html
 */

class {{ Class }}
{
{% for field in fields %}
    /**
     * @var {{ field.getDataType() }} $_{{ field.getName() }}
     */
    protected $_{{ field.getName() }};
{% endfor %}
 
    /**
     * Class Constructor
     */
    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }
 
    /**
     * Magic setter
     * @param string $name
     * @param mixed $value
     * @throws Exception
     */
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid guestbook property');
        }
        $this->$method($value);
    }
 
    /**
     * Magic getter
     * @param mixed $name
     * @return mixed
     * @throws Exception
     */
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid guestbook property');
        }
        return $this->$method();
    }
 
    /**
     * Set Options
     * @param array $options
     * @return {{ Class }}
     */
    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
    
{% for field in fields %}
    /**
     * $_{{ field.getName() }} setter
     * @param {{ field.getDataType() }} $_{{ field.getName() }}
     * @return {{ Class }}
     */
    public function setComment($_{{ field.getName() }})
    {
        $this->_{{ field.getName() }} = {% if field.getCastDataType() %}({{ field.getCastDataType() }}){% endif %} $_{{ field.getName() }};
        return $this;
    }
    
    /**
     * $_{{ field.getName() }} getter
     * @return {{ field.getDataType() }}
     */
    public function getComment()    
    {
        return $this->_{{ field.getName() }};
    }
    
{% endfor %}
     
}


