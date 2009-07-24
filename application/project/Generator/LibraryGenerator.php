<?php
/**
 * BeanGenerator
 * @author Juan Carlos Jarquin
 */


/**
 * Clase que genera los Collections
 */
class LibraryGenerator extends ModelGenerator
{
    /**
     * Constructor
     *
     * @return LibraryGenerator
     */
    public function LibraryGenerator()
    {
        $this->benderSettings = BenderSettings::getInstance();
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
        $criteriaBlocK = ($this->benderSettings->isPrivateCriteria()) ? 'privateCriteria' : 'publicCriteria';
        $this->template->showBlock($criteriaBlocK);
        if( $this->benderSettings->getUseBehaviors())
            $this->template->showBlock('useBehaviors');
        $this->fileContent = $this->template->fetch('library');
    }
}
