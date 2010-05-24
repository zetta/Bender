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
     * @var boolean
     */
    private $populated = false;

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
     * Determina si queremos que se guarden los cambios hechos a los models
     * @var boolean
     */
    private $preserveChanges = false;

    /**
     * Codificación utilizada en los archivos que se generarán
     * @var string
     */
    private $encoding = 'UTF-8';

    /**
     * Determina que archivo de schema se utilizará
     * @var string
     */
    private $schemaFile = 'default';

    /**
     * schema
     * @var Array
     */
    private $schema = array();
    
    /**
     * Determina si los archivos generados llevarán la leyenda que diga que fueron generados con bender
     * @var boolean
     */
    private $addBenderSignature = true;

    /**
     * Nombre del autor de los archivos
     * @var string
     */
    private $author = '';

    /**
     * Pon aqui el nombre de tu empresa, organizacion o cosa =P
     * @var string
     */
    private $brandName = '';

    /**
     * un pequeño copyrigt o algo asi =P
     * @var string
     */
    private $copyright = '';

    /**
     * Descripción del sistema que generas, o algo por el estilo
     * @var string
     */
    private $description = '';

    /**
     * @var string
     */
    private $dsn = "";


    /**
     * Nombre de usuario a utilizar en la conexión a la base de datos mysql
     * @var string
     */
    private $username = '';

    /**
     * Password
     * @var string
     */
    private $password = '';

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
     * Constructor de la clase
     * @return BenderSettings
     */
    private function BenderSettings()
    {
        if(!$this->populated)
        $this->populate();
    }

    /**
     * Guarda las configuraciones
     * @param Array $settings
     */
    private function populate()
    {
        $loader = sfYaml::load('application/config/settings.yml');
        $out = CommandLineInterface::getInstance();
        $out->printSection('Settings','Loading Settings');
        $this->fetchFrom($loader);
        $this->replaceFromFlags();
        $this->fetchSchema();
        $this->populated = true;
    }

    /**
     * Iterate over the values
     * @param array $vars
     * @throws InvalidArgumentException
     */
    private function fetchFrom(array $vars)
    {
        if(!is_array($vars))
           throw new InvalidArgumentException("Can't iterate over that argument");
        foreach ($vars as $name => $var)
        {
            if( is_array($var) )
            {
                $this->fetchFrom($var);
            }
            else
            {
                $this->store($name, $var);
            }
        }
    }
    
    /**
     * Store a value
     * @param string $name
     * @param mixed $var
     */
    private function store($name,$var)
    {
        $field = Formatter::underScoreToCamelCase($name,$var);
        $this->{$field} = $var;
    }
    
    /**
     * Obtains the flags and replace settings
     */
    private function replaceFromFlags()
    {
      $flags = array();
      foreach (BenderRequest::getInstance()->getFlags() as $flag => $value)
      {
          if( $value )
            $flags[Formatter::slugToUnderScore($flag)] = $value;
      }
      $this->fetchFrom($flags);
    }

    /**
     * Obtiene los valores del archivo schema
     */
    private function fetchSchema()
    {
        $loader = sfYaml::load("application/config/{$this->schemaFile}.schema.yml");
        $this->schema = (isset($loader['schema']) && is_array($loader)) ? $loader['schema'] : array(); 
    }
    
    public function getDsn()
    {
        return $this->dsn;
    }
    
    public function getUsername()
    {
        return $this->username;
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    public function getSchemaFile()
    {
        return $this->schemaFile;
    }
    
    /**
     * @return Array
     */
    public function getSchema()
    {
        return $this->schema;
    }
    
    /**
     * return Array
     */
    public function getObjectSchema($name)
    {
        return isset($this->schema[$name]) ? $this->schema[$name] : null; 
    }
    
    
    public function getCopyright()
    {
      return $this->copyright;
    }
    
    public function getBrandName()
    {
      return $this->brandName;
    }
    
    public function getDescription()
    {
      return $this->description;
    }
    
    public function getAuthor()
    {
      return $this->author;
    }
    
    public function addBenderSignature()
    {
      return $this->addBenderSignature;
    }
    
    public function getEncoding()
    {
      return $this->encoding;
    }

}

