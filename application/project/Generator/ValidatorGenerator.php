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
        
        
        
        $this->loopFields();
        
        
        
        $this->fileContent = $this->template->fetch('validator');
    }
    
    /**
     * Loop over fields
     */
    private function loopFields()
    {
      $fields = $this->table->getFields();
      while($fields->valid())
      {
        $field = $fields->current();
        
        $this->template->assignBlock('field', array(
                    'simpleName' => $field->getSimpleName(), 
                    'getter' => $field->getCompleteGetterName()
        ));
        
        for($i = 0; $i<3; $i++)
        {
          $this->template->assignBlock('field.validator',array(
            'construct' => 'Constructor'.$i.'()'
          ));
        }
        
        
        $fields->next();
      }
      
    }
}
