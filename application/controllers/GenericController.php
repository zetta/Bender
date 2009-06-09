<?php
/**
 * GenericModule
 * @author Juan Carlos Jarquin
 */ 



abstract class GenericController
{
    
    /**
     * Acción que se invocará en el método dispatch
     */ 
    protected $actionName = '';
    

    /**
     * Método que mandará a llamar la acción correspondiente =)
     *
     */
    public function dispatch()
    {
        $action = $this->actionName.'Action'; 
        $this->$action();
    }
    
    /**
     * Guarda el nombre de la acción que se desea ejecutar
     * @param string $actionName
     */ 
    public function setActionName($actionName)
    {
        $this->actionName = $actionName;
    }

    /**
     * método que se mandará llamar al final del dispatch 
     */
    function postDispatch(){}
    
}
