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
 * MetaDataFetcher Class
 */
class MetaDataFetcher 
{
  
  /**
   * ClassName
   */
  private $class;
  
  /**
   * ControllerName (slugged)
   */
  private $controller;

  public function MetaDataFetcher($controllerFile = NULL)
  {
  	if($controllerFile != NULL)
  	{
	    $this->class = str_replace('.php','',$controllerFile);
	    $controller = substr($this->class,0,-10);
	    $this->controller = Formatter::upperCamelCaseToSlug($controller);
  	}
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
    $out->printText($this->controller."\n",'SECTION');
    foreach($methods as $method)
    {
      
      if($method->isPublic() && preg_match('/Action$/',$method->name) )
      {
        $action = substr($method->name,0,-6);
        $action = Formatter::camelCaseToSlug($action);
        $comment = $method->getDocComment();
        $comment = preg_replace("#\\*|\\/#",'',$comment);
        $txt = $this->parseEntry($this->controller,$action,$comment);
        
        
        //if($txt['needColonSign'])
        //  $out->printText(':','WARNING');
        $a = str_pad('   '.($txt['needColonSign'] ? ':' : ''). trim($txt['actions']), 35);
        $out->printText($a,'INFO');
        $out->printText($txt['comment'],'NONE');
        $out->printText("\n");
        
        //$out->printText("\n\n");
      }
    }
  }
  
  /**
   * @return string
   */
  public function getClassName()
  {
    return $this->class;
  }
  
  /**
   * obtiene los argumentos de un metodo desde su phpdoc
   * @param ReflectionMethod $method
   * @return array
   */
  public function getArguments(ReflectionMethod $method)
  {
     $args = array();
  	 $doc = $method->getDocComment();
  	 $i = 0;
     foreach (explode("\n",$method->getDocComment()) as $line)
     {
     	$isOptional = false;
     	$default = null;
        if(strpos($line,'@param'))
        {
            $a = array();
		    $arg = preg_match('/\$(\w*)(\s*)(\[optional\])?(\s*)(\((\w*)\))?/i',$line,$a);
		    if(isset($a[3]) && $a[3])
		    	$isOptional = true;
		    if(isset($a[6]) && $a[6])
		        $default = $a[6];     	
		    
		    $args[] = array(
		      'name' => $a[1],
		      'isOptional' => $isOptional,
		      'default' => $default,
		      'index' => $i
		    );
		    $i++;
        }
     }
     return $args;
  }
  
  /**
   * regresa la informacion del metodo
   * @param string $controller
   * @param string $action
   * @param string $comment
   * @return array 
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
      'actions'    => implode(' ',$fullAction),
      'needColonSign' => $needColonSign,
      'comment'   => implode("\n  ",$fullComment)
    );
  }

}



