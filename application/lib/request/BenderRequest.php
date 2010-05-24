<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * BenderRequest
 */
class BenderRequest
{

  private $controller;
  private $action;
  private $flags = array();
  private $args = array();
  private $script;
  private static $instance = null;
  
  /**
   * @return BenderRequest
   */
  public static function getInstance()
  {
    if(self::$instance === null)
      self::$instance = new self();
    return self::$instance;
  }
  
  /**
   * Private Constructor
   * @return BenderRequest
   */
  private function BenderRequest()
  {
    $this->discover( $_SERVER['argv'] );
  }
  
  /**
   * 
   */
  private function discover(array $argv)
  {
    $this->extractFlags($argv);
    $this->script = array_shift($argv);
    $firstParameter = array_shift($argv);
    if (preg_match('/:/', $firstParameter))
    {
      list( $this->controller, $this->action ) = explode(':',$firstParameter);
    }else
    {
      $this->controller = $firstParameter;
      $this->action = 'default';
    }
    $this->args = $argv;
  }
  
  /**
   * Search all the flags sended in cli
   */
  private function extractFlags(array &$argv)
  {
    foreach ($argv as $key => $arg)
    {
      $found = array();
      if(preg_match('/^(--)(.[^=]*)((=)(.*))?/',$arg,$found))
      {
        $this->setFlag($found[2],( ( isset($found[5]) ) ?  $found[5] : true ));
        unset($argv[$key]);
      }
    }
  }
  
  /**
   * flag setter
   */
  public function setFlag($name,$value = false)
  {
     if($value === 'true') $value = true;
     if($value === 'false') $value = false;
     $this->flags[$name] = $value;
  }
  
  /**
   * get the flag value
   * @return string|boolean
   */
  public function getFlag($flagname)
  {
    return isset( $this->flags[$flagname] ) ? $this->flags[$flagname] : false;
  }
  
  public function getFlags()
  {
    return $this->flags;
  }
  
  /**
   * get the controller name
   */
  public function getController()
  {
    return $this->controller;
  }
  
  /**
   * get the action name
   */
  public function getAction()
  {
    return $this->action;
  }
  
    /**
     * Return the arguments
     * @return Array
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * Return an argument by index
     * @param int $index
     * @param mixed [OPTIONAL] The default value
     */
    public function getArg($index, $defaul = null)
    {
        $value =  isset($this->args[$index]) ? $this->args[$index] : null;
        $value = (is_null($value) && $defaul != null) ? $defaul: $value;
        return $value;
    }
    
    public function setAction($action)
    {
      $this->action = $action;
    }
    
    public function setController($controller)
    {
      $this->controller = $controller;
    }
    
    /**
     * set an argument
     */
    public function setArg($index,$value)
    {
      $this->args[$index] = $value;
      return $this;
    }
  

}






