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
        $fieldResource = $this->database->query("select * from {$this->table} where true limit 1");
        $numFields = $this->database->getNumFields($fieldResource);
        for($i = 0; $i < $numFields; $i ++)
        {
            $fieldData = $this->database->fetchfield($i, $fieldResource);
            $field = new DbField($fieldData->name);
            $field->setTable($this->table);
            $field->setSetterName('set' . DbTable::getCamelCase($fieldData->name, true));
            $field->setGetterName('get' . DbTable::getCamelCase($fieldData->name, true));
            $field->setPhpName(DbTable::getCamelCase($fieldData->name));
            $field->setUpperCaseName( ucfirst( $field->getPhpName() ));
            $field->setConstantName(strtoupper($fieldData->name));
            $field->setDataType(DbTable::parseDataType($fieldData->type));
            $field->setCastDataType(DbTable::parseCastDataType($field->getDataType()));
            $field->setBaseDataType($fieldData->type);
            $field->setIsPrimaryKey(($fieldData->primary_key == 0 ? false : true));
            $field->setCompleteGetterName( $this->parseCompleteGetterName($field) );
            $field->setSimpleName($fieldData->name);
            if ($benderSettings->useConstants())
                $field->setCatalogAccesor($this->getObject().'::'.$field->getConstantName());
            else
                $field->setCatalogAccesor($this->getObject()."::TABLENAME.'.{$field->getName()}'");
            
            if ($field->isPrimaryKey())
            {
                $this->primaryField = $field;
            }
            $query = "select COLUMN_COMMENT FROM information_schema.COLUMNS where TABLE_SCHEMA = '{$this->databaseName}' and TABLE_NAME = '{$this->table}' and  COLUMN_NAME = '{$fieldData->name}'";
            $commentResource = $this->database->query($query);
            $commentData = $this->database->fetch_array($commentResource);
            $field->setComment($commentData['COLUMN_COMMENT']);
            $this->fields->offsetSet($fieldData->name, $field);
            $this->fields->rewind();
            if($this->isForeignKey($fieldData->name) && !($field->isPrimaryKey()))
              $this->foreignKeys->append($field);
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
     * Obtiene el nombre completo del getter
     * @param DbField $field
     * @return string 
     */
    private function parseCompleteGetterName(DbField $field)
    {
        if($field->getBaseDataType() == 'date')
        {
            return "{$field->getGetterName()}()->get('YYYY-MM-dd')";
        }
        else if($field->getBaseDataType() == 'time')
        {
            return "{$field->getGetterName()}()->get('HH:mm:ss')";
        }
        else if($field->getBaseDataType() == 'datetime' || $field->getBaseDataType() == 'timestamp')
        {
            return "{$field->getGetterName()}()->get('YYYY-MM-dd HH:mm:ss')";
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
     * @param string $column
     * @return boolean
     */
    public function isForeignKey($column)
    {
      return eregi('^id\_',$column);
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
        $replacements[0] = 'Zend_Date';
        $replacements[1] = 'Zend_Date';
        $replacements[2] = 'string';
        $replacements[3] = 'Zend_Date';
        $replacements[4] = 'Zend_Date';
        $replacements[5] = 'float';
        return preg_replace($patterns, $replacements, $type);
    
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
        return (get_class($this->primaryField) == 'DbField') ? true : false;
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
