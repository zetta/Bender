<?php
/**
 * {{ brandName }}
 *
 * {{ description }}
 *
 * @category   lib
 * @package    lib_db
 * @copyright  {{ copyright }}
 * @author    zetta
 * @version    {{ version }} SVN: $Id$
 */
{% if flags["add-includes"] %}

/**
 * Requires
 */
require_once "{{ route.getLib('CatalogInterfaceGenerator') }}";
require_once "{{ route.getLib('CriteriaGenerator') }}";
require_once "{{ route.getLib('DBAOGenerator') }}";

{% endif %}
/**
 * Clase abstracta que representa un catalogo general
 *
 * @category   lib
 * @package    lib_db
 * @copyright  {{ copyright }}
{% if showBenderSignature %}
 * @copyright  {{ benderSignature }}
 * @author     zetta 
{% endif %}
 * @version    {{ version }} SVN: $Revision$
 */
abstract class Catalog implements CatalogInterface
{
{% if flags["use-behaviors"] %}
    const EVENT_CREATE = 1;
    const EVENT_RETRIEVE = 2;
    const EVENT_UPDATE = 3;
    const EVENT_DELETE = 4;
{% endif %}

    /**
     * Propiedad que representa el objeto db.
     * @var Zend_Db_Adapter_Abstract Objeto Zend_Db_Adapter_Abstract
     */
    protected $db;
    
    /**
     * Can't Clone Singleton Class 
     */
    private function __clone(){}

    /**
     * Date part usado para los campos tipo Date o DateTime
     * @var string $datePart
     */
    protected $datePart;
{% if flags["use-behaviors"] %}

    /**
     * Observers array
     * @var array
     */
    protected $observers;
{% endif %}

    /**
     * Constructor de la clase catalogo
     */
    public function Catalog()
    {
        $this->datePart = "YYYY-MM-dd hh:mm:ss";
        $this->db = DBAO::Database();
{% if flags["use-behaviors"] %}
        $this->observers = array();
{% endif %}
    }
    
{% if flags["use-behaviors"] %}

    /**
     * Agrega un observer al arreglo
     * @param BehaviorObserver $behaviorObserver
     */
    public function addObserver(BehaviorObserver $behaviorObserver)
    {
        $className = get_class($behaviorObserver);
        if (! isset($this->observers[$className]))
            $this->observers[$className] = $behaviorObserver;
    }
    
    /**
     * Envia una notificacion a los observers que están escuchando 
     * @param Object $object 
     * @param int $event 
     */
    public function notifyObservers($object, $event)
    {
        foreach ($this->observers as $observer)
        {
            $observer->fireEvent($this, $object, $event);
        }
    }
{% endif %}
  
    /**
     * array_filter
     */
    public static function notNull($value)
    {
       return !is_null($value);
    }
    
}

