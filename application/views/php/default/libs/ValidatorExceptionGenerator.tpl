<?php 
/**
 * {{ brandName }}
 *
 * {{ description }}
 *
 * @category   lib
 * @package    lib_db
 * @copyright  {{ copyright }}
 * @author     {{ author }}
 * @version    {{ version }} SVN: $Id$
 */

/**
 * ValidatorException Class
 * @category   lib
 * @package    lib_db
 * @copyright  {{ copyright }}
{% if showBenderSignature %}
 * @copyright  {{ benderSignature }}
 * @author     zetta 
{% endif %}
 * @version    {{ version }} SVN: $Revision$
 */
class ValidatorException extends Exception
{

  private $errors = array();
  
  public function ValidatorException($message, $code, $errors)
  {
    parent::__construct();
    $this->errors = $errors;
  }

  
  public function getErrors()
  {
    return $this->errors;
  }

}


