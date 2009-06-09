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
     * Arreglo de las settings 
     * @var array
     */
    private $settings = array();
    
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
     * Constructor de la clase
     *
     * @param array $settings
     * @return SchemaGenerator
     */
    public function SchemaGenerator($settings)
    {
        $this->settings = $settings;
    
    }
    
    /**
     * Genera el archivo que se va a guardar
     */
    public function generate()
    {
        include_once 'application/project/Yaml/Spyc.php';
        $db = Database::getInstance();
        $schema = array();
        foreach ( $db->fetch_all_array("SHOW TABLES FROM {$this->databaseName}") as $table )
        {
            $tableName = $table['Tables_in_' . $this->databaseName];
            $objectName = StringFormatter::toCamelCase($tableName, '_', true);
            $schema[$objectName] = array('table' => $tableName, 'extends' => false);
        
        }
        
        $this->fileContent = Spyc::YAMLDump(array('schema' => $schema),2,100);
    }
    
    /**
     * Escribe el archivo en la dirección especificada
     * @param string $path
     */
    public function saveFile($path)
    {
        CommandLineInterface::getInstance()->printSection('Generator', 'Saving file ' . $path, 'NOTE');
        $dir = dirname($path);
        if (! is_dir($dir))
            mkdir($dir, 0777, true);
        
        $handle = fopen($path, "w");
        if ($this->settings['encoding'] != 'UTF-8')
            $this->fileContent = iconv("UTF-8", $this->settings['encoding'], $this->fileContent);
        
        fwrite($handle, $this->fileContent);
        fclose($handle);
    }

}