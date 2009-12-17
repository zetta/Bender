<?php




class HelpFetcher 
{
  
  private $class;
  private $controller;

  public function HelpFetcher($controllerFile)
  {
    $this->class = str_replace('.php','',$controllerFile);
    $this->controller = $this->parseCommand(substr($this->class,0,-10));
  }


  public function showHelp()
  {
    $class = new ReflectionClass($this->class);
    $out = CommandLineInterface::getInstance();
    $methods = $class->getMethods();
    foreach($methods as $method)
    {
      if($method->isPublic() && eregi('Action$',$method->name) )
      {
        $action = substr($method->name,0,-6);
        $action = $this->parseCommand($action);
        $comment = $method->getDocComment();
        $comment = eregi_replace("\\*|\\/",'',$comment);
        $out->printSection($this->controller,':'.$action);
        $out->printSection('',trim($comment),'NOTE');
      }
    }
  }



  private function parseCommand($str)
  {
    $str[0] = strtolower($str[0]);
    $func = create_function('$c', 'return "-" . strtolower($c[1]);');
    return preg_replace_callback('/([A-Z])/', $func, $str);
  }

}



