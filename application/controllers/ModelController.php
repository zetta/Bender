<?php
/**
 * CatalogModule
 * @author Juan Carlos Jarquin
 */

/**
 * GenericModule 
 */
require_once "application/controllers/GenericController.php";

/**
 * Modulo para generar los models de forma automática
 *
 */
class ModelController extends GenericController
{
    /**
     * Version de la clase utilizada
     *
     */
    const VERSION = "0.3a";
    
    /**
     * Los modelos para uso futuro =)
     * @var array
     */
    public static $models = array();
    
    /**
     * Genera los catálogos
     */
    public function generateAction()
    {        
        $bender = $this->prepare();
        $schemaFile = isset($bender['schema']) ? "application/data/{$bender['schema']}.schema.yml" : 'application/data/default.schema.yml';
        
        if (! file_exists($schemaFile))
            throw new ErrorException("El archivo [{$schemaFile}] no se encuentra");
        $yaml = Spyc::YAMLLoad($schemaFile);
        $bender['models'] = $yaml['schema'];
        
        foreach ( $bender['models'] as $objectName => $model )
            $bender['models'][$objectName]['object'] = $objectName;
        
        ModelController::$models = $bender['models'];
        foreach ( $bender['models'] as $objectName => $model )
        {
            $dbTable = new DbTable($model['table'], $bender['mysql']['dbname'], $model);
            $dbTable->initialize();
            $modelPath = "output/{$bender['mysql']['dbname']}";
            
            $beanGenerator = new BeanGenerator($objectName, $dbTable, $model['extends'], $bender);
            $beanGenerator->createBean();
            $beanGenerator->saveFile("{$modelPath}/{$bender['paths']['beans']}/{$objectName}.php");
            
            $factoryGenerator = new FactoryGenerator($objectName, $dbTable, $model['extends'], $bender);
            $factoryGenerator->createFactory();
            $factoryGenerator->saveFile("{$modelPath}/{$bender['paths']['factories']}/{$objectName}Factory.php");
            
            $catalogGenerator = new CatalogGenerator($objectName, $dbTable, $model['extends'], $bender);
            $catalogGenerator->createCatalog();
            $catalogGenerator->saveFile("{$modelPath}/{$bender['paths']['catalogs']}/{$objectName}Catalog.php");
            
            $catalogGenerator = new CollectionGenerator($objectName, $dbTable, $model['extends'], $bender);
            $catalogGenerator->createCollection();
            $catalogGenerator->saveFile("{$modelPath}/{$bender['paths']['collections']}/{$objectName}Collection.php");
        
        }
        
        CommandLineInterface::getInstance()->printSection('Model', "Generating Library", 'COMMENT');
        
        $catalogLibraryGenerator = new LibraryGenerator($bender);
        $catalogLibraryGenerator->createLibrary('Catalog');
        $catalogLibraryGenerator->saveFile("output/Project/Db/Catalog.php");
        
        $criteriaGenerator = new LibraryGenerator($bender);
        $criteriaGenerator->createLibrary('Criteria');
        $criteriaGenerator->saveFile("output/Project/Db/Criteria.php");
        
        $dbaoGenerator = new LibraryGenerator($bender);
        $dbaoGenerator->createLibrary('DBAO');
        $dbaoGenerator->saveFile("output/Project/Db/DBAO.php");
    }
    
    /**
     * Genera un schema a partir de la configuración en el archivo settings
     */
    public function generateSchemaAction()
    {

        $bender = $this->prepare();
        $schemaGenerator = new SchemaGenerator($bender);
        $schemaGenerator->setDatabaseName($bender['mysql']['dbname']);
        $schemaGenerator->generate();
        $schemaGenerator->saveFile('application/data/generated.schema.yml');
    }
    
    /**
     * Obtiene el arreglo de los ajustes
     * y se conecta a la base de datos
     * @return mixed
     */
    private function prepare()
    {
        $settingsFile = 'application/data/settings.yml';
        if (! file_exists($settingsFile))
            throw new ErrorException("El archivo de ajustes [{$settingsFile}] no se encuentra");
        $yaml = Spyc::YAMLLoad($settingsFile);
        try
        {
            $bender = new ArrayObject(isset($yaml['bender']) ? $yaml['bender'] : null);
        } catch ( Exception $e )
        {
            throw new Exception("Error {$e->getCode()} : El archivo de configuración parece no ser válido");
        }
        
        $dataBase = Database::getInstance();
        $dataBase->configure($bender['mysql']['server'], $bender['mysql']['username'], $bender['mysql']['password'], $bender['mysql']['dbname']);
        $dataBase->connect();
        
        return $bender;
    }

}








