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
    
    private $libraryName = '';
    
    /**
     * Genera el Collection del objeto y lo almacena para su posterior uso
     */
    public function create()
    {
        CommandLineInterface::getInstance()->printSection('Generator', 'Creating ' . $this->libraryName, 'NOTE');
        $this->template->set_filenames(array('library' => 'Library/'.$this->libraryName));
        $criteriaBlocK = ($this->benderSettings->isPrivateCriteria()) ? 'privateCriteria' : 'publicCriteria';
        $this->template->showBlock($criteriaBlocK);
        if( $this->benderSettings->getUseBehaviors())
            $this->template->showBlock('useBehaviors');
        $this->fileContent = $this->template->fetch('library');
    }
    
    public function createLibrary($libraryName)
    {
      $this->libraryName = $libraryName;
      $this->create();
    }
}
