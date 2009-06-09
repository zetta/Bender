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
class CollectionGenerator extends ModelGenerator
{
    /**
     * Genera el Collection del objeto y lo almacena para su posterior uso
     */
    public function createCollection()
    {
        CommandLineInterface::getInstance()->printSection('Generator', 'Creating ' . $this->object.'Collection', 'NOTE');
        $this->template->set_filenames(array('collection' => 'Model/Collection'));
        $this->template->assign('className', $this->object);
        $this->template->assign('classVar', $this->getLowerObject());
        $this->template->assign('collection', $this->object . 'Collection');
        $this->fileContent = $this->template->fetch('collection');
    }
}
