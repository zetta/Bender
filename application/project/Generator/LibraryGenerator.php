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
class LibraryGenerator extends ModelGenerator
{
    /**
     * Constructor
     *
     * @param array $settings
     * @return LibraryGenerator
     */
    public function LibraryGenerator($settings)
    {
        $this->settings = $settings;
        $this->addHeaderInformation();
    }
    
    /**
     * Genera el Collection del objeto y lo almacena para su posterior uso
     * @param string $libraryName
     */
    public function createLibrary($libraryName)
    {
        CommandLineInterface::getInstance()->printSection('Generator', 'Creating ' . $libraryName, 'NOTE');
        $this->template->set_filenames(array('library' => 'Library/'.$libraryName));
        $privateCriteria = isset($this->settings['private_criteria']) ? $this->settings['private_criteria'] : false ; 
        $criteriaBlocK = ($privateCriteria) ? 'privateCriteria' : 'publicCriteria';
        $this->template->showBlock($criteriaBlocK);
        $this->fileContent = $this->template->fetch('library');
    }
}
