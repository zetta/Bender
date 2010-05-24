<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class BenderView
{
  private static $instance = null;
  private $loader;
  private $environment;
  private $template;
  protected $vars = array();
  protected $path = '';
  protected $folders;
  
  /**
   * singleton
   */
  public static function getInstance($objectName,$path)
  {
     if(!isset(self::$instance))
       self::$instance = new BenderView($objectName,$path);
     else
     {
       self::$instance->init($objectName);
     } 
     return self::$instance;
  }
  
  private function BenderView($objectName,$path)
  {
    if(!isset($this->folders) || $this->path != $path)
    {
      $folders = array($path);
      foreach (new DirectoryIterator($path) as $fileInfo)
      {
         if($fileInfo->isDir() && !$fileInfo->isDot())
         $folders[] = $fileInfo->getPathName();
      }
      $this->folders = $folders;
      $this->path = $path;
    }
    $this->init($objectName);
  }

  /**
   *
   */
  private function init($objectName)
  {
    $this->loader = new Twig_Loader_Filesystem($this->folders);
    $this->environment = new Twig_Environment($this->loader, array(
      'cache' => false,
      'trim_blocks' => true
    ));
    $this->template = $this->environment->loadTemplate($objectName.'.tpl');
  }


  /**
   * magic setter =) 
   */
  public function __set($name, $var)
  {
    $this->vars[$name]  = $var;
  }
  
  
  public function render()
  {
     return $this->template->render($this->vars);
  }


  public function displayRender()
  {
    echo $this->render();
  }

}



