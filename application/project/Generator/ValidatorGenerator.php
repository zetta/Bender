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
    CommandLineInterface::getInstance()->printSection('Generator', 'Creating ' . $this->object . 'Validator', 'INFO');
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
    while ( $fields->valid() )
    {
      $field = $fields->current();
      $this->validates = 0;
      $this->template->assignBlock('field', array('simpleName' => $field->getSimpleName(), 'getter' => $field->getCompleteGetterName()));
      
      if (eregi('string|char|varchar', $field->getBaseDataType()) && ($field->getMaxlength() || $field->getMinlength()))
        $this->addStringLength($field->getMinlength(), $field->getMaxlength());
      
      if($field->isRequired())
        $this->addRequired();   
        
      if ($field->getType() == 'email')
        $this->addEmail();
      
      if($field->getType() == 'Zend_Date')
        $this->addZendDate();  
        
      if ($this->validates == 0)
      {
        $this->template->unAssignBlock('field');
      }
      $fields->next();
    }
  
  }
  
  /**
   * Bloque de ancho de string
   *
   * @param int $min
   * @param int $max
   */
  private function addStringLength($min, $max)
  {
    $this->template->assignBlock('field.length', array('min' => $min, 'max' => $max));
    $this->validates += 1;
  }
  
  /**
   * Bloque de email
   */
  private function addEmail()
  {
    $this->template->showBlock('field.email');
    $this->validates += 1;
  }
  
  /**
   * Bloque de email
   */
  private function addRequired()
  {
    $this->template->showBlock('field.required');
    $this->validates += 1;
  }
  
  /**
   * Bloque de email
   */
  private function addZendDate()
  {
    $this->template->showBlock('field.zenddate');
    $this->validates += 1;
  }
  
  /**
   * Used validates
   * @var int
   */
  private $validates = 0;
}
