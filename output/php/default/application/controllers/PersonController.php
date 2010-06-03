<?php
/**
 * Bender
 *
 * Our Simple Models
 *
 * @category   Application
 * @package    Application_Controllers
 * @copyright  Copyright (c) 2008-2010 Bender Modeler (http://www.ctrl-zetta.com/#code)
 * @author     <zetta> <chentepixtol>, $LastChangedBy$
 * @version    1.0.0 SVN: $Id$
 */

/**
 * PersonController (CRUD for the Person Objects)
 *
 * @category   Project
 * @package    Project_Controllers
 * @copyright  Copyright (c) 2008-2010 Bender Modeler (http://www.ctrl-zetta.com/#code) 
 * @copyright  This File has been proudly generated by Bender (http://code.google.com/p/bender-modeler/). <chentepixtol> <zetta>
 * @author     <zetta> & <chentepixtol>
 * @version    1.0.0 SVN: $Revision$
 */
class PersonController extends CrudController
{
    
    /**
     * alias for the list action
     */
    public function indexAction()
    {
        $this->_forward('list');
    }
    
    /**
     * List the objects Person actives
     */
    public function listAction()
    {
        $this->view->persons = PersonCatalog::getInstance()->getActives();
        $this->setTitle('List the Person');
    }
    
    /**
     * delete an Person
     */
    public function deleteAction()
    {
        $personCatalog = PersonCatalog::getInstance();
        $idPerson = $this->getRequest()->getParam('idPerson');
        $person = $personCatalog->getById($idPerson);
        $personCatalog->deactivate($person);
        $this->setFlash('ok','Successfully removed the Person');
        $this->_redirect('person/list');
    }
    
    /**
     * Form for edit an Person
     */
    public function editAction()
    {
        $personCatalog = PersonCatalog::getInstance();
        $idPerson = $this->getRequest()->getParam('idPerson');
        $person = $personCatalog->getById($idPerson);
        $post = array(
            'id_person' => $person->getIdPerson(),
            'first_name' => $person->getFirstName(),
            'last_name' => $person->getLastName(),
        );
        $this->view->post = $post;
        $this->setTitle('Edit Person');
    }
    
    /**
     * Create an Person
     */
    public function createAction()
    {   
        $personCatalog = PersonCatalog::getInstance();
        $firstName = utf8_decode($this->getRequest()->getParam('first_name'));
        $lastName = utf8_decode($this->getRequest()->getParam('last_name'));
        $person = PersonFactory::create($firstName, $lastName);
        $personCatalog->create($person);  
        $this->view->setTpl('_row');
        $this->view->setLayoutFile(false);
        $this->view->person = $person;
    }
    
    /**
     * Update an Person
     */
    public function updateAction()
    {
        $personCatalog = PersonCatalog::getInstance();
        $idPerson = $this->getRequest()->getParam('idPerson');
        $person = $personCatalog->getById($idPerson);
        $person->setFirstName($this->getRequest()->getParam('first_name'));
        $person->setLastName($this->getRequest()->getParam('last_name'));
        $personCatalog->update($person);
        $this->setFlash('ok','Successfully edited the Person');
        $this->_redirect('person/list');
    }
    
}
