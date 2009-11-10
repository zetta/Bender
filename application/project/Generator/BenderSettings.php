<?php
/**
 * BenderSettings
 * @author Juan Carlos Jarquin
 */

/**
 * Clase que guardará las settings =) 
 *
 */
class BenderSettings
{
    
    /**
     * Instancia
     * @var BenderSettings
     */
    private static $instance = null;
    
    /**
     * Determina si se utilizarán o no includes en los archivos generados
     * @var boolean
     */
    private $addIncludes = false;
    
    /**
     * Determina si el modelo generado utilizará comportamientos
     * @var boolean
     */
    private $useBehaviors = true;
    
    /**
     * Determina si los catálogos generados serán singleton
     * @var boolean
     */
    private $singleton = true;
    
    /**
     * Determina si el model utilizarán constantes para leer las referencias a si misma,
     * extremadamente util para ser utilizada con el objeto Criteria público
     * @var boolean
     */
    private $useConstants = true;
    
    /**
     * Arreglo donde se guardan los models
     * @var DbTable[]
     */
    private $models = array();
    
    /**
     * Determina si queremos que se guarden los cambios hechos a los models
     * @var boolean
     */
    private $preserveChanges = false;
    
    /**
     * El Path de las librerias
     * @var string
     */
    private $libLocation = 'Lib';
    
    /**
     * Codificación utilizada en los archivos que se generarán
     * @var string
     */
    private $encoding = 'UTF-8';
    
    /**
     * Determina que archivo de schema se utilizará
     * @var string
     */
    private $schema = 'default';
    
    /**
     * Determina si los archivos generados llevarán la leyenda que diga que fueron generados con bender
     * @var boolean
     */
    private $addBenderSignature = true;
    
    /**
     * Determina si el criteria utilizado será privado o no
     * @var boolean
     */
    private $privateCriteria = false;
    
    /**
     * 'firma' que se agregará en los archivos  
     * @var string
     */
    private $benderSignature = "This File as been proudly generated by Bender (http://code.google.com/p/bender-modeler/)";
    
    /**
     * Nombre del autor de los archivos
     * @var string
     */
    private $author = 'zetta';
    
    /**
     * Pon aqui el nombre de tu empresa, organizacion o cosa =P
     * @var string
     */
    private $brandName = 'ctrl-zetta';
    
    /**
     * un pequeño copyrigt o algo asi =P
     * @var string
     */
    private $copyRight = '(c) zetta';
    
    /**
     * Descripción del sistema que generas, o algo por el estilo
     * @var string
     */
    private $description = '';
    
    /**
     * El PATH base donde estará tu model ( mas que nada sirve para los includes =P ) 
     * @var string
     */
    private $modelLocation = '/';
    
    /**
     * PATH donde estarán los catálogos (includes)
     * @var string
     */
    private $catalogLocation = 'Catalogs';
    
    /**
     * PATH donde estarán los beans (includes)
     * @var string
     */
    private $beanLocation = '';
    
    /**
     * PATH donde estarán las factories (includes)
     * @var string
     */
    private $factoryLocation = '';
    
    /**
     * PATH donde estarán las collections (includes)
     * @var string
     */
    private $collectionLocation = '';
    
    /**
     * Nombre de usuario a utilizar en la conexión a la base de datos mysql
     * @var string
     */
    private $username = 'db_username';
    
    /**
     * Password 
     * @var string
     */
    private $password = 'password';
    
    /**
     * Servidor donde se conectará 
     * @var string
     */
    private $server = 'localhost';
    
    /**
     * Nombre de la base de datos
     * @var string
     */
    private $dbName = 'db_name';
    
    /**
     * PATH donde se guardaran los archivos ( o donde estan los antiguos)
     * @var string
     */
    private $workCopyLocation = 'output/';
    private $baseWorkCopyLocation = 'output/';
    /**
     * PATH donde se guardan las librerias
     * @var string
     */
    private $libraryLocation = 'output/Project';
    
    /**
     * @var string
     */
    private $dbLocation = 'Db';
    
    /**
     * Determina si las librerias serán generadas antes de los archivos
     * @var boolean
     */
    private $libFirst = false;
	
	/**
	 * @return string
	 */
	public function getDbLocation() {
		return $this->dbLocation;
	}
	
	/**
	 * @param string $dbLocation
	 */
	public function setDbLocation($dbLocation) {
		$this->dbLocation = $dbLocation;
	}

    
    /**
     * @return boolean
     */
    public function getAddBenderSignature()
    {
        return $this->addBenderSignature;
    }
    
