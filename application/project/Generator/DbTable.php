<?php
/**
 * DbTable
 * @author Juan Carlos Jarquin
 */

require_once 'application/project/Generator/FieldCollection.php';
require_once 'application/project/Generator/DbField.php';

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
        $this->extends = $modelInfo['extends'];
        $this->extendedTableName = isset($modelInfo['extended']) ? $modelInfo['extended'] : '';
        if(!isset($modelInfo['object']))
            throw new Exception('Ha ocurrido un error no se pudo encontrar la información del objeto ');
        $this->object = $modelInfo['object'];
    }
    
    
    /**
     * Genera toda la información acerca de la tabla
     */
    public function initialize()
    {
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
            $field->setConstantName(strtoupper($fieldData->name));
            $field->setDataType(DbTable::parseDataType($fieldData->type));
            $field->setCastDataType(DbTable::parseCastDataType($field->getDataType()));
            $field->setBaseDataType($fieldData->type);
            $field->setIsPrimaryKey(($fieldData->primary_key == 0 ? false : true));
            if ($field->isPrimaryKey())
            {
                $this->primaryField = $field;
            }
            $query = "select COLUMN_COMMENT FROM information_schema.COLUMNS where TABLE_SCHEMA = '{$this->databaseName}' and TABLE_NAME = '{$this->table}' and  COLUMN_NAME = '{$fieldData->name}'";
            $commentResource = $this->database->query($query);
            $commentData = $this->database->fetch_array($commentResource);
            $field->setComment($commentData['COLUMN_COMMENT']);
            $this->fields->append($field);
        }
        if($this->extends){
            $this->extendedTable = new DbTable(ModelController::$models[$this->extendedTableName]['table'],$this->databaseName,ModelController::$models[$this->extendedTableName]);
            $this->extendedTable->initialize();
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
}
