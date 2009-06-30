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
    const VERSION = "0.4";
    
    /**
     * Los modelos para uso futuro =)
     * @var array
     */
    public static $models = array();
    
    /**
     * Las settings para poder accesar a ellas cuando se neceesiten =P
     */
    public static $settings = array();
    
    private $library = array(
            'CatalogInterface' => 'Db/CatalogInterface.php', 
            'Catalog' => 'Db/Catalog.php',
            'Criteria' => 'Db/Criteria.php', 
            'DBAO' => 'Db/DBAO.php', 
            'BehaviorObserver' => 'Db/Behavior/BehaviorObserver.php', 
            'Observer' => 'Db/Behavior/Observer.php', 
            'SluggableBehavior' => 'Db/Behavior/SluggableBehavior.php');
    
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
        
        
        $modelPath =  (isset($yaml['workcopy'])) ? $yaml['workcopy'] : "output/{$bender['mysql']['dbname']}";
        $libPath = (isset($yaml['libPath'])) ? $yaml['libPath'] : "output/Project";
        $libfirst = (isset($yaml['libfirst'])) ? $yaml['libfirst'] : true;
        
        if(isset($yaml['workcopy']))
            ProjectAutoloader::getInstance()->addDirectory($yaml['workcopy']);
        if(isset($yaml['libPath']))
            ProjectAutoloader::getInstance()->addDirectory($yaml['libPath']);
        
        foreach ( $bender['models'] as $objectName => $model )
            $bender['models'][$objectName]['object'] = $objectName;
        
        ModelController::$models = $bender['models'];
        
        if($libfirst)
            $this->generateLibrary($libPath, $bender);
        
        foreach ( $bender['models'] as $objectName => $model )
        {
            $dbTable = new DbTable($model['table'], $bender['mysql']['dbname'], $model);
            $dbTable->initialize();
            
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
        
        if(!$libfirst)
            $this->generateLibrary($libPath, $bender);
        
    }
    
    /**
     * Genera las librerias
     * @param string $libPath
     * @param array $settings
     */
    private function generateLibrary($libPath,$settings)
    {
        CommandLineInterface::getInstance()->printSection('Model', "Generating Library", 'COMMENT');
        foreach ( $this->library as $objectName => $path )
        {
            $libraryGenerator = new LibraryGenerator($settings);
            $libraryGenerator->createLibrary($objectName);
            $libraryGenerator->saveFile("{$libPath}/{$path}",false);
        }
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
        ModelController::$settings = $bender;
        return $bender;
    }

}







