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
 * HelpFetcher Class
 */
class HelpFetcher 
{
  
  /**
   * ClassName
   */
  private $class;
  
  /**
   * ControllerName (slugged)
   */
  private $controller;

  public function HelpFetcher($controllerFile)
  {
    $this->class = str_replace('.php','',$controllerFile);
    $controller = substr($this->class,0,-10);
    $this->controller = Formatter::upperCamelCaseToSlug($controller);
  }

  /**
   * Muestra la ayuda de todos los metodos de un controller
   */
  public function showHelp()
  {
    try
    {
        $class = new ReflectionClass($this->class);
    } catch (Exception $e)
    {
        return;
    }
    $out = CommandLineInterface::getInstance();
    $methods = $class->getMethods();
    foreach($methods as $method)
    {
      if($method->isPublic() && preg_match('/Action$/',$method->name) )
      {
        $action = substr($method->name,0,-6);
        $action = Formatter::camelCaseToSlug($action);
        $comment = $method->getDocComment();
        $comment = preg_replace("#\\*|\\/#",'',$comment);
        $txt = $this->parseEntry($this->controller,$action,$comment);
        $out->printMessage($txt['controller'],'INFO');
        if($txt['needColonSign'])
          $out->printText(':','WARNING');
        $out->printText($txt['action'],'INFO');
        $out->printText("\n");
        $out->printMessage($txt['comment'],'NOTE');
        $out->printText("\n\n");
      }
    }
  }
  
  public function getClassName()
  {
    return $this->class;
  }
  
  /**
   *
   */
  private function parseEntry($controller,$action,$comment)
  {
    $comment = trim($comment);
    $args = array();
    $fullComment = array();
    $needColonSign = ($action == 'default' ? false : true);
    $fullAction = array(($action == 'default' ? '' : $action));
    foreach (explode("\n",$comment) as $line)
    {
      if(strpos($line,'@param'))
       $args[] = trim($line);
      else
      $fullComment[] = '  '.trim($line);
    }
    foreach($args as $arg)
    {
      $a = array();
      $arg = preg_match('/\$(\w*)(.*)(optional)?/i',$arg,$a);
      if(isset($a[2]))
      $arg = (stripos($a[2],'optional')) ? '[' . $a[1] . ']' : $a[1];
      $fullAction[] = trim($arg);
    }
    return array(
      'controller' => $controller ,
      'action'    => implode(' ',$fullAction),
      'needColonSign' => $needColonSign,
      'comment'   => implode("\n  ",$fullComment)
    );
  }

}



