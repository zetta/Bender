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
class ExceptionGenerator extends ModelGenerator
{
    /**
     * Genera el Collection del objeto y lo almacena para su posterior uso
     */
    public function create()
    {
        CommandLineInterface::getInstance()->printSection('Generator', 'Creating ' . $this->object.'Exception', 'INFO');
        $this->template->set_filenames(array('exception' => 'Model/Exception'));
        $this->fileContent = $this->template->fetch('exception');
    }
}
