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
 * Clase que representa una tabla en la base de datos, para conseguir los tipos de dato etc.
 *
 */
class BenderTable
{

    /**
     * Nombre de la tabla a la que se va a consultar
     * @var string
     */
    private $tableName = '';

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
     * Array of Tables
     * @var FieldCollection
     */
    private $nonPrimaryFields = null;
    
    /**
     * Array of Tables
     * @var BenderFieldCollection
     */
    private $uniqueFields = null;

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
     * Nombre del objeto que representan
     */
    private $lowerObject = '';

    /**
     * Las llaves foraneas
     * @var FieldCollection
     */
    private $foreignKeys = null;
    
    /**
     * Las No llaves foraneas
     * @var FieldCollection
     */
    private $nonForeignKeys = null;

    /**
     * Comportamientos que debe tener el objeto
     * @var array
     */
    private $behaviors = array();

    /**
     * Info
     * @var Array
     */
    private $info = array();

    /**
     * @var BenderTableCollection
     */
    private $manyToManyRelations = null;
    
    /**
     * Constructor
     *
     * @param string $table
     * @param string $database
     * @param array $modelInfo
     * @param string $object;
     * @return DbTable
     */
    public function BenderTable($table)
    {
        $this->tableName = $table['table'];
        $this->fields = new BenderFieldCollection();
        $this->nonPrimaryFields = new BenderFieldCollection();
        $this->foreignKeys = new BenderFieldCollection();
        $this->nonForeignKeys = new BenderFieldCollection();
        $this->uniqueFields = new BenderFieldCollection();
        $this->manyToManyRelations = array();
        $this->behaviors = isset($table['behaviors']) ? $table['behaviors'] : array();
        $this->info = $table;
    }


