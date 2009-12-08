<?php
/**
 * CatalogModule
 * @author Juan Carlos Jarquin
 */

abstract class ModelGenerator
{
    /**
     * Nombre Del objeto (bean)
     * @var string
     */
    protected $object;
    
    /**
     * Tabla con la que se está trabajando
     * @var DbTable $table
     */
    protected $table;
    
    /**
     * Extiende de otra tabla
     * @var boolean $extends
     */
    protected $extends;
    
    /**
     * Settings 
     * @var BenderSettings
     */
    protected $benderSettings = null;
    
    /**
     * Template
     *
     * @var Template
     */
    protected $template;
    
    /**
     * Nombre del objeto como variable de php
     *
     * @var string
     */
    protected $lowerObject;
    
    /**
     * El path del archivo destino
     *
     * @var string
     */
    protected $filePath;
    
    /**
     * Constructor de la clase
     *
     * @param string $objectName
     * @param DbTable $table
     * @param array $settings
     */
    public function __construct($objectName, DbTable $table)
    {
    	  $this->benderSettings = BenderSettings::getInstance();
        $this->object = $objectName;
        $this->table = $table;
        $this->extends = $table->getExtends();
        $this->lowerObject = $this->toLower($objectName);
        $this->addHeaderInformation();
    }
    
    /**
     * Agrega las variables comunes a los headers de los archivos 
     */
    protected function addHeaderInformation()
    {
        $this->template = new Template('application/templates/');
        $this->template->assign('libLocation',$this->benderSettings->getLibLocation());
        $this->template->assign('dbLocation',$this->benderSettings->getDbLocation());
        $this->template->assign('brandName',  $this->benderSettings->getBrandName());
        $this->template->assign('description',$this->benderSettings->getDescription());
        $this->template->assign('copyright', $this->benderSettings->getCopyRight());
        $this->template->assign('author', $this->benderSettings->getAuthor());
        $this->template->assign('modelFolder', $this->benderSettings->getModelLocation());
        $this->template->assign('catalogFolder',$this->benderSettings->getCatalogLocation());
        $this->template->assign('beanFolder', $this->benderSettings->getBeanLocation());
        $this->template->assign('factoryFolder', $this->benderSettings->getFactoryLocation());
        $this->template->assign('collectionFolder', $this->benderSettings->getCollectionLocation());
        $this->template->assign('exceptionFolder', $this->benderSettings->getExceptionLocation());
        $this->template->assign('benderSignature', $this->benderSettings->getBenderSignature());
        if($this->benderSettings->getAddBenderSignature())
            $this->template->showBlock('benderSignature');
        $this->template->assign('version', Bender::VERSION);
        
        $this->template->assign('Bean', $this->object);
        $this->template->assign('Controller',$this->object.'Controller');
        $this->template->assign('Catalog',$this->object.'Catalog');
        $this->template->assign('Factory',$this->object.'Factory');
        $this->template->assign('Collection',$this->object.'Collection');
        $this->template->assign('Exception',$this->object.'Exception');
        
        $this->template->assign('bean', $this->lowerObject);
        $this->template->assign('controller',$this->lowerObject.'Controller');
        $this->template->assign('catalog',$this->lowerObject.'Catalog');
        $this->template->assign('factory',$this->lowerObject.'Factory');
        $this->template->assign('collection',$this->lowerObject.'Collection');
        $this->template->assign('exception',$this->lowerObject.'Exception');
        
        if($this->table instanceof DbTable )
          $this->template->assign('tableName', $this->table->getTable());
        
        if ($this->benderSettings->getAddIncludes())
            $this->template->showBlock('useIncludes');
    }
    
    /**
     * Contenido del archivo que se generó
     */
    protected $fileContent = "";
    
    /**
     * Escribe el archivo en la dirección especificada
     * @param string $path
     * @param boolean $preserveChanges
     */
    public function saveFile($path, $preserveChanges = true)
    {
        $path = str_replace('//','/',$path);
        $this->filePath = $path;
        CommandLineInterface::getInstance()->printSection('Generator', 'Saving file ' . $path, 'NOTE');
        $dir = dirname($path);
        if (! is_dir($dir))
            mkdir($dir, 0777, true);
        if($preserveChanges)
            $this->tryToPreserveChanges($path);
        
        $handle = fopen($path, "w");
        if ($this->benderSettings->getEncoding() != 'UTF-8')
            $this->fileContent = iconv("UTF-8", $this->benderSettings->getEncoding(), $this->fileContent);
        
        fwrite($handle, $this->fileContent);
        fclose($handle);
    }
    
    /**
     * Modifica el nombre del objeto para poder usarlo como variable
     *
     * @param string $string
     * @return string
     */
    private function toLower($string)
    {
        return (strtolower($string[0])) . (substr($string, 1));
    }
    
    /**
     * @return string
     */
    public function getLowerObject()
    {
        return $this->lowerObject;
    }
    
