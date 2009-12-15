<?php
/**
 * DbTable
 * @author Juan Carlos Jarquin
 */

/**
 * Clase que representa una tabla en la base de datos, para conseguir los tipos de dato etc.
 *
 */
class DbTable
{
    
    /**
     * Nombre de la tabla a la que se va a consultar
     * @var string
     */
    private $table = '';
    
    /**
     * Nombre de la base de datos a la que pertenece esta tabla
     *
     * @var string
     */
    private $databaseName = '';
    
    /**
     * Database
     *
     * @var Database $database
     */
    private $database = null;
    
    /**
     * Array of Tables
     * @var FieldCollection
     */
    private $fields = null;
    
    /**
     * PrimaryField
     *
     * @var DbField
     */
    private $primaryField = null;
    
    /**
     * Extended Table
     * @var DbTable
     */
    private $extendedTable = null;
    
    /**
     * Extended Table name
     * @var string
     */
    private $extendedTableName = '';
    
    /**
     * Extends
     * @var boolean
     */
    private $extends = false;
    
    /**
     * Nombre del objeto que representan
     */
    private $object = '';
    
    /**
     * Las llaves foraneas
     * @var FieldCollection
     */
    private $foreignKeys = null;
    
    /**
     * Comportamientos que debe tener el objeto
     * @var array
     */
    private $behaviors = array();
    
    /**
     * Constructor
     *
     * @param string $table
     * @param string $database
     * @param array $modelInfo
     * @param string $object;
     * @return DbTable
     */
    public function DbTable($table, $database, $modelInfo)
    {
        $this->table = $table;
        $this->databaseName = $database;
        $this->database = Database::getInstance();
        $this->fields = new FieldCollection();
        $this->foreignKeys = new FieldCollection();
        $this->behaviors = isset($modelInfo['behaviors']) ? $modelInfo['behaviors'] : array();
        $this->extends = $modelInfo['extends'];
        $this->extendedTableName = isset($modelInfo['extends']) ? $modelInfo['extends'] : '';
        if (! isset($modelInfo['object']))
            throw new Exception('Ha ocurrido un error no se pudo encontrar la información del objeto ');
        $this->object = $modelInfo['object'];
    }
    
    /**
     * Genera toda la información acerca de la tabla
     */
    public function initialize()
    {
        $benderSettings = BenderSettings::getInstance();
        CommandLineInterface::getInstance()->printSection('DbTable', "Fetching table info '{$this->table}'", 'COMMENT');
        $fieldResource = $this->database->query("SHOW FULL COLUMNS FROM {$this->table} WHERE TRUE");
        while (($column = $this->database->fetch_array($fieldResource)))
        { 
            //aqui ponemos todos los defaults
            $type = $this->parseTypeAndLength( $column['Type'] );            
            $field = new DbField($column['Field']);
            $field->setTable($this->table);
            $field->setSetterName('set' . DbTable::getCamelCase($field->getName(), true));
            $field->setGetterName('get' . DbTable::getCamelCase($field->getName(), true));
            $field->setPhpName(DbTable::getCamelCase($field->getName()));
            $field->setUpperCaseName(ucfirst( $field->getPhpName()));
            $field->setConstantName(strtoupper($field->getName()));
            $field->setCompleteGetterName( $this->parseCompleteGetterName($field) );
            $field->setSimpleName($field->getName());
            
            $field->setMaxlength($type['max']);
            $field->setBaseDataType($type['type']);
            $field->setDataType(DbTable::parseDataType($field->getBaseDataType()));
            $field->setCastDataType(DbTable::parseCastDataType($field->getDataType()));
            
            $field->setType( $field->getDataType() );
            $field->setComment( $this->parseComment( $column->Comment ));
            
            $field->setIsPrimaryKey(($column['Key'] == 'PRI' ? true : false));
            $field->setIsUnique( $column['Key'] == 'UNI' ? true : false );
            
            $field->setDefaultValue($column['Default']);
            if ($benderSettings->useConstants())
                $field->setCatalogAccesor($this->getObject().'::'.$field->getConstantName());
            else
                $field->setCatalogAccesor($this->getObject()."::TABLENAME.'.{$field->getName()}'");
           
                
            // ahora aqui debemos poner todo lo que el usuario especificó en el schema    
            $info = BenderSettings::getInstance()->getModel($this->object);
            if( isset($info['fields'][$field->getName()]) )
            {
              $custom = $info['fields'][$field->getName()];
              if(isset($custom['type']))
                $field->setType($custom['type']);
              if(isset($custom['required']))
                $field->setRequired( $custom['required'] ? true : false );
              if(isset($custom['max']))
                $field->setMaxlength($custom['max']);
              if(isset($custom['min']))
                $field->setMinlength($custom['min']);
              if(isset($custom['unique']))
                $field->setIsUnique($custom['unique'] ? true : false);
              
            }
            
            $this->fields->offsetSet($field->getName(), $field);
            $this->fields->rewind();
            if($this->isForeignKey($field) || $field->isUnique() )
              $this->foreignKeys->append($field);
            if ($field->isPrimaryKey())
                $this->primaryField = $field;  
        }
        if ($this->extends)
        {
            $modelInfo = BenderSettings::getInstance()->getModel($this->extendedTableName);
            $this->extendedTable = new DbTable($modelInfo['table'], $this->databaseName, $modelInfo );
            $this->extendedTable->initialize();
        }
    }
    
