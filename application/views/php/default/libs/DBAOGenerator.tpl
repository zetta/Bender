<?php
/**
 * {{ brandName }}
 *
 * {{ description }}
 *
 * @category   lib
 * @package    lib_db
 * @copyright  {{ copyright }}
 * @author     {{ author }}
 * @version    {{ version }} SVN: $Id$
 */
{% if useIncludes %}

/**
 * Requires
 */
require_once 'Zend/Db.php';
{% endif %}

/**
 * Clase que representa la abstraccion de nuestro objeto Zend_Db
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
abstract class DBAO
{

    /**
     * @var Zend_Db_Adapter_Abstract
     */
    public static $config = null;

    /**
     * Instancia singleton
     */
    static protected $instance  = null;
    
    /**
     * Regresa el objeto Zend_Db para los catalogos
     * @return Zend_Db_Adapter_Abstract Objeto Zend_Db_Adapter_Abstract para manejo de la Base de datos
     * @throws Exception No se ha configurado el parametro estático de la base de datos
     */
    public static function Database()
    {
        if(DBAO::$config === null)
        {
            throw new Exception("No se ha configurado el parametro estático de la base de datos");
        }
        return DBAO::getInstance();
    }

    
    /**
     * Método para obtener la instancia del catálogo, solo el método DBAO::Database() puede acceder a el
     * @return Zend_Db_Adapter_Abstract
     */
    private static function getInstance()
    {
        if (!isset(self::$instance))
        {
          self::$instance = Zend_Db::factory(DBAO::$config);
        }
        return self::$instance;
    }
}