    /**
     * Genera toda la información acerca de la tabla
     * @param string $objectName El nombre que se va a dar al objeto
     */
    public function initialize($objectName)
    {
        $this->object = $objectName;
        $this->lowerObject = Formatter::upperCamelCaseToCamelCase($this->object);
        $s = BenderSettings::getInstance();
        $pdo = BenderDatabase::getPDO();
        CommandLineInterface::getInstance()->printSection('Table', "Fetching table info '{$this->tableName}'", 'COMMENT');
        $st =  $pdo->getColumnsFromTable($this->tableName);
        if($st === FALSE)
           throw new BenderDatabaseException(print_r($pdo->errorInfo(),true));

        foreach ($st as $column)
        {
            $type = $this->parseTypeAndLength( $column['Type'] );
            $field = new BenderField($column['Field']);
            $field->setTable($this->tableName);
            $field->setMaxlength($type['max']);
            $field->setBaseDataType($type['type']);

            $field->setSetterName('set' . Formatter::underScoreToUpperCamelCase($field->getName()));
            $field->setGetterName('get' . Formatter::underScoreToUpperCamelCase($field->getName()));
            $field->setVarName( Formatter::underScoreToCamelCase($field->getName()) );            
            $field->setUpperCaseName( Formatter::underScoreToUpperCamelCase($field->getName()) );
            $field->setConstantName( Formatter::underscoreToUpperCase($field->getName()) );
            $field->setCompleteGetterName( 'set'. Formatter::underScoreToUpperCamelCase($field->getName()) );
            $field->setSimpleName( $field->getName()  );
            $field->setComment( Formatter::formatComment($column['Comment']));

            $field->setIsPrimaryKey(($column['Key'] == 'PRI' ? true : false));
            $field->setIsUnique( $column['Key'] == 'UNI' ? true : false );
            $field->setRequired( $column['Null'] == 'NO' ? true : false );

            $field->setDefaultValue($column['Default']);
            $field->setCatalogAccesor($this->getObject().'::'.$field->getConstantName());

            $info = BenderSettings::getInstance()->getObjectSchema($this->object);
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
                if(isset($custom['comment']))
                  $field->setComment($custom['comment']);
                if(isset($custom['default']))
                  $field->setDefaultValue($custom['default']);
            }

            $this->fields->offsetSet($field->getName(), $field);
            $this->fields->rewind();
            
            if( $this->isForeignKey($field) && !$field->isPrimaryKey() )
            {
            	$foreigObject = preg_replace('/(^id\_)|(\_id)$/i', '', $field->getName());
            	$field->setForeignObject(Formatter::underScoreToUpperCamelCase($foreigObject));
            	$field->setForeignLowerObject(Formatter::underScoreToCamelCase($foreigObject));
            	$field->setIsForeignKey(true);
            }
            
            /* Unique Collection Fields */
            if($field->isUnique() && !$field->isPrimaryKey())
                $this->uniqueFields->append($field);
                
            /* ForeignKey Collection Fields and Non ForeignKey Collection Fields  */
            if (  $this->isForeignKey($field)  && !$field->isPrimaryKey() )
              $this->foreignKeys->append($field);
            else if( !$field->isPrimaryKey() )
              $this->nonForeignKeys->append($field);
            
            /* Non Primary Key Collection Fields  as PrimaryKey Field*/
            if ($field->isPrimaryKey())
              $this->primaryField = $field;
            else
              $this->nonPrimaryFields->append($field);
        }
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
        preg_match("/([a-zA-Z]{1,10})\\(([0-9,]{1,8})\\)/i",$type, $m );
        if(!count($m))
        {
          return array('type' => $type, 'max' => 0) ;
        }else
          return array('type' => $m[1], 'max' => $m[2]);  
    }

    /**
     * Agrega campos a la tabla
     * @param BenderFieldCollection $fields
     */
    public function addFields(BenderFieldCollection $fields)
    {
        foreach($fields as $offset => $field){
            $this->fields->offsetSet($offset, $field);
        }
    }

    /**
     * Es una llave foranea?
     * @param BenderField $field
     * @return boolean
     */
    public function isForeignKey(BenderField $field)
    {
        
        return preg_match('(^id\_|\_id$)',$field->getName());
    }

    /**
     * @return BenderFieldCollection
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @param BenderFieldCollection $fields
     */
    public function setFields(BenderFieldCollection $fields)
    {
        $this->fields = $fields;
    }

    /**
     * @param string $table
     */
    public function setTableName($table)
    {
        $this->tableName = $table;
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
     * @param BenderTable $extendedTable
     */
    public function setExtendedTable(BenderTable $extendedTable)
    {
        $this->extendedTable = $extendedTable;
        $this->extends = true;
        $this->extendedTableName = $extendedTable->getTableName();
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
     * @return string
     */
    public function getLowerObject()
    {
        return $this->lowerObject;
    }

    /**
     * @return boolean
     */
    public function hasPrimaryField()
    {
        return $this->primaryField instanceof BenderField;
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

    /**
     * Obtiene un campo
     * @param string
     * @return BenderField
     * @throws BenderDatabaseException
     */
    public function getField($fieldName)
    {
        if (! $this->fields->offsetExists($fieldName))
        throw new BenderDatabaseException("Field [{$fieldName}] doesn't exists");
        return $this->fields->offsetGet($fieldName);
    }

    /**
     * @return array
     */
    public function getInfo()
    {
      return $this->info;
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
    
    
    /**
     * @return FieldCollection
     */
    public function getNonForeignKeys()
    {
        return $this->nonForeignKeys;
    }
    
    /**
     * @return FieldCollection
     */
    public function getNonPrimaryFields()
    {
        return $this->nonPrimaryFields;
    }
    
    /**
     * @return BenderFieldCollection
     */
    public function getUniqueFields()
    {
        return $this->uniqueFields;
    }
    
    /**
     * @param BenderTable
     */
    public function addManyToManyRelation(BenderTable $table,$tableName)
    {
        $sourceTable = new BenderTable(array('table' => $tableName));
        $sourceTable->initialize($table->getTableName().'_'.$tableName);
        $this->manyToManyRelations[$tableName] = array(
            'source' => $sourceTable,
            'related' => $table
        );
    }
    
    /**
     * @return array
     */
    public function getManyToManyRelations()
    {
        return $this->manyToManyRelations;
    }
    
    /**
     * obtiene una relacion
     * @param string
     * @return array|null
     */
    public function getManyToManyRelation($name)
    {
        return (isset($this->manyToManyRelations[$name])) ? $this->manyToManyRelations[$name] : null;
    }
    
    /**
     * @return boolean
     */
    public function hasManyToManyRelations()
    {
        return (boolean) $this->countManyToManyRelations();
    }
    
    /**
     * @return int
     */
    public function countManyToManyRelations()
    {
        return count($this->manyToManyRelations);
    }


}
