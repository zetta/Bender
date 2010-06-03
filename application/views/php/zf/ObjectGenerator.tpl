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
     * @param 
     */
    public function setComment($text)
    {
        $this->_comment = (string) $text;
        return $this;
    }
{% endfor %}
    

 
    public function getComment()
    {
        return $this->_comment;
    }
 
    public function setEmail($email)
    {
        $this->_email = (string) $email;
        return $this;
    }
 
    public function getEmail()
    {
        return $this->_email;
    }
 
    public function setCreated($ts)
    {
        $this->_created = $ts;
        return $this;
    }
 
    public function getCreated()
    {
        return $this->_created;
    }
 
    public function setId($id)
    {
        $this->_id = (int) $id;
        return $this;
    }
 
    public function getId()
    {
        return $this->_id;
    }
 
}


