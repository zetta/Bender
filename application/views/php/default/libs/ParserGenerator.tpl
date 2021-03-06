<?php 
/**
 * {{ brandName }}
 *
 * {{ description }}
 *
 * @category   lib
 * @package    lib_utils
 * @copyright  {{ copyright }}
 * @author     {{ author }}
 * @version    {{ version }} SVN: $Id$
 */

/**
 * Parser Class
 * @category   lib
 * @package    lib_utils
 * @copyright  {{ copyright }}
{% if showBenderSignature %}
 * @copyright  {{ benderSignature }}
 * @author     chentepixtol 
{% endif %}
 * @version    {{ version }} SVN: $Revision$
 */
class Parser
{

    /**
     * @var string
     */
    private $class;

    /**
     * @var mixed
     */
    private $bean;

    /**
     * @var array
     */
    private $properties = array();

    /**
     * @var string
     */
    private $dateFormat = 'yyyy-MM-dd';
    
    /**
     * @var string
     */
    private $timestampFormat = 'yyyy-MM-dd HH:mm:ss';

    /**
     * @var Reflection
     */
    private $reflectionClass = null;

    /**
     * Define el formato de la fecha
     * @param string $dateFormat
     * @return void
     */
    public function setDateFormat($dateFormat)
    {
        $this->dateFormat = $dateFormat;
    }
    
    /**
     * Define el formato de la fecha
     * @param string $dateFormat
     * @return void
     */
    public function setTimestampFormat($timestampFormat)
    {
        $this->timestampFormat = $timestampFormat;
    }

    /**
     * Constructor
     * @param string $class
     * @return void
     */
    public function __construct($class)
    {
        $this->class = $class;
        $this->introspect();
    }

    /**
     * Obtiene las propiedades que tiene un getter
     * @return void
     */
    private function introspect()
    {
        $this->reflectionClass = new ReflectionClass($this->class);
        foreach ($this->reflectionClass->getMethods() as $method)
        {
            $name = $method->getName();
            if(preg_match('/^get*/', $name))
            {
                $this->properties[] = self::lcfirst(substr($name, 3));
            }
        }
    }

    /**
     * Cambia de Bean
     * @param mixed $bean
     * @return void
     */
    public function changeBean($bean)
    {
        if( $this->reflectionClass->getName() != get_class($bean) )
        {
            throw new Exception('The object is not valid');
        }

        $this->bean = $bean;
    }

    /**
     * Invoca un metodo del bean
     * @param string $method
     * @return mixed
     */
    private function invoke($method)
    {
        $value = call_user_func(array($this->bean, $method));
        if(is_object($value))
        {
            if($value instanceof Zend_Date)
            {
                if($value->get('HH:mm:ss') != '00:00:00')
                {
                    $value = $value->get($this->timestampFormat);
                }
                else
                {
                    $value = $value->get($this->dateFormat);
                }
            }
            
        }
        return $value;
    }
    
    /**
     * Convierte a un array
     * @return array
     */
    public function toArray()
    {
        $array = array();
        foreach ($this->properties as $property)
        {
            $method = 'get' . ucfirst($property);
            $array[$property] = $this->invoke($method);
        }
        return $array;
    }

    /**
     * Convierte en Json
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * Convierte la primera en minuscula
     * @param string $str
     * @return string
     */
    public static function lcfirst( $str )
    {
        return (string)(strtolower(substr($str,0,1)).substr($str,1));
    }

    /**
     * Obtiene un array de indice valor
     * @param string $ckey
     * @param string $cvalue
     * @return array
     */
    public function toKeyValueArray($ckey, $cvalue)
    {
        $methodKey = self::getMethodGetter($ckey);
        $methodValue = self::getMethodGetter($cvalue);
        $key = $this->invoke($methodKey);
        $value = $this->invoke($methodValue);
        return array($key => $value);
    }
    
    /**
     * Obtiene el getter a partir de una constante
     * @param string $const
     * @return string
     */
    public static function getMethodGetter($const)
    {
        $key = explode('.', $const);
        $method = 'get'. self::toCamelCase($key[1]);
        return $method;
    }

    /**
     * Convierte una cadena a camel case
     * @param string $str
     * @param boolean $ucfirst
     * @return string
     */
    public static function toCamelCase($str, $ucfirst = false)
    {
        if($ucfirst) ucfirst($str);
        $func = create_function('$c', 'return strtoupper($c[1]);');
        return preg_replace_callback('/_([a-z])/', $func, $str);
    }

}
