<?php
/**
 * {{ brandName }}
 *
 * {{ description }}
 *
 * @category   Application
 * @package    Application_Controllers
 * @copyright  {{ copyright }}
 * @author     {{ author }}, $LastChangedBy$
 * @version    {{ version }} SVN: $Id$
 */

{% if flags["add-includes"] %}
/**
 * Dependences
 */
require_once "{{ route.getLib('CrudControllerGenerator') }}";
require_once "{{ route.getObject('CatalogGenerator', table) }}";

{% endif %}
/**
 * {{ Bean }}Controller (CRUD for the {{ Bean }} Objects)
 *
 * @category   Project
 * @package    Project_Controllers
 * @copyright  {{ copyright }} 
{% if showBenderSignature %}
 * @copyright  {{ benderSignature }}
 * @author     <zetta> & <chentepixtol>
{% endif %}
 * @version    {{ version }} SVN: $Revision$
 */
class {{ Bean }}Controller extends CrudController
{
    
    /**
     * alias for the list action
     */
    public function indexAction()
    {
        $this->_forward('list');
    }
    
    /**
     * List the objects {{ Bean }} actives
     */
    public function listAction()
    {
        $this->view->{{ bean }}s = {{ Catalog }}::getInstance()->getActives();
        $this->setTitle('List the {{ Bean }}');
    }
    
    /**
     * delete an {{ Bean }}
     */
    public function deleteAction()
    {
        ${{ catalog }} = {{ Catalog }}::getInstance();
        ${{ primaryField.getVarName() }} = $this->getRequest()->getParam('{{ primaryField.getVarName() }}');
        ${{ bean }} = ${{ catalog }}->getById(${{ primaryField.getVarName() }});
        ${{ catalog }}->deactivate(${{ bean }});
        $this->setFlash('ok','Successfully removed the {{ Bean }}');
        $this->_redirect('{{ formatter.camelCaseToSlug(table.getLowerObject()) }}/list');
    }
    
    /**
     * Form for edit an {{ Bean }}
     */
    public function editAction()
    {
        ${{ catalog }} = {{ Catalog }}::getInstance();
        ${{ primaryField.getVarName() }} = $this->getRequest()->getParam('{{ primaryField.getVarName() }}');
        ${{ bean }} = ${{ catalog }}->getById(${{ primaryField.getVarName() }});
        $post = array(
{% for field in table.getFields() %}
            '{{ field.getSimpleName()}}' => ${{ table.getLowerObject() }}->{{ field.getGetterName() }}(),
{% endfor %}
        );
        $this->view->post = $post;
        $this->setTitle('Edit {{ Bean }}');
    }
    
    /**
     * Create an {{ Bean }}
     */
    public function createAction()
    {   
        ${{ catalog }} = {{ Catalog }}::getInstance();
{% for field in nonPrimaryFields %}
        ${{ field.getVarName() }} = utf8_decode($this->getRequest()->getParam('{{ field.getSimpleName() }}'));
{% endfor %}
{% if uniqueField %}
        ${{ bean }} = ${{ catalog }}->getBy{{ uniqueField.getUpperCaseName() }}(${{ uniqueField.getVarName() }});
        if( !(${{ bean }} instanceof {{ Bean }}) )
        {
            ${{ bean }} = {{ Factory }}::create({% for field in nonPrimaryFields %}${{ field.getVarName() }}{% if loop.last == false %}, {%endif%}{% endfor %});
            ${{ catalog }}->create(${{ bean }});
        }
        ${{ catalog }}->activate(${{ bean }});
{% else %}
        ${{ bean }} = {{ Factory }}::create({% for field in nonPrimaryFields %}${{ field.getVarName() }}{% if loop.last == false %}, {%endif%}{% endfor %});
        ${{ catalog }}->create(${{ bean }});  
{% endif %}
        $this->view->setTpl('_row');
        $this->view->setLayoutFile(false);
        $this->view->{{ bean }} = ${{ bean }};
    }
    
    /**
     * Update an {{ Bean }}
     */
    public function updateAction()
    {
        ${{ catalog }} = {{ Catalog }}::getInstance();
        ${{ primaryField.getVarName() }} = $this->getRequest()->getParam('{{ primaryField.getVarName() }}');
        ${{ bean }} = ${{ catalog }}->getById(${{ primaryField.getVarName() }});
{% for field in nonPrimaryFields %}
        ${{ bean }}->{{ field.getSetterName() }}($this->getRequest()->getParam('{{ field.getSimpleName() }}'));
{% endfor %}
        ${{ catalog }}->update(${{ bean }});
        $this->setFlash('ok','Successfully edited the {{ Bean }}');
        $this->_redirect('{{ formatter.camelCaseToSlug(table.getLowerObject()) }}/list');
    }
    
}
