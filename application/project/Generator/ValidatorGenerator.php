<?php
/**
 * BeanGenerator
 * @author Juan Carlos Jarquin
 */

/**
 * BaseGenerator 
 */
require_once "application/project/Generator/ModelGenerator.php";

/**
 * Clase que genera los Collections
 */
class ValidatorGenerator extends ModelGenerator
{
    /**
     * Genera el Validator y lo almacena para su posterior uso
     */
    public function create()
    {
        CommandLineInterface::getInstance()->printSection('Generator', 'Creating ' . $this->object.'Validator', 'INFO');
        $this->template->set_filenames(array('validator' => 'Model/Validator'));
        
        
        
        //$this->loop();
        
        
        
        $this->fileContent = $this->template->fetch('validator');
    }
    
    
    //public function 
}
