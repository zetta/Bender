<?php 
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



class Route
{
  
  /**
   * get the destination file of the specified generator
   */
  public function getLib($class)
  {
    $request = BenderRequest::getInstance();
    $lang = $request->getArg(0);
    $mode = $request->getArg(1,'default');
    $file = "application/lib/generators/{$lang}/{$mode}/libs/{$class}.php";
    if(!file_exists($file))
    {
      CommandLineInterface::getInstance()->printSection('Warning','route not found in '.$class,'WARNING','WARNING');
      return "";
    }
    require_once $file;
    $class = new ReflectionClass($class);
    $instance = $class->newInstance();
    return $instance->getFileName();
  }
  
  /**
   *
   */
  public function getObject($class, BenderTable $table)
  {
    $request = BenderRequest::getInstance();
    $lang = $request->getArg(0);
    $mode = $request->getArg(1,'default');
    $file = "application/lib/generators/{$lang}/{$mode}/generators/{$class}.php";
    if(!file_exists($file))
    {
      CommandLineInterface::getInstance()->printSection('Warning','route not found in '.$class,'WARNING','WARNING');
      return "";
    }
    require_once $file;
    $class = new ReflectionClass($class);
    $instance = $class->newInstance();
    $instance->setTable($table);
    return $instance->getFileName();
  }
  
}