    /**
     * Esta función intenta conservar los cambios que se hayan realizado en el documento
     * @param string $path
     */
    private function tryToPreserveChanges($path)
    {
        if (file_exists($path))
        {
            //CommandLineInterface::getInstance()->printSection('Generator','File exists!','NOTE');
            $content = substr(file_get_contents($path),5);
            $content = eregi_replace("require|include","#",$content);
                        
            $php = substr($this->fileContent, 5);
            $php = eregi_replace('class ', 'class Temp', $php);
            
            $classes = array();
            preg_match_all('~^\s*(?:abstract\s+|final\s+)?(?:class|interface)\s+(\w+)~mi', $php, $classes);
            $newClass = $classes[1][0];
            $oldClass = substr($newClass, 4);
            
            
            if(!class_exists($oldClass))
                eval($content);
            eval($php);
            
            
            $newReflection = new ReflectionClass($newClass);
            $oldReflection = new ReflectionClass($oldClass);
            $oldConstants = $oldReflection->getConstants();
            $newConstants = $newReflection->getConstants();
            
            $methods = $oldReflection->getMethods();
            $propeties = $oldReflection->getProperties();
            
            $manualMethods = array();
            $manualProperties = array();
            $manualConstants = array_diff($oldConstants, $newConstants);
            
            foreach ( $methods as $method )
            {
                if (! $newReflection->hasMethod($method->getName()))
                {
                    $manualMethods[] = $method;
                }
            }
            foreach ( $propeties as $property )
            {
                if (! $newReflection->hasProperty($property->getName()))
                {
                    $manualProperties[] = $property;
                }
            }
            if ($manualMethods | $manualConstants | $manualProperties)
            {
                $strManualMethods = '';
                foreach ( $manualMethods as $method )
                {
                    $strManualMethods .= '    ' . $method->getDocComment() . "\n";
                    $strManualMethods .= $this->getBlockCode($path, $method->getStartLine(), $method->getEndLine()) . "\n";
                }
                $strManualConstants = '';
                foreach ( $manualConstants as $constantName => $constantValue )
                {
                    $constantValue = $this->formatValue($constantValue);
                    $strManualConstants .= "    const {$constantName} = $constantValue;\n";
                }
                $strManualProperties = "\n";
                foreach ( $manualProperties as $property )
                {
                    $object = new $oldClass();
                    $strManualProperties .= $this->getPropertyDeclaration($property, $object);
                }
                $this->fileContent = eregi_replace("\n}\n", $strManualMethods . "\n}\n", $this->fileContent);
                $this->fileContent = eregi_replace("\n}\n", $strManualConstants . "\n}\n", $this->fileContent);
                $this->fileContent = eregi_replace("\n}\n", $strManualProperties . "\n}\n", $this->fileContent);
            }
        }
    }
    
    /**
     * Formatea un valor para ser escrito en el php
     *
     * @param mixed $value
     * @return string
     */
    private function formatValue($value)
    {
        if (is_bool($value))
        {
            $value = $value ? 'true' : 'false';
        } else if (is_string($value))
        {
            $value = "\"{$value}\"";
        } elseif (is_array($value))
        {
            $value = $this->getArrayString($value);
        }
        return $value;
    }
    
    /**
     * Intenta obtener el valor de las variables privadas o protegidas
     * @param string $filePath
     * @param string $propertyName
     * @return string
     */
    private function fetchNonPublicProperty($filePath, $propertyName)
    {
        if (! file_exists($filePath))
            throw new Exception('No se pudo abrir el archivo ' . $filePath);
        
        $content = file_get_contents($filePath);
        
        $matches = array();
        preg_match("/\\\${$propertyName}(.*)?;/", $content, $matches);
        if (! trim($matches[1]))
            return ';';
        else
            return $matches[1] . ';';
    }
    
    /**
     * Obtiene la representación de un arreglo
     *
     * @param array $array
     * @return string
     */
    private function getArrayString($array)
    {
        $temporalArray = array();
        foreach ( $array as $index => $value )
        {
            $index = $this->formatValue($index);
            $value = $this->formatValue($value);
            $temporalArray[] = "{$index} => {$value}";
        }
        return "array(" . implode(",", $temporalArray) . ")";
    }
    
    /**
     * Obtiene el bloque de la declaración de la propiedad especificada
     *
     * @param ReflectionProperty $property
     * @param stdClass $object
     * @return string
     */
    private function getPropertyDeclaration(ReflectionProperty $property, $object)
    {
        $visibility = $property->isProtected() ? 'protected' : ($property->isPrivate() ? 'private' : 'public');
        $isStatitc = $property->isStatic() ? ' static' : '';
        $hasValue = false;
        $value = '';
        try
        {
            $value = $this->formatValue($property->getValue($object));
            $semicolon = ';';
            if ($value != NULL)
                $hasValue = true;
        } catch ( Exception $e )
        {
            
            CommandLineInterface::getInstance()->printSection('Warning', $e->getMessage(), 'WARNING', 'WARNING');
            $value = $this->fetchNonPublicProperty($this->filePath, $property->getName());
            $semicolon = '';
        }
        
        $asSign = $hasValue ? ' = ' : '';
        $docComment = $property->getDocComment() ? "\n    " . $property->getDocComment() . "\n" : '';
        $declaration = $docComment . "    {$visibility}{$isStatitc} \${$property->getName()}{$asSign}{$value}{$semicolon}\n";
        return $declaration;
    }
    
    /**
     * Obtiene el bloque de codigo dentro de un archivo
     *
     * @param string $filePath
     * @param int $startLine
     * @param int $endLine
     */
    private function getBlockCode($filePath, $startLine, $endLine)
    {
        if (! file_exists($filePath))
            throw new Exception('No se pudo abrir el archivo ' . $filePath);
        
        $blockCode = '';
        $entireCode = file($filePath);
        
        for($i = $startLine - 1; $i < $endLine; $i ++)
        {
            $blockCode .= $entireCode[$i];
        }
        
        return $blockCode;
    }

}  
