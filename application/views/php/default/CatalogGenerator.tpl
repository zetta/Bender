{% extends "_baseModel.tpl" %}
{% block body %}
{% if flags["add-includes"] %}

/**
 * Dependences
 */
{% if table.extends() %}
{# requiring the parent catalog #}
require_once "{{ route.getObject('CatalogGenerator',  table.getExtendedTable()  ) }}";
{% else %}
{# requiring the base catalog #}
require_once "{{ route.getLib('CatalogLibraryGenerator') }}";
{% endif %}
require_once "{{ route.getObject('BeanGenerator',  table  ) }}";
require_once "{{ route.getObject('ExceptionGenerator',  table  ) }}";
require_once "{{ route.getObject('CollectionGenerator',  table  ) }}";
{% if flags["use-validators"] %}
require_once "{{ route.getObject('ValidatorGenerator',  table  ) }}";
{% endif %}
{% if flags["use-factories"] %}
require_once "{{ route.getObject('FactoryGenerator',  table  ) }}";
{% endif %}
{% endif %}

/**
 * Singleton {{ Catalog }} Class
 *
 * @category   lib
 * @package    lib_models
 * @subpackage lib_models_catalogs
 * @copyright  {{ copyright }}
{% if showBenderSignature %}
 * @copyright  {{ benderSignature }}
 * @author     zetta & chentepixtol
{% endif %}
 * @version    {{ version }} SVN: $Revision$
 */
class {{ Catalog }} extends {% if table.extends() %}{{ table.getExtendedTable().getObject() }}{% endif %}Catalog
{

    /**
     * Singleton Instance
     * @var {{ Catalog }}
     */
    static protected $instance = null;

{% if flags["use-validators"] %}

    /**
     * Validador
     * @var {{ Validator }}
     */
    private $validator;
{% endif %}

    /**
     * Método para obtener la instancia del catálogo
     * @return {{ Catalog }}
     */
    public static function getInstance()
    {
        if (!isset(self::$instance))
        {
          self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor de la clase {{ Catalog }}
     * @return {{ Catalog }}
     */
    protected function {{ Catalog }}()
    {
{% if flags["use-validators"] %}
        $this->validator = new {{ Validator }}();
{% endif %}
        parent::{% if table.extends() %}{{ table.getExtendedTable().getObject() }}{% endif %}Catalog();
{% if flags["use-behaviors"] %}
{% for key,behavior in table.getBehaviors() %}
        $this->addObserver(new {{ key }}Behavior({{ formatter.getArrayString( behavior ) }})); 
{% endfor %}
{% endif %}
    }
{% if table.hasPrimaryField() %}

    /**
     * Metodo para agregar un {{ Bean }} a la base de datos
     * @param {{ Bean }} ${{ bean }} Objeto {{ Bean }}
     */
    public function create(${{ bean }})
    {
        if(!(${{ bean }} instanceof {{ Bean }}))
            throw new {{ Exception }}("passed parameter isn't a {{ Bean }} instance");
{% if flags["use-validators"] %}
        $this->validator->validate(${{ bean }});
{% endif %}
{% if flags["use-behaviors"] %}
        $this->notifyObservers(${{ bean }}, Catalog::EVENT_CREATE);
{% endif %}
        try
        {
{% if table.extends() %}
            if(!${{ bean }}->{{ table.getExtendedTable().getPrimaryField().getGetterName() }}())
              parent::create(${{ bean }});
{% endif %}
            $data = array(
{% for field in nonPrimaryFields %}
                '{{ field.getName() }}' => ${{ bean }}->{{ field.getGetterName() }}(),
{% endfor %}
            );
            $data = array_filter($data, 'Catalog::notNull');
            $this->db->insert({{ Bean }}::TABLENAME, $data);
{% if table.hasPrimaryField() %}
            ${{ bean }}->{{ table.getPrimaryField().getSetterName() }}($this->db->lastInsertId());
{% endif %}
        }
        catch(Exception $e)
        {
            throw new {{ Exception }}("The {{ Bean }} can't be saved \n" . $e->getMessage());
        }
    }

    /**
     * Metodo para Obtener los datos de un objeto por su llave primaria
     * @param {{ table.getPrimaryField().getDataType() }} ${{ table.getPrimaryField().getVarName() }}
     * @param boolean $throw
     * @return {{ Bean }}|null
     */
    public function getById(${{ table.getPrimaryField().getVarName() }}, $throw = false)
    {
        try
        {
            $criteria = new Criteria();
            $criteria->add({{ Bean }}::{{ table.getPrimaryField().getConstantName() }}, ${{ table.getPrimaryField().getVarName() }}, Criteria::EQUAL);
            $new{{ Bean }} = $this->getByCriteria($criteria)->getOne();
        }
        catch(Exception $e)
        {
            throw new {{ Exception }}("Can't obtain the {{ Bean }} \n" . $e->getMessage());
        }
        if($throw && null == $new{{ Bean }})
            throw new {{ Exception }}("The {{ Bean }} at ${{ table.getPrimaryField().getVarName() }} not exists ");
        return $new{{ Bean }};
    }
    
    /**
     * Metodo para Obtener una colección de objetos por varios ids
     * @param array $ids
     * @return {{ Collection }}
     */
    public function getByIds(array $ids)
    {
        if(null == $ids) return new {{ Collection }}();
        try
        {
            $criteria = new Criteria();
            $criteria->add({{ Bean }}::{{ table.getPrimaryField().getConstantName() }}, $ids, Criteria::IN);
            ${{ collection }} = $this->getByCriteria($criteria);
        }
        catch(Exception $e)
        {
            throw new {{ Exception }}("{{ Collection }} can't be populated\n" . $e->getMessage());
        }
        return ${{ collection }};
    }

    /**
     * Metodo para actualizar un {{ Bean }}
     * @param {{ Bean }} ${{ bean }} 
     */
    public function update(${{ bean }})
    {
        if(!(${{ bean }} instanceof {{ Bean }}))
            throw new {{ Exception }}("passed parameter isn't a {{ Bean }} instance");
{% if flags["use-validators"] %}
        $this->validator->validate(${{ bean }});
{% endif %}
        try
        {
            $where[] = "{{ table.getPrimaryField().getName() }} = '{${{ bean }}->{{ table.getPrimaryField().getGetterName() }}()}'";
            $data = array(
{% for field in nonPrimaryFields %}
                '{{ field.getName() }}' => ${{ bean }}->{{ field.getGetterName() }}(),
{% endfor %}
            );
            $data = array_filter($data, 'Catalog::notNull');
            $this->db->update({{ Bean }}::TABLENAME, $data, $where);
{% if table.extends() %}
            parent::update(${{ bean }});
{% endif %}
        }
        catch(Exception $e)
        {
            throw new {{ Exception }}("The {{ Bean }} can't be updated \n" . $e->getMessage());
        }
    }
    
    /**
     * Metodo para guardar un {{ bean }}
     * @param {{ Bean }} ${{ bean }}
     */	
    public function save(${{ bean}})
    {
        if(!(${{ bean }} instanceof {{ Bean }}))
            throw new {{ Exception }}("passed parameter isn't a {{ Bean }} instance");
        if(null != ${{ bean}}->{{ table.getPrimaryField().getGetterName() }}())
            $this->update(${{ bean}});
        else
            $this->create(${{ bean}});
    }

    /**
     * Metodo para eliminar un {{ bean }}
     * @param {{ Bean }} ${{ bean }}
     */
    public function delete(${{ bean }})
    {
        if(!(${{ bean }} instanceof {{ Bean }}))
            throw new {{ Exception }}("passed parameter isn't a {{ Bean }} instance");
        $this->deleteById(${{ bean }}->{{ table.getPrimaryField().getGetterName() }}());
{% if table.extends() %}
        parent::delete(${{ bean }});
{% endif %}
    }

    /**
     * Metodo para eliminar un {{ Bean }} a partir de su Id
     * @param int ${{ table.getPrimaryField().getVarName() }}
     */
    public function deleteById(${{ table.getPrimaryField().getVarName() }})
    {
        try
        {
            $where = array($this->db->quoteInto('{{ table.getPrimaryField().getName() }} = ?', ${{ table.getPrimaryField().getVarName() }}));
            $this->db->delete({{ Bean }}::TABLENAME, $where);
        }
        catch(Exception $e)
        {
            throw new {{ Exception }}("The {{ Bean }} can't be deleted\n" . $e->getMessage());
        }
    }
    
    /**
     * Metodo para eliminar varios {{ Bean }} a partir de su Id
     * @param array $ids
     */
    public function deleteByIds(array $ids)
    {
        try
        {
            $criteria = new Criteria();
            $criteria->add({{ Bean }}::{{ table.getPrimaryField().getConstantName() }}, $ids, Criteria::IN);
            $this->db->delete({{ Bean }}::TABLENAME, array($criteria->createSql()));
        }
        catch(Exception $e)
        {
            throw new {{ Exception }}("Can't delete that\n" . $e->getMessage());
        }
    }
    
    /**
     * Metodo para Obtener todos los ids en un arreglo
     * @return array
     */
    public function retrieveAllIds()
    {
        return $this->getIdsByCriteria(new Criteria());
    }

    /**
     * Metodo para obtener todos los id de {{ Bean }} por criterio
     * @param Criteria $criteria
     * @return array Array con todos los id de {{ Bean }} que encajen en la busqueda
     */
    public function getIdsByCriteria(Criteria $criteria = null)
    {
        $criteria = (null === $criteria) ? new Criteria() : $criteria;
        return $this->getCustomFieldByCriteria({{ Bean }}::{{ table.getPrimaryField().getConstantName() }}, $criteria);
    }
{% endif %}

    /**
     * Metodo para obtener un campo en particular de un {{ Bean }} dado un criterio
     * @param string $field
     * @param Criteria $criteria
     * @param $distinct
     * @return array Array con el campo de los objetos {{ Bean }} que encajen en la busqueda
     */
    public function getCustomFieldByCriteria($field, Criteria $criteria = null, $distinct = false)
    { 
        $criteria = (null === $criteria) ? new Criteria() : $criteria;
        $distinct = $distinct ? 'DISTINCT' : '';
        try
        {
            $sql = "SELECT {$distinct} {$field}
                    FROM ".{{ Bean }}::TABLENAME."
{% for i in 1..5 %}
{% if table.extends() %}
                      INNER JOIN ".{{ table.getExtendedTable().getObject() }}::TABLENAME." USING ( {{ table.getExtendedTable().getPrimaryField().getName() }} )
{% endif %}
{% set table as table.getExtendedTable() %}
{% endfor %}
                    WHERE  " . $criteria->createSql();
            $result = $this->db->fetchCol($sql);
        } catch(Zend_Db_Exception $e)
        {
            throw new {{ Exception }}("No se pudieron obtener los fields de objetos {{ Bean }}\n" . $e->getMessage());
        }
        return $result;
    }

    /**
     * Metodo que regresa una coleccion de objetos {{ Bean }} 
     * dependiendo del criterio establecido
     * @param Criteria $criteria
     * @return {{ Collection }} ${{ collection }}
     */
    public function getByCriteria(Criteria $criteria = null)
    {
        $criteria = (null === $criteria) ? new Criteria() : $criteria;
        $this->db->setFetchMode(Zend_Db::FETCH_ASSOC);
        try 
        {
            $sql = "SELECT * FROM ".{{ Bean }}::TABLENAME."
{% for i in 1..5 %}
{% if table.extends() %}
                      INNER JOIN ".{{ table.getExtendedTable().getObject() }}::TABLENAME." USING ( {{ table.getExtendedTable().getPrimaryField().getName() }} )
{% endif %}
{% set table as table.getExtendedTable() %}
{% endfor %}
                    WHERE " . $criteria->createSql();
            ${{ collection }} = new {{ Collection }}();
            foreach ($this->db->fetchAll($sql) as $result){
                ${{ collection }}->append($this->get{{ Bean }}Instance($result));
            }
        }
        catch(Zend_Db_Exception $e)
        {
            throw new {{ Exception }}("Cant obtain {{ Collection }}\n" . $e->getMessage());
        }
        return ${{ collection }};
    }
    
    /**
     * Metodo que cuenta {{ Bean }} 
     * dependiendo del criterio establecido
     * @param Criteria $criteria
     * @param string $field
     * @return int $count
     */
    public function countByCriteria(Criteria $criteria = null, $field = '{{ table.getPrimaryField().getSimpleName() }}')
    {
        $criteria = (null === $criteria) ? new Criteria() : $criteria;
        try 
        {
            $sql = "SELECT COUNT( $field ) FROM ".{{ Bean }}::TABLENAME."
{% for i in 1..5 %}
{% if table.extends() %}
                      INNER JOIN ".{{ table.getExtendedTable().getObject() }}::TABLENAME." USING ( {{ table.getExtendedTable().getPrimaryField().getName() }} )
{% endif %}
{% set table as table.getExtendedTable() %}
{% endfor %}
                    WHERE " . $criteria->createSql();   
            $count = $this->db->fetchOne($sql);
        }
        catch(Zend_Db_Exception $e)
        {
            throw new {{ Exception }}("Cant obtain the count \n" . $e->getMessage());
        }
        return $count;
    }
    
    /**
     * Método que construye un objeto {{ Bean }} y lo rellena con la información del rowset
     * @param array $result El arreglo que devolvió el objeto Zend_Db despues del fetch
     * @return {{ Bean }} 
     */
    private function get{{ Bean }}Instance($result)
    {
{% if flags["use-factories"] %}
        return {{ Factory }}::createFromArray($result);
{% else %}
        $new{{ Bean }} = new {{ Bean }}();
{% for field in fields %}
        $new{{ Bean }}->{{ field.getSetterName() }}($result['{{ field.getName() }}']);
{% endfor %}
        return $new{{ Bean }};
{% endif %}
    }
{% for field in foreigns %}
  
    /**
     * Obtiene un {% if field.isUnique() %}{{ Bean }}{% else %}{{ Collection }} {% endif %} dependiendo del {{ field.getVarName() }}
     * @param {{ field.getDataType() }} ${{ field.getVarName() }} {{ field.getComment() }} 
     * @return {% if field.isUnique() %}{{ Bean }}{% else %}{{ Collection }}{% endif %} 
     */
    public function getBy{{ field.getUpperCaseName() }}(${{ field.getVarName() }})
    {
        $criteria = new Criteria();
        $criteria->add({{ Bean }}::{{ field.getConstantName() }}, ${{ field.getVarName() }}, Criteria::EQUAL);
        ${{ collection }} = $this->getByCriteria($criteria);
        return ${{ collection }}{% if field.isUnique() %}->getOne(){% endif %};
    }
{% endfor %}
{% for field in uniqueFields %}
  
    /**
     * Obtiene un {{ Bean }} dependiendo del {{ field.getVarName() }}
     * @param {{ field.getDataType() }} ${{ field.getVarName() }} {{ field.getComment() }} 
     * @return {{ Bean }} 
     */
    public function getBy{{ field.getUpperCaseName() }}(${{ field.getVarName() }})
    {
        $criteria = new Criteria();
        $criteria->add({{ Bean }}::{{ field.getConstantName() }}, ${{ field.getVarName() }}, Criteria::EQUAL);
        ${{ collection }} = $this->getByCriteria($criteria);
        return ${{ collection }}->getOne();
    }
{% endfor %}
{% if status %}

    /**
     * Metodo que regresa una coleccion de objetos {{ Bean }} con {{ status.getUpperCaseName() }} 'Active'
     * dependiendo del criterio establecido
     * @param Criteria $criteria
     * @return {{ Collection }} ${{ collection }}
     */
    public function getActives(Criteria $criteria = null)
    {
        $criteria = (null === $criteria) ? new Criteria() : $criteria;
        $criteria->add({{ Bean }}::{{ status.getConstantName() }}, {{ Bean }}::${{ status.getUpperCaseName() }}['Active'], Criteria::EQUAL);
        return $this->getByCriteria($criteria);
    }
    
    /**
     * Metodo que regresa una coleccion de objetos {{ Bean }} con {{ status.getUpperCaseName() }} 'Inactive'
     * dependiendo del criterio establecido
     * @param Criteria $criteria
     * @return {{ Collection }} ${{ collection }}
     */
    public function getInactives(Criteria $criteria = null)
    {
        $criteria = (null === $criteria) ? new Criteria() : $criteria;
        $criteria->add({{ Bean }}::{{ status.getConstantName() }}, {{ Bean }}::${{ status.getUpperCaseName() }}['Inactive'], Criteria::EQUAL);
        return $this->getByCriteria($criteria);
    }
    
    /**
     * Activate a {{ bean }}
     * @param {{ Bean }} ${{ bean }}
     */ 
    public function activate(${{ bean}})
    {
        if(!(${{ bean }} instanceof {{ Bean }}))
            throw new {{ Exception }}("passed parameter isn't a {{ Bean }} instance");
        if({{ Bean }}::${{ status.getUpperCaseName() }}['Active'] != ${{ bean }}->{{ status.getGetterName() }}())
        {
            ${{ bean }}->{{ status.getSetterName() }}({{ Bean }}::${{ status.getUpperCaseName() }}['Active']);
            $this->save(${{ bean }});
        }
    }
    
    /**
     * Deactivate a {{ bean }}
     * @param {{ Bean }} ${{ bean }}
     */ 
    public function deactivate(${{ bean}})
    {
        if(!(${{ bean }} instanceof {{ Bean }}))
            throw new {{ Exception }}("passed parameter isn't a {{ Bean }} instance");
        if({{ Bean }}::${{ status.getUpperCaseName() }}['Inactive'] != ${{ bean }}->{{ status.getGetterName() }}())
        {
            ${{ bean }}->{{ status.getSetterName() }}({{ Bean }}::${{ status.getUpperCaseName() }}['Inactive']);
            $this->save(${{ bean }});
        }
    }
{% endif %}
{% for relation in table.getManyToManyRelations() %}
{% set related as relation['related'] %}
{% set source as relation['source'] %}
{% set const as 'TABLENAME_' ~ formatter.camelCaseToUpperCase(table.getObject()) ~ '_' ~ formatter.camelCaseToUpperCase(relation['related'].getObject())  %}
{% set pk1 as table.getPrimaryField() %}
{% set pk2 as related.getPrimaryField() %}

    /**
     * Link a {{ table.getObject() }} to {{ related.getObject() }}
     * @param {{ pk1.getDataType() }} ${{ pk1.getVarName() }}
     * @param {{ pk2.getDataType() }} ${{ pk2.getVarName() }}
{% for field in source.getNonForeignKeys() %}
     * @param {{ field.getDataType() }} ${{ field.getVarName() }}
{% endfor %}
     */
    public function linkTo{{ related.getObject() }}(${{ pk1.getVarName() }}, ${{ pk2.getVarName() }}{% for field in source.getNonForeignKeys() %}, ${{ field.getVarName() }}{% endfor%})
    {
        try
        {
            $this->unlinkFrom{{ related.getObject() }}(${{ pk1.getVarName() }}, ${{ pk2.getVarName() }});
            $data = array(
                '{{ pk1.getSimpleName() }}' => ${{ pk1.getVarName() }},
                '{{ pk2.getSimpleName() }}' => ${{ pk2.getVarName() }},
{% for field in source.getNonForeignKeys() %}
                '{{ field.getSimpleName() }}' => ${{ field.getVarName() }},
{% endfor%}
            );
            $this->db->insert({{ table.getObject() }}::{{ const }}, $data);
        }
        catch(Exception $e)
        {
            throw new {{ Exception }}("Can't link {{ table.getObject() }} to {{ related.getObject() }}\n" . $e->getMessage());
        }
    }

    /**
     * Unlink a {{ table.getObject() }} from {{ related.getObject() }}
     * @param {{ pk1.getDataType() }} ${{ pk1.getVarName() }}
     * @param {{ pk2.getDataType() }} ${{ pk2.getVarName() }}
     */
    public function unlinkFrom{{ related.getObject() }}(${{ pk1.getVarName() }}, ${{ pk2.getVarName() }})
    {
        try
        {
            $where = array(
                $this->db->quoteInto('{{ pk1.getSimpleName() }} = ?', ${{ pk1.getVarName() }}),
                $this->db->quoteInto('{{ pk2.getSimpleName() }} = ?', ${{ pk2.getVarName() }}),
            );
            $this->db->delete({{ table.getObject() }}::{{ const }}, $where);
        }
        catch(Exception $e)
        {
            throw new {{ Exception }}("Can't unlink {{ table.getObject() }} to {{ related.getObject() }}\n" . $e->getMessage());
        }
    }

    /**
     * Unlink all {{ related.getObject() }} relations
     * @param {{ pk1.getDataType() }} ${{ pk1.getVarName() }}
{% for field in source.getNonForeignKeys() %}
     * @param {{ field.getDataType() }} ${{ field.getVarName() }}
{% endfor %}
     */
    public function unlinkAll{{ related.getObject() }}Relations(${{ pk1.getVarName() }}{% for field in source.getNonForeignKeys() %}, ${{ field.getVarName() }} = null{% endfor%})
    {
        try
        {
            $where = array(
                $this->db->quoteInto('{{ pk1.getSimpleName() }} = ?', ${{ pk1.getVarName() }}),
            );
{% for field in source.getNonForeignKeys() %}
            if(null != ${{ field.getVarName() }}) $where[] = $this->db->quoteInto('{{ field.getSimpleName() }} = ?', ${{ field.getVarName() }});
{% endfor %}
            $this->db->delete({{ table.getObject() }}::{{ const }}, $where);
        }
        catch(Exception $e)
        {
            throw new {{ Exception }}("Can't unlink all {{ related.getObject() }} relations \n" . $e->getMessage());
        }
    }

    /**
     * Get {{ table.getObject() }} - {{ related.getObject() }} relations by Criteria
     * @param Criteria $criteria
     * @return array
     */
    public function get{{ table.getObject() }}{{ related.getObject() }}Relations(Criteria $criteria = null)
    { 
        $criteria = (null === $criteria) ? new Criteria() : $criteria;
        $this->db->setFetchMode(Zend_Db::FETCH_ASSOC);
        try
        {
           $sql = "SELECT * FROM ". {{ table.getObject() }}::{{ const }} ."
                   WHERE  " . $criteria->createSql();
           $result = $this->db->fetchAll($sql);
        } catch(Exception $e)
        {
           throw new {{ Exception }}("Can't obtain relations by criteria\n" . $e->getMessage());
        }
        return $result;
    }

    /**
     * Get {{ Collection }} by {{ related.getObject() }}
     * @param {{ pk2.getDataType() }} ${{ pk2.getVarName() }}
{% for field in source.getNonForeignKeys() %}
     * @param [{{ field.getDataType() }}|array] ${{ field.getVarName() }}
{% endfor %}
     * @return {{ Collection }}
     */
    public function getBy{{ related.getObject() }}(${{ pk2.getVarName() }}{% for field in source.getNonForeignKeys() %}, ${{ field.getVarName() }} = null{% endfor%})
    {
        $criteria = new Criteria();
        $criteria->add('{{ pk2.getSimpleName() }}', ${{ pk2.getVarName() }}, Criteria::EQUAL);
{% for field in source.getNonForeignKeys() %}
        if(null != ${{ field.getVarName() }}) $criteria->add('{{ field.getSimpleName() }}', ${{ field.getVarName() }}, is_array(${{ field.getVarName() }}) ? Criteria::IN : Criteria::EQUAL);
{% endfor %}
        ${{ table.getLowerObject() }}{{ related.getObject() }} = $this->get{{ table.getObject() }}{{ related.getObject() }}Relations($criteria);
        $ids = array();
        foreach(${{ table.getLowerObject() }}{{ related.getObject() }} as $rs){
            $ids[] = $rs['{{ pk1.getSimpleName() }}'];
        }
        return $this->getByIds($ids);
    }
{% endfor %}

{% if table.hasPrimaryField() == FALSE %}

    /**
     * Bender cant implement this methods, make sure you have a primaryField 
     * in your "{{ table.getTableName() }}" table
     */
    public function create(${{ bean }}){ throw new Exception('Method not implemented'); }
    public function delete(${{ bean }}){ throw new Exception('Method not implemented'); }
    public function update(${{ bean }}){ throw new Exception('Method not implemented'); }
    public function getByIds($id){ throw new Exception('Method not implemented'); }
    public function getById($id){ throw new Exception('Method not implemented'); }
    public function deleteById($id){ throw new Exception('Method not implemented'); }
    public function retrieveAllIds(){ throw new Exception('Method not implemented'); }
    public function getIdsByCriteria(Criteria $criteria){ throw new Exception('Method not implemented'); }

{% endif %}

} 
 
{% endblock %}
