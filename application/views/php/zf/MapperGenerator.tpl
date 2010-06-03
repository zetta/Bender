<?php 
/**
 * ZendFramework ModelMapper 
 * {{ description }}
 * {{ brandName }}
 *
 * @category   models
 * @package    models
 * @copyright  {{ copyright }}
 * @author     {{ author }}, $LastChangedBy$
{% if showBenderSignature %}
 * @copyright  {{ benderSignature }}
 * @author     zetta
{% endif %}
 * @version    {{ version }} SVN: $Id$
 * @see http://framework.zend.com/manual/en/learning.quickstart.create-model.html
 */

class {{ Mapper }}
{

    /**
     * @var string
     */
    protected $_dbTable;
 
    /**
     * dbTable setter
     * @param string|Zend_Db_Table_Abstract $dbTable
     * @return {{ Mapper }}
     */
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }
 
    /**
     * @return string
     */
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('{{ DbTable }}');
        }
        return $this->_dbTable;
    }

{% if table.hasPrimaryField() %}
    /**
     * Save the entity
     * @param {{ Class }} ${{ object }}
     */
    public function save({{ Class }} ${{ object }})
    {
        $data = array(
{% for field in fields %}
            '{{ field.getName() }}' => ${{ object }}->{{ field.getGetterName() }}(),
{% endfor %}
        );
 
        if (null === (${{ table.getPrimaryField().getVarName() }} = ${{ object }}->{{ table.getPrimaryField().getGetterName() }}())) {
            unset($data['{{ table.getPrimaryField().getName() }}']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('{{ table.getPrimaryField().getName() }} = ?' => ${{ table.getPrimaryField().getVarName() }}));
        }
    }
 
    /**
     * Find an entry
     * @param {{ table.getPrimaryField().getDataType() }} ${{ table.getPrimaryField().getVarName() }}
     * @param [optional] {{ Class }} ${{ object }}
     * @return {{ Class }}|null
     */
    public function find(${{ table.getPrimaryField().getVarName() }}, {{ Class }} ${{ object }} = null)
    {
        $result = $this->getDbTable()->find(${{ table.getPrimaryField().getVarName() }});
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $obj = (${{ object }} instanceof {{ Class }}) ? ${{ object }} : new {{ Class }};
{% for field in fields %}
        {% if loop.first == true %}$obj{% else %}    {% endif %}->{{ field.getSetterName() }}($row->{{ field.getName() }}){% if loop.last == true %};{% endif%}

{% endfor %}
        if(!(${{ object }} instanceof {{ Class }})) 
            return $obj;
    }

{% endif %}

    /**
     * Fetch all entries
     * @return array
     */
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new {{ Class }}();
{% for field in fields %}
            {% if loop.first == true %}$entry{% else %}    {% endif %}->{{ field.getSetterName() }}($row->{{ field.getName() }}){% if loop.last == true %};{% endif%}

{% endfor %}
            $entries[] = $entry;
        }
        return $entries;
    }

}


