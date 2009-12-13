<?php
/**
 * CollectionGenerator
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
    public function create()
    {
        CommandLineInterface::getInstance()->printSection('Generator', 'Creating ' . $this->object.'Collection', 'INFO');
        $this->template->set_filenames(array('collection' => 'Model/Collection'));
        
        $this->template->assign('libUtilsLocation', BenderSettings::getInstance()->getLibUtilsLocation());
        if($this->table->hasPrimaryField())
        {
        	$this->template->showBlock('hasPrimaryField');
        	$this->template->assign('primaryKeyGetter', $this->table->getPrimaryField()->getGetterName());
        	$this->template->assign('primaryKeyPhpName', $this->table->getPrimaryField()->getPhpName());
        }
        else 
        {
        	$this->template->showBlock('noPrimaryField');
        }
        
        $this->fileContent = $this->template->fetch('collection');
    }
}
