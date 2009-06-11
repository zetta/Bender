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
     * YAML settings
     * @var array
     */
    protected $settings;
    
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
     * @param boolean $extends
     * @param array $settings
     */
    public function __construct($objectName, DbTable $table, $extends, $settings)
    {
        $this->object = $objectName;
        $this->table = $table;
        $this->extends = $extends;
        $this->settings = $settings;
        $this->addHeaderInformation();
        if ($this->settings['add_includes'])
            $this->template->showBlock('useIncludes');
        $this->lowerObject = $this->toLower($objectName);
    }
    
    protected function addHeaderInformation()
    {
        $this->template = new Template('application/templates/');
        $this->template->assign('brandName', $this->settings['header']['brandName']);
        $this->template->assign('description', $this->settings['header']['description']);
        $this->template->assign('copyright', $this->settings['header']['copyright']);
        $this->template->assign('author', $this->settings['header']['author']);
        $this->template->assign('modelFolder', $this->settings['paths']['model_location']);
        $this->template->assign('catalogFolder', $this->settings['paths']['catalogs']);
        $this->template->assign('beanFolder', $this->settings['paths']['beans']);
        $this->template->assign('factoryFolder', $this->settings['paths']['factories']);
        $this->template->assign('collectionFolder', $this->settings['paths']['collections']);
        $this->template->assign('version', ModelController::VERSION);
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
        $this->filePath = $path;
        CommandLineInterface::getInstance()->printSection('Generator', 'Saving file ' . $path, 'NOTE');
        $dir = dirname($path);
        if (! is_dir($dir))
            mkdir($dir, 0777, true);
        if($preserveChanges)
            $this->tryToPreserveChanges($path);
        
        $handle = fopen($path, "w");
        if ($this->settings['encoding'] != 'UTF-8')
            $this->fileContent = iconv("UTF-8", $this->settings['encoding'], $this->fileContent);
        
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
            require_once $path;
            $php = substr($this->fileContent, 5);
            $php = eregi_replace('class ', 'class Temp', $php);
            eval($php);
            
            $classes = array();
            preg_match_all('~^\s*(?:abstract\s+|final\s+)?(?:class|interface)\s+(\w+)~mi', $php, $classes);
            $newClass = $classes[1][0];
            $oldClass = substr($newClass, 4);
            
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
