<?php

/**
 * ProjectAutoloader class.
 *
 *
 * @package    Project
 * @subpackage Project_Autoloader
 * @author     Juan Carlos Jarquin
 * @version    0.1
 */
class ProjectAutoloader
{
    
    /**
     * @var boolean
     */
    protected static $registered = false;
    
    /**
     * @var  ProjectAutoloader
     */
    protected static $instance = null;
    
    /**
     * @var string
     */
    protected $cacheFile = null;
    
    /**
     * @var boolean
     */
    protected $cacheLoaded = false;
    
    /**
     * @var string
     */
    protected $cacheChanged = false;
    
    /**
     * @var mixed
     */
    protected $dirs = array();
    
    /**
     * @var mixed
     */
    protected $classes = array();
    
    /**
     * Constructor de la clase
     * @param string $cacheFile
     */
    protected function __construct($cacheFile = null)
    {
        if (! is_null($cacheFile))
        {
            $this->cacheFile = $cacheFile;
        }
        $this->loadCache();
    }
    
    /**
     * Retrieves the singleton instance of this class.
     *
     * @param  string $cacheFile  The file path to save the cache
     *
     * @return ProjectAutoloader   A ProjectAutoloader implementation instance.
     */
    static public function getInstance($cacheFile = null)
    {
        if (! isset(self::$instance))
        {
            self::$instance = new ProjectAutoloader($cacheFile);
        }
        
        return self::$instance;
    }
    
    /**
     * Register ProjectAutoloader in spl autoloader.
     *
     * @return void
     */
    static public function register()
    {
        if (self::$registered)
        {
            return;
        }
        
        //ini_set('unserialize_callback_func', 'spl_autoload_call');
        if (false === spl_autoload_register(array(self::getInstance(), 'autoload')))
        {
            throw new Exception(sprintf('Unable to register %s::autoload as an autoloading method.', get_class(self::getInstance())));
        }
        self::$registered = true;
    }
    
    /**
     * Unregister ProjectAutoloader from spl autoloader.
     *
     * @return void
     */
    static public function unregister()
    {
        spl_autoload_unregister(array(self::getInstance(), 'autoload'));
        self::$registered = false;
    }
    
    /**
     * Handles autoloading of classes.
     *
     * @param  string  A class name.
     * @param  boolean $first
     * @return boolean Returns true if the class has been loaded
     */
    public function autoload($class, $first = true)
    {
        // class already exists
        if (class_exists($class, false) || interface_exists($class, false))
        {
            return true;
        }
        
        if (! $this->cacheLoaded)
            $this->loadCache();
            
        // we have a class path, let's include it
        if (isset($this->classes[$class]))
        {
            require ($this->classes[$class]);
            return true;
        } else if ($first == true)
        {
            $this->cacheChanged = true;
            $this->saveCache();
            $this->autoload($class, false);
        }
        
        return false;
    }
    
    /**
     * Loads the cache.
     */
    public function loadCache()
    {
        if (! $this->cacheFile || ! is_readable($this->cacheFile))
        {
            return;
        }
        $this->classes = unserialize(file_get_contents($this->cacheFile));
        $this->cacheLoaded = true;
        $this->cacheChanged = false;
    }
    
    /**
     * Saves the cache.
     */
    public function saveCache()
    {
        if ($this->cacheChanged)
        {
            if (is_writable(dirname($this->cacheFile)))
            {
                $this->parseIncludePaths();
                file_put_contents($this->cacheFile, serialize($this->classes));
                chmod($this->cacheFile, 0777);
            }
            $this->cacheChanged = false;
        }
    }
    
    /**
     * Removes the cache.
     */
    public function removeCache()
    {
        @unlink($this->cacheFile);
    }
    
    /**
     * 
     */
    public function parseIncludePaths()
    {
        $this->dirs = explode(PATH_SEPARATOR, get_include_path());
        foreach ( $this->dirs as $dir )
        {
            $this->parseDirectory($dir);
        }
    }
    
    /**
     * @param string $directory
     */
    public function parseDirectory($directory)
    {
        if (is_dir($directory))
        {
            if (false != ($handle = opendir($directory)))
            {
                while ( false !== ($file = readdir($handle)) )
                {
                    if (is_dir($directory . '/' . $file) && ! eregi("^\\.", $file))
                    {
                        $this->parseDirectory($directory . '/' . $file);
                    }
                    if (eregi(".php$", $file))
                        $this->addFile($directory . '/' . $file);
                }
                closedir($handle);
            }
        }
    }
    
    /**
     * Adds a file to the autoloading system.
     *
     * @param string  A file path
     * @param Boolean Whether to register those files as single entities (used when reloading)
     */
    public function addFile($file)
    {
        if (! is_file($file))
        {
            return;
        }
        $classes = array();
        preg_match_all('~^\s*(?:abstract\s+|final\s+)?(?:class|interface)\s+(\w+)~mi', file_get_contents($file), $classes);
        foreach ( $classes[1] as $class )
        {
            $this->classes[$class] = $file;
        }
    }

}









