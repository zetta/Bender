<?php
/**
 * CatalogModule
 * @author Juan Carlos Jarquin
 */

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
    const VERSION = "0.6.0";
    
    private $library = array(
            'CatalogInterface' => '{db-location}/CatalogInterface.php', 
            'Catalog' => '{db-location}/Catalog.php',
            'Criteria' => '{db-location}/Criteria.php', 
            'DBAO' => '{db-location}/DBAO.php', 
            'BehaviorObserver' => '{db-location}/Behavior/BehaviorObserver.php', 
            'Observer' => '{db-location}/Behavior/Observer.php', 
            'SluggableBehavior' => '{db-location}/Behavior/SluggableBehavior.php');
    
    /**
     * Genera los catálogos
     */
    public function generateAction()
    {
        $this->prepare();
        $benderSettings = BenderSettings::getInstance();
        $schemaFile = "application/data/{$benderSettings->getSchema()}.schema.yml";
        
        if (! file_exists($schemaFile))
            throw new ErrorException("El archivo [{$schemaFile}] no se encuentra");
        $yaml = Spyc::YAMLLoad($schemaFile);
        $bender['models'] = $yaml['schema'];
        $benderSettings->setUp($yaml);
        if(isset($yaml['workcopy']))
            ProjectAutoloader::getInstance()->addDirectory($benderSettings->getWorkCopyLocation());
        if(isset($yaml['libPath']))
            ProjectAutoloader::getInstance()->addDirectory($benderSettings->getLibraryLocation());
        
        foreach ( $bender['models'] as $objectName => $model )
            $bender['models'][$objectName]['object'] = $objectName;
        $benderSettings->setModels($bender['models']);
        $models = array();
        foreach ( $bender['models'] as $objectName => $model )
        {
            $dbTable = new DbTable($model['table'], $benderSettings->getDbName(), $model);
            $dbTable->initialize();
            $models[$objectName] =$dbTable;
        }
        
        
        if($benderSettings->getLibFirst())
            $this->generateLibrary($benderSettings->getLibraryLocation());
        
        foreach ( $models as $objectName => $dbTable )
        {
            $beanGenerator = new BeanGenerator($objectName, $dbTable);
            $beanGenerator->createBean();
            $beanGenerator->saveFile("{$benderSettings->getWorkCopyLocation()}/{$benderSettings->getBeanLocation()}/{$objectName}.php", $benderSettings->getPreserveChanges());
            
            $factoryGenerator = new FactoryGenerator($objectName, $dbTable);
            $factoryGenerator->createFactory();
            $factoryGenerator->saveFile("{$benderSettings->getWorkCopyLocation()}/{$benderSettings->getFactoryLocation()}/{$objectName}Factory.php", $benderSettings->getPreserveChanges());
            
            $catalogGenerator = new CatalogGenerator($objectName, $dbTable);
            $catalogGenerator->createCatalog();
            $catalogGenerator->saveFile("{$benderSettings->getWorkCopyLocation()}/{$benderSettings->getCatalogLocation()}/{$objectName}Catalog.php", $benderSettings->getPreserveChanges());
            
            $catalogGenerator = new CollectionGenerator($objectName, $dbTable);
            $catalogGenerator->createCollection();
            $catalogGenerator->saveFile("{$benderSettings->getWorkCopyLocation()}/{$benderSettings->getCollectionLocation()}/{$objectName}Collection.php", $benderSettings->getPreserveChanges());
            
            $exceptionGenerator = new ExceptionGenerator($objectName,$dbTable);
            $exceptionGenerator->createException();
            $exceptionGenerator->saveFile("{$benderSettings->getLibraryLocation()}/{$benderSettings->getExceptionLocation()}/{$objectName}Exception.php", $benderSettings->getPreserveChanges());
        }
        
        if(!$benderSettings->getLibFirst())
            $this->generateLibrary($benderSettings->getLibraryLocation());
    }
    
    /**
     * Genera las librerias
     * @param string $libPath
     */
    private function generateLibrary($libPath)
    {
        CommandLineInterface::getInstance()->printSection('Model', "Generating Library", 'COMMENT');
        foreach ( $this->library as $objectName => $path )
        {
        	$path = str_replace('{db-location}',BenderSettings::getInstance()->getDbLocation(),$path);
            $libraryGenerator = new LibraryGenerator();
            $libraryGenerator->createLibrary($objectName);
            $libraryGenerator->saveFile("{$libPath}/{$path}", BenderSettings::getInstance()->getPreserveChanges());
        }
    }
    
    
    
    /**
     * Genera un schema a partir de la configuración en el archivo settings
     */
    public function generateSchemaAction()
    {
        $this->prepare();
        $schemaGenerator = new SchemaGenerator();
        $schemaGenerator->setDatabaseName(BenderSettings::getInstance()->getDbName());
        $schemaGenerator->generate();
        $schemaGenerator->saveFile('application/data/generated.schema.yml');
    }
    
    /**
     * Obtiene el arreglo de los ajustes
     * y se conecta a la base de datos
     */
    private function prepare()
    {
        $benderSettings = BenderSettings::getInstance();
        $settingsFile = 'application/data/settings.yml';
        if (! file_exists($settingsFile))
            throw new ErrorException("El archivo de ajustes [{$settingsFile}] no se encuentra");
        $yaml = Spyc::YAMLLoad($settingsFile);
        try
        {
            $benderSettings->setUp($yaml['bender']);
        } catch ( Exception $e )
        {
            throw new Exception("Error {$e->getCode()} : El archivo de configuración parece no ser válido");
        }
        
        $dataBase = Database::getInstance();
        $dataBase->configure($benderSettings->getServer(), $benderSettings->getUsername(), $benderSettings->getPassword(), $benderSettings->getDbName());
        $dataBase->connect();
    }

}







