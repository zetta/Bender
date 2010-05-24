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

/**
 * Clase abstracta Behavior que implementa la interfaz de Observer
 * utilizada para las los comportamientos que se agregen a los catálogos
 * @category   lib
 * @package    lib_db
 * @subpackage lib_db_behavior
 * @copyright  {{ copyright }}
{% if showBenderSignature %}
 * @copyright  {{ benderSignature }}
 * @author     zetta 
{% endif %}
 * @version    {{ version }} SVN: $Revision$
 */
abstract class BehaviorObserver implements Observer
{
    
    /**
     * Obtiene el camelCase de una cadena de texto
     *
     * @param string $string
     * @param boolean $first
     * @param boolean $preserve 
     * @return string
     */
    protected function getCamelCase($string, $first = false, $preserve = false)
    {
        $array = explode('_', $string);
        $string = '';
        foreach ( $array as $i => $segment )
        {
            if (! $preserve)
                $segment = strtolower($segment);
            if ($i || $first)
                $segment = ucfirst($segment);
            $string .= $segment;
        }
        return $string;
    }

}

