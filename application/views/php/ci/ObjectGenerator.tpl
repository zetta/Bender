<?php 
/**
 * {{ Class }}, Code Igniter Model
 *
 * @category   lib
 * @package    lib_models
 * @subpackage lib_models_beans
 * @copyright  {{ copyright }} 
 * @author     {{ author }}
{% if showBenderSignature %}
 * @copyright  {{ benderSignature }}
 * @author     <zetta> & <chentepixtol>
{% endif %}
 * @version    {{ version }} SVN: $Revision$
 */
class {{ Class }} extends Model 
{

{% for field in fields  %}
    var ${{ field.getVarName() }} = '';
{% endfor %}
 
    function {{ Class }}()
    {
        //Call the Model Constructor
        parent::Model();
    }
    
    /**
     * Obtiene los 10 últimos registros de la tabla
     */
    function get_last_ten_entries()
    {
        $query = $this->db->get('{{ table.getTableName() }}', 10);
        return $query->result();
    }

    /**
     * Inserta un registro a la tabla
     */
    function insert_entry()
    {
{% for field in nonPrimaryFields %}
        $this->{{ field.getName() }} = $this->input->post('{{ field.getName() }}'):
{% endfor %}
        $this->db->insert('{{ table.getTableName() }}', $this);
    }

{% if table.hasPrimaryField() %}
    /**
     * Actualiza un registro de la tabla
     */
    function update_entry()
    {
{% for field in nonPrimaryFields %}
        $this->{{ field.getName() }} = $this->input->post('{{ field.getName() }}'):
{% endfor %}
        $this->db->update('{{ table.getTableName() }}', $this, array('{{ table.getPrimaryField().getName() }}' => $this->input->post('{{ table.getPrimaryField().getName() }}') ));
    }
{% endif %}

    /**
     * Obtiene todos los registros de la tabla
     * @return array 
     */
    function getAll() {
        $q = $this->db->get('{{ table.getTableName() }}');
        if($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
    
    
}


