<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once 'application/lib/autoloader/AutoloaderException.php';

/**
 * BenderAutoloader class.
 *
 *
 * @package    lib
 * @subpackage lib_autoloader
 * @author     Juan Carlos Jarquin
 * @version    0.2
 */
class BenderAutoloader
{
    
    /**
     * @var boolean
     */
    protected static $registered = false;
    
    /**
     * @var BenderAutoloader
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
     *
     * @var boolean
     */
    protected $useIncludePaths = false;
    
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
     * @param boolean [OPTIONAL] $useIncludePaths
     * @param array [OPTIONAL] $dirs
     */
    protected function __construct($cacheFile = null, $useIncludePaths = false, $dirs = NULL)
    {
        if (! is_null($cacheFile))
        {
            $this->cacheFile = $cacheFile;
            $this->useIncludePaths = $useIncludePaths;
            if($this->useIncludePaths == false && $dirs == NULL)
              $dirs = array('.');
            $this->dirs = $dirs;
            if (!is_writable(dirname($this->cacheFile)))
              throw new AutoloaderException( dirname($this->cacheFile). ' folder is not writable ');
        }
        $this->loadCache();
    }
    
    /**
     * Retrieves the singleton instance of this class.
     *
     * @param  string $cacheFile  The file path to save the cache
     * @param boolean [OPTIONAL] $useIncludePaths
     * @param array [OPTIONAL] $dirs
     * @return BenderAutoloader A BenderAutoloader implementation instance.
     */
    static public function getInstance($cacheFile = null, $useIncludePaths = false, $dirs = NULL)
    {
        if (! isset(self::$instance))
        {
            self::$instance = new BenderAutoloader($cacheFile,$useIncludePaths, $dirs);
        }
        
        return self::$instance;
    }
    
    /**
     * Register BenderAutoloader in spl autoloader.
     *
     * @return void
     */
    static public function register()
    {
        if (self::$registered)
        {
            return;
        }
        
        ini_set('unserialize_callback_func', 'spl_autoload_call');
        if (false === spl_autoload_register(array(self::getInstance(), 'autoload')))
        {
            throw new AutoloaderException(sprintf('Unable to register %s::autoload as an autoloading method.', get_class(self::getInstance())));
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
     * Check if we have a class in our proyect
     */
    public function hasClass($className, $revalidate = false)
    {
      if (isset($this->classes[$className]))
        return true;
      else if($revalidate)
      {
         $this->cacheChanged = true;
         $this->saveCache();
         return $this->hasClass($className,false);
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
            $this->parseIncludePaths();
            file_put_contents($this->cacheFile, serialize($this->classes));
            chmod($this->cacheFile, 0777);
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
        $this->dirs = ($this->useIncludePaths) ? explode(PATH_SEPARATOR, get_include_path()) : $this->dirs;
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
                    if (is_dir($directory . '/' . $file) && !preg_match("/^\\./", $file))
                    {
                        $this->parseDirectory($directory . '/' . $file);
                    }
                    if (preg_match("/.php$/", $file))
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


