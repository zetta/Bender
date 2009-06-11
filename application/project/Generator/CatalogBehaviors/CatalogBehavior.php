<?php

/**
 * CatalogBehavior
 * @author Juan Carlos Jarquin
 */

abstract class CatalogBehavior
{
    /**
     * Tabla usada
     * @var DbTable
     */
    protected $table;
    
    /**
     * Parámetros enviados al behavior
     * @var string
     */
    protected $behaviorData;
    
    /**
     * abstract factory 
     *
     * @param string $behaviorName
     * @param array $behaviorData
     * @param DbTable $table
     * @return CatalogBehavior
     */
    static public function factory($behaviorName, $behaviorData, DbTable $table)
    {
        switch ( strtolower($behaviorName))
        {
            case 'sluggable' :
                return new SluggableCatalogBehavior($table, $behaviorData);
            break;
            default :
                throw new Exception("At this moment Bender doesn't support  [{$behaviorName}] Behavior");           
            break;
        }
    }
    
    /**
     * Obtiene la información necesaria para generar la llamada al Behavior
     */
    abstract public function generate();

}


