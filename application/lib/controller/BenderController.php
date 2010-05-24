<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

abstract class BenderController
{

    /**
     * Action to be dispatched
     */
    protected $actionName = '';
    protected $request = null;
    
    public function BenderController()
    {
      $this->request = BenderRequest::getInstance();
    }


    /**
     * The arguments
     */
    private $args = array();

    /**
     * The dispatch method [FINAL]
     */
    final public function dispatch()
    {
        $action = Formatter::SlugToCamelCase($this->request->getAction());
        $method = new ReflectionMethod($this, $action . 'Action');
        if($this->request->getFlag('debug'))
          CommandLineInterface::getInstance()->printSection('CLI', 'invoking '.$this->request->getController().':'.$this->request->getAction(),'INFO');
        $method->invoke($this);
    }

    
    /**
     * forward to another controller:action
     * @param string $action
     * @param [OPTIONAL] array $args
     * @param [OPTIONAL] array $flags
     */ 
    final protected function forward($action,array $args = null, array $flags = null)
    {        
        if (preg_match('/:/', $action))
        {
          list( $c, $action ) = explode(':',$action);
          $this->request->setController($c);
        }
        $this->request->setAction($action);
        
        if($args)
         foreach ( $args as $index => $arg )
           $this->request->setArg($index,$arg);
        
        if($flags)
         foreach ( $flags as $index => $flag )
           $this->request->setFlag($index,$flag);
        
        $cli = new CommandLineInterpreter();
        $cli->run();
    }

    /**
     * postDispatch Method
     */
    public function postDispatch()
    {
    }

    /**
     * preDispatch Method
     */
    public function preDispatch()
    {
    }

    /**
     * Set the arguments
     * @param Array
     */
    public function setArgs($args)
    {
        $this->args = $args;
    }



}