    /**
     * Agrega campos a la tabla
     * @param FieldCollection $fields
     */
    public function addFields(FieldCollection $fields)
    {
        foreach($fields as $offset => $field){
            $this->fields->offsetSet($offset, $field);
        }
    }
    
    /**
     * Limpia los comentarios (les quita los acentos para que no se muera esta cosa)
     *
     * @param string $comment
     * @return string $comment
     */
    private function parseComment($string)
    {
      $string = strtr($string,
         "\xe1\xc1\xe0\xc0\xe2\xc2\xe4\xc4\xe3\xc3\xe5\xc5".
         "\xaa\xe7\xc7\xe9\xc9\xe8\xc8\xea\xca\xeb\xcb\xed".
         "\xcd\xec\xcc\xee\xce\xef\xcf\xf1\xd1\xf3\xd3\xf2".
         "\xd2\xf4\xd4\xf6\xd6\xf5\xd5\x8\xd8\xba\xf0\xfa\xda".
         "\xf9\xd9\xfb\xdb\xfc\xdc\xfd\xdd\xff\xe6\xc6\xdf\xf8",
         "aAaAaAaAaAaAacCeEeEeEeEiIiIiIiInNo".
         "OoOoOoOoOoOoouUuUuUuUyYyaAso");
      return $string;
    }
    
    /**
     * Obtiene el nombre completo del getter
     * @param DbField $field
     * @return string 
     */
    private function parseCompleteGetterName(DbField $field)
    {
        if($field->getBaseDataType() == 'date')
        {
            return "{$field->getGetterName()}()->get('yyyy-MM-dd')";
        }
        else if($field->getBaseDataType() == 'time')
        {
            return "{$field->getGetterName()}()->get('HH:mm:ss')";
        }
        else if($field->getBaseDataType() == 'datetime' || $field->getBaseDataType() == 'timestamp')
        {
            return "{$field->getGetterName()}()->get('yyyy-MM-dd HH:mm:ss')";
        }
        else
        {
            return "{$field->getGetterName()}()";
        }
    
    }
    
    
    /**
     * Obtiene los nombres de campos y tablas en CamelCase
     *
     * @param string $string
     * @param boolean $first
     * @param boolean $preserve 
     * @return string
     */
    public static function getCamelCase($string, $first = false, $preserve = false)
    {
        $array = explode('_', $string);
        // Initialise the string to be returned
        $string = '';
        // Loop through each element in the array
        foreach ( $array as $i => $segment )
        {
            // If the preserve case option has not been set
            if (! $preserve)
            {
                // Make sure the current segment does not contain uppercase characters
                $segment = strtolower($segment);
            }
            // If it isn't the first segment or the resulting string must start with an uppercase character
            if ($i || $first)
            {
                // Capitalise the first character of the segment
                $segment = ucfirst($segment);
            }
            // Add this segment to the end of the string
            $string .= $segment;
        }
        // Return the resulting string
        return $string;
    }
    
