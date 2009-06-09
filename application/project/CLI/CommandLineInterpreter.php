<?php
/**
 * Command Line Interpreter
 * @author Juan Carlos Jarquin
 */

class CommandLineInterpreter
{
    
    /**
     * Archivo que fue llamado en el intérprete
     * @var string
     */
    private $executedFile = "";
    
    /**
     * Controlador que se ejecutará
     * @var string
     */
    private $controller = "";
    
    /**
     * Acción que se ejecutará
     */
    private $action = "";
    
    /**
     * Options 
     * @var array
     */
    private $options = array();
    
    /**
     * Flags 
     * @var array
     */
    private $flags = array();
    
    /**
     * Arguments 
     * @var array
     */
    private $arguments = array();
    
    /**
     * Constructor de la clase
     */
    public function CommandLineInterpreter()
    {
        $arguments = $_SERVER['argv'];
        $this->executedFile = array_shift($arguments);
        $firstParameter = array_shift($arguments);
        if (! eregi(":", $firstParameter))
            throw new Exception("El primer parámetro debe estar en formato [controller]:[action]");
        $firstParameter = explode(":", $firstParameter);
        $this->controller = $this->parseController($firstParameter[0]);
        $this->action = $this->parseAction($firstParameter[1]);
    }
    
    /**
     * Inicia el interprete y realiza la petición
     */
    public function run()
    {
        $controller = ucfirst($this->controller);
        if (! file_exists("application/controllers/{$controller}Controller.php"))
            throw new Exception("El controllador {$this->controller} no existe");
        
        require_once "application/controllers/{$controller}Controller.php";
        $controllerName = "{$controller}Controller";
        $reflectedModule = new ReflectionClass($controllerName);
        if (! $reflectedModule->hasMethod($this->action . 'Action'))
            throw new Exception("La acción {$this->action} no existe");
        
        require_once 'application/project/Template/Template.php';
        $controller = new $controllerName();
        $controller->setActionName($this->action);
        $controller->dispatch();
        $controller->postDispatch();
    }
    
    /**
     * Devuelve el nombre de un módulo de acuerdo a la sintaxis necesaria
     * @param string $controllerName
     */
    private function parseController($controllerName)
    {
        return StringFormatter::toCamelCase($controllerName);
    }
    
    /**
     * Devuelve el nombre de una acción de acuerdo a la sintaxis necesaria
     * @param string $moduleName
     */
    private function parseAction($actionName)
    {
        return StringFormatter::toCamelCase($actionName);
    }

}



















