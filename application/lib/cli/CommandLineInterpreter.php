<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
     * Request 
     * @var BenderRequest
     */
    private $arguments = null;
    
    /**
     * Constructor de la clase
     */
    public function CommandLineInterpreter()
    {
        $this->request =  BenderRequest::getInstance();
        $this->controller = $this->parseController( $this->request->getController() );
        $this->action = $this->parseAction( $this->request->getAction() );
    }
    
    /**
     * Inicia el interprete y realiza la petición
     */
    public function run()
    {
        try
        {
          $controller = ucfirst($this->controller);
          if(!$controller)
              throw new BadMethodCallException("No controller to run");
          if (! file_exists("application/controllers/{$controller}Controller.php"))
              throw new BadMethodCallException("There's no `{$this->controller}` controller");
          
          $controllerName = "{$controller}Controller";
          $reflectedClass = new ReflectionClass($controllerName);
          if(!$reflectedClass->isSubclassOf('BenderController'))
              throw new CLIException("Invalid method");
          if (! $reflectedClass->hasMethod($this->action . 'Action'))
              throw new CLIException("There's no `{$this->action}` action in `{$this->controller}` controller");
          $method =$reflectedClass->getMethod($this->action.'Action');
          if(!$method->isPublic())
              throw new CLIException("There's no `{$this->action}` action in `{$this->controller}` controller");
        } catch(Exception $e)
        {
          $this->showHelp($e->getMessage());
          return;
        }
        $controller = $reflectedClass->newInstance();
        $controller->preDispatch();
        $controller->dispatch();
        $controller->postDispatch();
    }
    
    /**
     * show the bender help
     */
    public function showHelp($message = null)
    {
        if($message)
          CommandLineInterface::getInstance()->printMessage($message."\n",'ERROR');
        $this->request->setController('help');
        $this->request->setAction('default');
        $this->request->setArg(0,null);
        $help = new CommandLineInterpreter();
        $help->run();
    }
    
    
    /**
     * Devuelve el nombre de un módulo de acuerdo a la sintaxis necesaria
     * @param string $controllerName
     */
    private function parseController($controllerName)
    {
        return Formatter::slugtoCamelCase($controllerName);
    }
    
    /**
     * Devuelve el nombre de una acción de acuerdo a la sintaxis necesaria
     * @param string $moduleName
     */
    private function parseAction($actionName)
    {
        return Formatter::slugtoCamelCase($actionName);
    }

}



















