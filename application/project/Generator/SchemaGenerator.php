<?php
/**
 * SchemaGenerator
 * @author Juan Carlos Jarquin
 */

class SchemaGenerator
{
    
    /**
     * Nombre de la base de datos a la que nos vamos a conectar
     * @var string $databaseName
     */
    private $databaseName = '';
    
    /**
     * El contenido del archivo que se generará
     * @var string
     */
    private $fileContent = '';
    
    /**
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->databaseName;
    }
    
    /**
     * @param string $databaseName
     */
    public function setDatabaseName($databaseName)
    {
        $this->databaseName = $databaseName;
    }
    
    /**
     * Genera el archivo que se va a guardar
     */
    public function generate()
    {
        $db = Database::getInstance();
        $schema = array();
        foreach ( $db->fetch_all_array("SHOW FULL TABLES FROM {$this->databaseName}") as $table )
        {
            if($table['Table_type'] == 'VIEW') continue;
            $tableName = $table['Tables_in_' . $this->databaseName];
            $objectName = StringFormatter::toCamelCase($tableName, '_', true);
            
            $dbTable = new DbTable($tableName,  BenderSettings::getInstance()->getDbName(), array('object' => $tableName));
            $dbTable->initialize();
            
            $dbFields = array();
            
            $fields = $dbTable->getFields();
            while ($fields->valid())
            {
              $field = $fields->current();
              $dbFields[ $field->getName() ] = array(
                'type' => $field->getDataType()
              );
              $fields->next();
            }
            
            $schema[$objectName] = array('table' => $tableName, 'extends' => false,'fields' => $dbFields);
        }
        $this->fileContent = Spyc::YAMLDump(array('schema' => $schema),2,150);
    }
    
    /**
     * Escribe el archivo en la dirección especificada
     * @param string $path
     */
    public function saveFile($path)
    {
        $benderSettings = BenderSettings::getInstance();
        CommandLineInterface::getInstance()->printSection('Generator', 'Saving file ' . $path, 'NOTE');
        $dir = dirname($path);
        if (! is_dir($dir))
            mkdir($dir, 0777, true);
        
        $handle = fopen($path, "w");
        if ($benderSettings->getEncoding() != 'UTF-8')
            $this->fileContent = iconv("UTF-8", $benderSettings->getEncoding(), $this->fileContent);
        
        fwrite($handle, $this->fileContent);
        fclose($handle);
    }

}