    /**
     * @return boolean
     */
    public function getAddIncludes()
    {
        return $this->addIncludes;
    }
    
    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }
    
    /**
     * @return string
     */
    public function getBeanLocation()
    {
        return $this->beanLocation;
    }
    
    /**
     * @return string
     */
    public function getBenderSignature()
    {
        return $this->benderSignature;
    }
    
    /**
     * @return string
     */
    public function getBrandName()
    {
        return $this->brandName;
    }
    
    /**
     * @return string
     */
    public function getCatalogLocation()
    {
        return $this->catalogLocation;
    }
    
    /**
     * @return string
     */
    public function getCollectionLocation()
    {
        return $this->collectionLocation;
    }
    
    /**
     * @return string
     */
    public function getCopyRight()
    {
        return $this->copyRight;
    }
    
    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * @return string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }
    
    /**
     * @return string
     */
    public function getFactoryLocation()
    {
        return $this->factoryLocation;
    }
    
    /**
     * @return string
     */
    public function getModelLocation()
    {
        return $this->modelLocation;
    }
    
    /**
     * @return boolean
     */
    public function getSingleton()
    {
        return $this->singleton;
    }
    
    /**
     * @return boolean
     */
    public function getUseBehaviors()
    {
        return $this->useBehaviors;
    }
    
    /**
     * @return boolean
     */
    public function useConstants()
    {
        return $this->useConstants;
    }
    
    /**
     * @param boolean $addBenderSignature
     */
    public function setAddBenderSignature($addBenderSignature)
    {
        $this->addBenderSignature = $addBenderSignature;
    }
    
    /**
     * @param boolean $addIncludes
     */
    public function setAddIncludes($addIncludes)
    {
        $this->addIncludes = $addIncludes;
    }
    
    /**
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }
    
    /**
     * @param string $beanLocation
     */
    public function setBeanLocation($beanLocation)
    {
        $this->beanLocation = $beanLocation;
    }
    
    /**
     * @param string $benderSignature
     */
    public function setBenderSignature($benderSignature)
    {
        $this->benderSignature = $benderSignature;
    }
    
    /**
     * @param string $brandName
     */
    public function setBrandName($brandName)
    {
        $this->brandName = $brandName;
    }
    
    /**
     * @param string $catalogLocation
     */
    public function setCatalogLocation($catalogLocation)
    {
        $this->catalogLocation = $catalogLocation;
    }
    
    /**
     * @param string $collectionLocation
     */
    public function setCollectionLocation($collectionLocation)
    {
        $this->collectionLocation = $collectionLocation;
    }
    
    /**
     * @param string $copyRight
     */
    public function setCopyRight($copyRight)
    {
        $this->copyRight = $copyRight;
    }
    
    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    
    /**
     * @param string $encoding
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
    }
    
    /**
     * @param string $factoryLocation
     */
    public function setFactoryLocation($factoryLocation)
    {
        $this->factoryLocation = $factoryLocation;
    }
    
    /**
     * @param string $modelLocation
     */
    public function setModelLocation($modelLocation)
    {
        $this->modelLocation = $modelLocation;
    }
    
    /**
     * @param boolean $singleton
     */
    public function setSingleton($singleton)
    {
        $this->singleton = $singleton;
    }
    
    /**
     * @param boolean $useBehaviors
     */
    public function setUseBehaviors($useBehaviors)
    {
        $this->useBehaviors = $useBehaviors;
    }
    
    /**
     * @param boolean $useConstants
     */
    public function setUseConstants($useConstants)
    {
        $this->useConstants = $useConstants;
    }
    
    /**
     * @return string
     */
    public function getDbName()
    {
        return $this->dbName;
    }
    
    /**
     * @return boolean
     */
    public function getLibFirst()
    {
        return $this->libFirst;
    }
    
    /**
     * @return string
     */
    public function getLibraryLocation()
    {
        return $this->libraryLocation;
    }
    
    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
    
    /**
     * @return string
     */
    public function getServer()
    {
        return $this->server;
    }
    
    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }
    
    /**
     * @return string
     */
    public function getWorkCopyLocation()
    {
        return $this->workCopyLocation;
    }
    
    /**
     * @param string $dbName
     */
    public function setDbName($dbName)
    {
        $this->dbName = $dbName;
    }
    
    /**
     * @param boolean $libFirst
     */
    public function setLibFirst($libFirst)
    {
        $this->libFirst = $libFirst;
    }
    
    /**
     * @param string $libraryLocation
     */
    public function setLibraryLocation($libraryLocation)
    {
        $this->libraryLocation = $libraryLocation;
    }
    
    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
    
    /**
     * @param string $server
     */
    public function setServer($server)
    {
        $this->server = $server;
    }
    
    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }
    
    /**
     * @param string $workCopyLocation
     */
    public function setWorkCopyLocation($workCopyLocation)
    {
        $this->workCopyLocation = $workCopyLocation;
    }
    
    /**
     * Pues obtiene la instancia unica del singleton
     * @return BenderSettings
     */
    public static function getInstance()
    {
        if (! isset(self::$instance))
        {
            self::$instance = new BenderSettings();
        }
        return self::$instance;
    }
    
    /**
     * @return boolean
     */
    public function isPrivateCriteria()
    {
        return $this->privateCriteria;
    }
    
    /**
     * @param boolean $privateCriteria
     */
    public function setPrivateCriteria($privateCriteria)
    {
        $this->privateCriteria = $privateCriteria;
    }
    
    /**
     * @return DbTable[]
     */
    public function getModels()
    {
        return $this->models;
    }
    
    /**
     * @return string
     */
    public function getSchema()
    {
        return $this->schema;
    }
    
    /**
     * @param DbTable[] $models
     */
    public function setModels($models)
    {
        $this->models = $models;
    }
    
    /**
     * @param string $schema
     */
    public function setSchema($schema)
    {
        $this->schema = $schema;
    }
    
	/**
	 * @return string
	 */
	public function getLibLocation() {
		return $this->libLocation;
	}
	
	/**
	 * @param string $libLocation
	 */
	public function setLibLocation($libLocation) {
		$this->libLocation = $libLocation;
	}
    
    /**
     * Constructor de la clase
     * @return BenderSettings
     */
    private function BenderSettings()
    {
    }
    
    /**
     * Obtiene un DbTable dado el nombre
     *
     * @param string $name
     * @return array
     * @throws Exception
     */
    public function getModel($name)
    {
        foreach ( $this->models as $modelName => $model )
        {
            if ($name == $modelName)
                return $model;
        }
        throw new Exception('No se encontró el model [' . $name .']');
    }
    
    /**
     * @return boolean
     */
    public function getPreserveChanges()
    {
        return $this->preserveChanges;
    }
    
    /**
     * @param boolean $preserveChanges
     */
    public function setPreserveChanges($preserveChanges)
    {
        $this->preserveChanges = $preserveChanges;
    }

    
    /**
     * Guarda las configuraciones
     * @param Array $settings
     */
    public function setUp($settings)
    {
        $this->addIncludes        = isset($settings['add_includes']) ? $settings['add_includes'] : $this->addIncludes;
        $this->useBehaviors       = isset($settings['use_behaviors']) ? $settings['use_behaviors'] : $this->useBehaviors;
        $this->singleton          = isset($settings['singleton']) ? $settings['singleton'] : $this->singleton;
        $this->useConstants       = isset($settings['use_constants']) ? $settings['use_constants'] : $this->useConstants;
        $this->privateCriteria    = isset($settings['private_criteria']) ? $settings['private_criteria'] : $this->privateCriteria;
        $this->encoding           = isset($settings['encoding']) ? $settings['encoding'] : $this->encoding;
        $this->addBenderSignature = isset($settings['add_bender_signature']) ? $settings['add_bender_signature'] : $this->addBenderSignature;
        $this->author             = isset($settings['header']['author']) ? $settings['header']['author'] : $this->author;
        $this->brandName          = isset($settings['header']['brand_name']) ? $settings['header']['brand_name'] : $this->brandName;
        $this->copyRight          = isset($settings['header']['copyright']) ? $settings['header']['copyright'] : $this->copyRight;
        $this->description        = isset($settings['header']['description']) ? $settings['header']['description'] : $this->description;
        $this->username           = isset($settings['mysql']['username']) ? $settings['mysql']['username'] : $this->username;
        $this->password           = isset($settings['mysql']['password']) ? $settings['mysql']['password'] : $this->password;
        $this->server             = isset($settings['mysql']['server']) ? $settings['mysql']['server'] : $this->server;
        $this->dbName             = isset($settings['mysql']['dbname']) ? $settings['mysql']['dbname'] : $this->dbName;
        $this->schema             = isset($settings['schema']) ? $settings['schema'] : $this->schema;
        $this->modelLocation      = isset($settings['paths']['model_location']) ? $settings['paths']['model_location'] : $this->modelLocation;
        $this->catalogLocation    = isset($settings['paths']['catalog_location']) ? $settings['paths']['catalog_location'] : $this->catalogLocation;
        $this->beanLocation       = isset($settings['paths']['bean_location']) ? $settings['paths']['bean_location'] : $this->beanLocation;
        $this->factoryLocation    = isset($settings['paths']['factory_location']) ? $settings['paths']['factory_location'] : $this->factoryLocation;
        $this->collectionLocation = isset($settings['paths']['collection_location']) ? $settings['paths']['collection_location'] : $this->collectionLocation;
        $this->libFirst           = isset($settings['lib_first']) ? $settings['lib_first'] : $this->libFirst;
        $this->libraryLocation    = isset($settings['lib_path']) ? $settings['lib_path'] : $this->libraryLocation;
        $this->workCopyLocation   = isset($settings['workcopy']) ? $settings['workcopy'] : $this->baseWorkCopyLocation . $this->dbName;
        $this->preserveChanges    = isset($settings['preserve_changes']) ? $settings['preserve_changes'] : $this->preserveChanges;
        $this->libLocation        = isset($settings['paths']['lib_location']) ? $settings['paths']['lib_location'] : $this->libLocation;
        $this->dbLocation         = isset($settings['paths']['db_location']) ? $settings['paths']['db_location'] : $this->dbLocation;
    }



}