    /**
     * Es una llave foranea?
     * @param dbField $field
     * @return boolean
     */
    public function isForeignKey(dbField $field)
    {
      return eregi('^id\_',$field->getName());
    }
    
    /**
     * Obtiene el tipo de dato a usar en los campos 
     *
     * @param string $type
     * @return string
     */
    public static function parseDataType($type)
    {
        $patterns[0] = '/^date$/i';
        $patterns[1] = '/^datetime$/i';
        $patterns[2] = '/^blob$/i';
        $patterns[3] = '/^time$/i';
        $patterns[4] = '/^timestamp$/i';
        $patterns[5] = '/^real$/i';
        $patterns[6] = '/^varchar$/i';
        $patterns[7] = '/^text$/i';
        $patterns[8] = '/^char$/i';
        $replacements[0] = 'Zend_Date';
        $replacements[1] = 'Zend_Date';
        $replacements[2] = 'string';
        $replacements[3] = 'Zend_Date';
        $replacements[4] = 'Zend_Date';
        $replacements[5] = 'float';
        $replacements[6] = 'string';
        $replacements[7] = 'string';
        $replacements[8] = 'string';
        return preg_replace($patterns, $replacements, $type);
    
    }
    
    /**
     * Saca la informacion del tipo de dato que contiene la columna y el numero maximo de caracteres
     *
     * @param string $type
     * @return Array
     */
    public function parseTypeAndLength( $type )
    {
      if(strpos($type,'(') === FALSE) 
        return array('type' => $type, 'max' => 0);
      $m = array();
      preg_match("/([a-zA-Z]{1,10})\\(([0-9]{1,8})\\)/i",$type, $m );
      return array('type' => $m[1], 'max' => $m[2]);
    }
    
    /**
     * Obtiene el tipo de dato a usar en los campos 
     *
     * @param string $type
     * @return string
     */
    public static function parseCastDataType($type)
    {
        $objects = array('Zend_Date');
        return (in_array($type, $objects)) ? $type . ' ' : '';
    }
    
    /**
     * @return FieldCollection
     */
    public function getFields()
    {
        return $this->fields;
    }
    
    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }
    
    /**
     * @param FieldCollection $fields
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }
    
    /**
     * @param string $table
     */
    public function setTable($table)
    {
        $this->table = $table;
    }
    
    /**
     * @return DbField
     */
    public function getPrimaryField()
    {
        return $this->primaryField;
    }
    
    /**
     * @return DbTable
     */
    public function getExtendedTable()
    {
        return $this->extendedTable;
    }
    
    /**
     * @return boolean
     */
    public function getExtends()
    {
        return $this->extends;
    }
    
    /**
     * @param DbTable $extendedTable
     */
    public function setExtendedTable($extendedTable)
    {
        $this->extendedTable = $extendedTable;
    }
    
    /**
     * @param boolean $extends
     */
    public function setExtends($extends)
    {
        $this->extends = $extends;
    }
    
    /**
     * @return string
     */
    public function getObject()
    {
        return $this->object;
    }
    
    /**
     * @return boolean
     */
    public function hasPrimaryField()
    {
        return $this->primaryField instanceof DbField;
    }
    
    /**
     * Determina si el objeto tiene un Behavior Asociado
     * @param string $behaviorName
     */
    public function hasBehavior($behaviorName)
    {
        return isset($this->behaviors[$behaviorName]);
    }
    
    /**
     * @return array
     */
    public function getBehaviors()
    {
        return $this->behaviors;
    }
    
    /**
     * @return boolean
     */
    public function hasBehaviors()
    {
        return count($this->behaviors) > 0 ? true : false;
    }
    
    public function getField($fieldName)
    {
        if (! $this->fields->offsetExists($fieldName))
            throw new Exception("Field [{$fieldName}] doesn't exists");
        return $this->fields->offsetGet($fieldName);
    }
    
    /**
     * @return string
     */
    public function getExtendedTableName()
    {
        return $this->extendedTableName;
    }
    
    /**
     * @param string $extendedTableName
     */
    public function setExtendedTableName($extendedTableName)
    {
        $this->extendedTableName = $extendedTableName;
    }
  
  /**
   * @return FieldCollection
   */
  public function getForeignKeys()
  {
    return $this->foreignKeys;
  }

    
}
