<?php
/**
 * {{ brandName }}
 *
 * {{ description }}
 *
 * @category   lib
 * @package    lib_db
 * @copyright  {{ copyright }}
 * @author    zetta
 * @version    {{ version }} SVN: $Id$
 */

{% if flags["add-includes"] %}
/**
 * Dependences
 */
require_once 'lib/controller/BaseController.php';

{% endif %}
/**
 * Clase abstracta para los CRUDS
 * @category   lib
 * @package    lib_db
 * @copyright  {{ copyright }}
{% if showBenderSignature %}
 * @copyright  {{ benderSignature }}
 * @author     zetta 
{% endif %}
 * @version    {{ version }} SVN: $Revision$
 */
abstract class CrudController extends BaseController
{
    
    /**
     * list all objects
     */
    abstract public function listAction();
    
    /**
     * delete an object
     */
    abstract public function deleteAction();
    
    /**
     * Form to edit an object
     */
    abstract public function editAction();
    
    /**
     * Create an Object
     */
    abstract public function createAction();
    
    /**
     * Update an Object
     */
    abstract public function updateAction();
    
}


