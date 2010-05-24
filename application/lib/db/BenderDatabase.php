<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


class BenderDatabase
{

    /**
     * Instancia de la clase
     * @var BenderDatabase
     */
    private static $instance = null;

    /**
     * @var BenderTableCollection
     */
    private $tables = null;
    
    /**
     * Instancia del PDO
     * @var PDO
     */
    private static $pdo = null;

    /**
     * @var boolean
     */
    private $customizedTables = false;
    
    /**
     * Constructor de la clase
     */
    private function BenderDatabase()
    {
        $this->populate();
    }

    /**
     * La instancia del singleton
     * @return BenderDatabase
     */
    public static function getInstance()
    {
        if(self::$instance === null)
        {
            self::$instance = new BenderDatabase();
        }
        return self::$instance;
    }

    /**
     * @return BenderTableCollection
     */
    public function getTables()
    {
        return $this->tables;
    }

    /**
     * Comienza a obtener la informacion de las tablas->campos
     */
    final private function populate()
    {
        self::getPDO();
        $s = BenderSettings::getInstance();
        $this->tables = new BenderTableCollection();
        if(BenderRequest::getInstance()->getFlag('ignore-database')) return;
        $out = CommandLineInterface::getInstance();

        if( count($s->getSchema()) == 0 )
          throw new BenderDatabaseException( $s->getSchemaFile() . ' schema file does not exists');
      
        // obtiene la coleccion de tablas
        foreach($s->getSchema() as $tableName => $info)
        {
            $table = new BenderTable($info);
            $table->initialize($tableName);
            $this->tables->offsetSet($tableName, $table);
        }
        
        // obtiene la informacion de la jerarquia
        foreach($s->getSchema() as $tableName => $info)
        {
            if($info['extends'])
            {
                $parent = $this->tables->offsetGet($info['extends']);
                if(!($parent instanceof BenderTable))
                    throw new InvalidArgumentException("El objeto [{$info['extends']}] es invalido (schema.yml)");
                $table = $this->tables->offsetGet($tableName);
                $table->setExtendedTable($parent);
            }
        }
        // se obtiene la informacion de las relaciones
        foreach($s->getSchema() as $tableName => $info)
        {
            if(isset($info['relations']) && is_array($info['relations']))
            {
                $table = $this->tables->offsetGet($tableName);
                foreach($info['relations'] as $relatedTableName => $more)
                {
                    $relatedTable = $this->tables->offsetGet($relatedTableName);
                    if($more['type'] == 'manyToMany')
                      $table->addManyToManyRelation($relatedTable,$more['table']);
                }
            }
        }
    }

    
    /**
     * obtiene el PDO
     * TODO que se pueda llamar este metodo estatico en cualquier punto ya que por ahora se necesita
     * que se mande a llamar el metodo populate para que el pdo se genere
     * @return PDO
     */
    public static function getPDO()
    {
        if(!isset(self::$pdo))
        {
          $s = BenderSettings::getInstance();
          self::$pdo = new BenderPDO($s->getDsn(), $s->getUsername(), $s->getPassword());
        }
        return self::$pdo;
    }
    
    /**
     * fill the data types according to the specified lang
     */
    public function setUpLangDataTypes(array $dataTypes, array $types, array $castDataTypes, array $formats)
    {
      if(!$this->customizedTables)
      {
        foreach($this->tables as $table)
        {
          foreach($table->getFields() as $field)
          {
            if(isset($dataTypes[$field->getBaseDataType()]))
              $field->setDataType( $dataTypes[$field->getBaseDataType()] );
            else
              $field->setDataType( $field->getBaseDataType()); // using the default [bad idea]

            if(isset($types[$field->getBaseDataType()]))
              $field->setType( $types[$field->getBaseDataType()] );
            else
              $field->setType( $field->getBaseDataType()); // using the default [bad idea]

            if(isset($castDataTypes[$field->getBaseDataType()]))
              $field->setCastDataType( $castDataTypes[$field->getBaseDataType()] );  // if not exist dont cast

            if(isset($formats[$field->getBaseDataType()]))
              $field->setFormat( $formats[$field->getBaseDataType()] );  // if not exist dont cast           
          }
          $table->getFields()->rewind();
        }
        $this->tables->rewind();
      }
      $this->customizedTables = true;
    }

}



















