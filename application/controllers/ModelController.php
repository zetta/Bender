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
 * Modulo para generar los models de forma automática
 *
 */
class ModelController extends BenderController
{
    /**
     * Corre los scripts especificados para `lang` y `pattern`
     * @param string $lang
     * @param string $pattern [OPTIONAL]
     */
    public function generateAction()
    {
        if(BenderRequest::getInstance()->getFlag('isolated'))
          $this->forward('cache:clear',null,array('keep-autoloader'=>true));
        $path = "application/lib/generators/{$this->lang}/{$this->pattern}/generators/";
        $this->directoryIteration($path);
        $path = "application/lib/generators/{$this->lang}/{$this->pattern}/libs/";
        $this->directoryIteration($path,true);
        $fs = new FileSaver();
        CommandLineInterface::getInstance()->printSection('End', $fs->getCount() . ' files generated');
    }
    
    /**
     * Iterate over the directory and search generator classes
     * @param string $path the directory to iterate over
     * @param boolean $single if we must use this directory as library (run once)
     */
    private function directoryIteration($path, $single = false)
    {
      try
      {
        $dir = new DirectoryIterator($path);
      }
      catch (Exception $e)
      {
        throw new Exception("There's no `{$this->mode}` pattern in `{$this->lang}`");
      }
      foreach ($dir as $fileInfo)
      {
        if($fileInfo->isDot() || $fileInfo->isDir()) continue;
        require_once $path.'/'.$fileInfo->getFileName();
        
        $className =  str_replace('.php','',$fileInfo->getFileName());
        $class = new ReflectionClass($className);
        if(!$class->implementsInterface("CodeGenerable") && !$class->isSubclassOf('CodeGenerator'))
          continue;
        
        if(!$this->shouldRun($class))
          continue;

        if($single)
          $this->generatorRunner($class, null);
        else
          foreach(BenderDatabase::getInstance()->getTables() as $table)
          {
            if(!$this->needMoreOptions($class,$table))
              $this->generatorRunner($class, $table);
          }
      }
    }
    
    /**
     * check the options in table and compare with the options in generator
     */
    private function needMoreOptions(ReflectionClass $class, BenderTable $table)
    {
      if(!$class->hasProperty('requiredOption'))
      {
        return false;
      }
      else
      {
        $property = $class->getProperty('requiredOption');
        if(!$property->isPublic())
        {
           return false;
        }
        else
        {
           $info = $table->getInfo();
           if(!isset($info['options']))
           {
              return true;
           }
           else
           {
             $options = $info['options'];
             $value =  (string) $property->getValue($class->newInstance());
             if(!is_array($options))
             {
               return true;
             } 
             else
             {
               return !in_array($value,$options);
             }
           }
        }
      }
    }
    
    /**
     * Inspect the class and check if we must run this generator
     * @param ReflecionClass $class
     * @return boolean
     */
    private function shouldRun(ReflectionClass $class)
    {
      if(!$class->hasProperty('requiredFlags'))
      {
        return true;
      }
      else
      {
        $property = $class->getProperty('requiredFlags');
        if(!$property->isPublic())
        {
          return true;
        }
        else
        {
          $value = $property->getValue($class->newInstance());
          $request = BenderRequest::getInstance();
          if(is_array($value))
          {
            foreach( $value as $val)
            {
              if($request->getFlag($val))
                return true;
            }
          }else if(is_string($value))
          {
            return $request->getFlag($value);
          }
        }
      }
    }
    
    /**
     * Run the generator class
     * @param ReflectionClass $class
     * @param BenderTable $table
     */
    private function generatorRunner(ReflectionClass $class, BenderTable $table = null)
    {
      $object = $class->newInstance();
      $object->setTable($table);
      $object->setPath("application/views/{$this->lang}/{$this->mode}/")->setLang($this->lang)->setMode($this->mode);
      foreach($class->getMethods() as $method)
      {
          if(preg_match('/(^run$)|(^additional)/',$method->getName()))
          {
             $object->initialize($method->getName()); // la vista escoge el nombre del archivo
             $object->start();
             $fileName = $object->invoke($method);
             if($fileName)
               $object->setFileName($fileName);
             $object->end(); // el twig se ejecuta
          }
      }
    }
    
    /**
     * Genera un schema a partir de la configuración en el archivo settings
     * @param string $schema [OPTIONAL] (generated)
     */
    public function generateSchemaAction()
    {
        $schemaGenerator = new SchemaGenerator();
        $schemaGenerator->generate($this->schema);
    }
    


}







