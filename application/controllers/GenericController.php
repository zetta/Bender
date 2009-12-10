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
        $method = new ReflectionMethod($this, $this->actionName . 'Action');
        $method->invoke($this);
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
     * está aqui por si las moscas =) 
     */
    public function postDispatch()
    {
    }
    
    /**
     * método que se mandará llamar antes del dispatch
     * está aqui por si las moscas =) 
     */
    public function preDispatch()
    {
    }
    
    /**
     * Obtiene el arreglo de los ajustes
     * y se conecta a la base de datos
     */
    public function prepare()
    {
        $benderSettings = BenderSettings::getInstance();
        $settingsFile = 'application/data/settings.yml';
        if (! file_exists($settingsFile))
            throw new ErrorException("El archivo de ajustes [{$settingsFile}] no se encuentra");
        $yaml = Spyc::YAMLLoad($settingsFile);
        try
        {
            $benderSettings->setUp($yaml['bender']);
        } catch ( Exception $e )
        {
            throw new Exception("Error {$e->getCode()} : El archivo de configuración parece no ser válido");
        }
        
        $dataBase = Database::getInstance();
        $dataBase->configure($benderSettings->getServer(), $benderSettings->getUsername(), $benderSettings->getPassword(), $benderSettings->getDbName());
        $dataBase->connect();
    }

}
