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
    public function createException()
    {
        CommandLineInterface::getInstance()->printSection('Generator', 'Creating ' . $this->object.'Exception', 'NOTE');
        $this->template->set_filenames(array('exception' => 'Model/Exception'));
        $this->template->assign('className', $this->object);
        $this->template->assign('classVar', $this->getLowerObject());
        $this->template->assign('exception', $this->object . 'Exception');
        $this->fileContent = $this->template->fetch('exception');
    }
}
