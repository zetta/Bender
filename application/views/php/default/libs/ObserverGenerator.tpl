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


/**
 * Interfaz para los objetos observers
 * @category   lib
 * @package    lib_db
 * @subpackage lib_db_behaviors
 * @copyright  {{ copyright }}
{% if showBenderSignature %}
 * @copyright  {{ benderSignature }}
 * @author     zetta 
{% endif %}
 * @version    {{ version }} SVN: $Revision$
 */
interface Observer
{
    
    
    /**
     * Dispara un evento 
     * @param Catalog $catalog El catálogo que disparó =O
     * @param mixed $bean EL BEAN
     * @param int $event
     */ 
    public function fireEvent(Catalog $catalog, $bean, $event);
    
}



