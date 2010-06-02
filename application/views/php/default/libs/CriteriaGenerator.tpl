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
 * Clase Criteria para realizar busquedas o filtros
 * @category   lib
 * @package    lib_db
 * @copyright  {{ copyright }}
{% if showBenderSignature %}
 * @copyright  {{ benderSignature }}
 * @author     zetta 
{% endif %}
 * @version    {{ version }} SVN: $Revision$
 */
class Criteria
{
    //Comparision
    const EQUAL = '=';
    const JUST_LIKE = 'LIKE';          # table.field LIKE '{value}' 
    const LIKE = ' LIKE ';             # table.field LIKE '%{value}%'
    const LEFT_LIKE = ' LIKE';         # table.field LIKE '%{value}'
    const RIGHT_LIKE = 'LIKE ';        # table.field LIKE '{value}%'
    const NOT_LIKE = ' NOT LIKE ';     # table.field NOT LIKE '%{value}%'
    const NOT_JUST_LIKE = 'NOT LIKE';  # table.field NOT LIKE '{value}'
    const IN = 'IN';
    const NOT_IN = 'NOT IN';
    const NOT_EQUAL = '<>';
    const GREATER_THAN = '>';
    const LESS_THAN = '<';
    const GREATER_OR_EQUAL = '>=';
    const LESS_OR_EQUAL = '<=';
    const BETWEEN = 'BETWEEN';
    const IS_NULL = 'IS NULL';
    const IS_NOT_NULL = 'IS NOT NULL';
    
    //modes
    const EXCLUSIVE = " AND ";
    const INCLUSIVE = " OR ";
    
    //Mutators
    const PASSWORD = 'PASSWORD(%s)';
    const LOWER = 'LOWER(%s)';
    const UPPER = 'UPPER(%s)';
    const DATE = 'DATE(%s)';

    /**
     * El limite de filas que regresará el sql .  <code>0</code> significa que regresa todos
     * rows.
     * @var int $limit
     */
    private $limit = 0;

    /**
     * Criteria Mode
     * @var string
     */
    private $mode = Criteria::EXCLUSIVE;

    /**
     * Para comenzar a desplegar los resultados en una fila diferente a la primera
     * @var int $offset
     */
    private $offset = 0;

    /**
     * Principal arreglo de almacenamiento del objeto
     * @var mixed
     */
    private $map = array();

    /**
     * Columnas por las que se ordenará el resultado
     * @var mixed
     */
    private $orderByColumns = array();

    /**
     * Columnas por las que se agruparán los resultados
     * @var mixed
     */
    private $groupByColumns = array();

    /**
     * Devuelvel al objeto criteria a su estado natural, para que pueda ser usado nuevamente
     * @return     void
     */
    public function clear()
    {
        $this->map = array();
        $this->orderByColumns = array();
        $this->groupByColumns = array();
        $this->offset = 0;
        $this->limit = 0;
    }

    /**
     * set Criteria mode
     * @param string
     */
    public function setMode($mode)
    {
      $this->mode = $mode;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @param int $offset
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
    }

    /**
     * Guarda un criterio en la lista
     * Usese de la siguiente manera:
     *
     * <p>
     * <code>
     * $crit = new Criteria();
     * $crit->add(&quot;column&quot;, &quot;value&quot;, &quot;Criteria::EQUAL&quot;);
     * </code></p>
     *
     *
     * @param string $criteria La columna a comparar
     * @param mixed $value
     * @param string [optional] $comparison
     * @param string [optional] $mutator
     * @return Criteria $this
     */
    public function add($column, $value, $comparison = '=', $mutator = '')
    {
        if(!$comparison)
            throw new Exception('Se necesita especificar un signo de comparación para el criterio');
        
        $this->map[] = array(
            'column'=>$column, 
            'value'=>$value, 
            'comparision'=>$comparison, 
            'mutator'=>$mutator);
        
        return $this;
    }

    /**
     * Elimina un criterio en la lista
     *
     * @param string $criteria La columna a comparar
     * @return Criteria $this
     */
    public function remove($column)
    {
        foreach($this->map as $key => $criteria)
            if($criteria['column'] == $column)
                unset($this->map[$key]);
        return $this;
    }

    /**
     * Reemplaza un criterio en la lista
     *
     * @see Criteria::add
     * @param string $criteria La columna a comparar
     * @param mixed $value
     * @param string $comparison Una cadena
     * @return Criteria $this
     */
    public function replace($column, $value, $comparison)
    {
        if($value === null || $value === '')
            throw new Exception("No se puede guardar un criterio con valor vacio <strong>({$column},{$value},{$comparison})</strong>");
        if(!$comparison)
            throw new Exception('Se necesita especificar un signo de comparación para el criterio');
        foreach($this->map as &$criteria)
        {
            if($criteria['column'] == $column)
            {
                $criteria = array(
                    'column'=>$column, 
                    'value'=>$value, 
                    'comparision'=>$comparison);
            }
        }
        return $this;
    
    }

    /**
     * Genera un criterio nuevo a partir de 2 criterios ya creados
     *
     */
    public function addOr(Criteria $firstCriteria, Criteria $secondCriteria)
    {
        $this->map[] = array(
            'firstCriteria'=>$firstCriteria, 
            'secondCriteria'=>$secondCriteria, 
            'comparision'=>'OR');
    }

    /**
     * Genera un criterio nuevo a partir de 2 criterios ya creados
     *
     */
    public function addAnd(Criteria $firstCriteria, Criteria $secondCriteria)
    {
        $this->map[] = array(
            'firstCriteria'=>$firstCriteria, 
            'secondCriteria'=>$secondCriteria, 
            'comparision'=>'AND');
    }

    /**
     * GUarda una columna para ordenar los resultados
     * @param string $groupBy
     * @return Criteria
     */
    public function addGroupByColumn($groupBy)
    {
        $this->groupByColumns[] = $groupBy;
        return $this;
    }

    /**
     * @return array
     */
    public function getGroupByColumns()
    {
        return $this->groupByColumns;
    }

    /**
     * Agrega una columna para ordenar de forma ascendente
     *
     * @param string $name El nombde de la columna.
     * @return  Criteria
     */
    public function addAscendingOrderByColumn($name)
    {
        $this->orderByColumns[] = $name . ' ASC';
        return $this;
    }

    /**
     * Agrega una columna para ordenar de forma descendente
     *
     * @param string $name El nombre de la columna
     * @return Criteria The modified Criteria object.
     */
    public function addDescendingOrderByColumn($name)
    {
        $this->orderByColumns[] = $name . ' DESC';
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderByColumns()
    {
        return $this->orderByColumns;
    }

    /**
     * Determina si el mapa de criterios contiene por lo menos un criterio
     * @return boolean
     */
    public function hasKeys()
    {
        if($this->map)
            return true;
        else
            return false;
    }

    /**
     * Obtiene los indices del mapa de criterios
     * @return mixed
     */
    public function keys()
    {
        return array_keys($this->map);
    }

    /**
     * Regresa el criterio de la columna especificada
     *
     * @param string $column Column name.
     * @return mixed
     */
    public function getCriterion($column)
    {
        return isset($this->map[$column]) ? $this->map[$column] : '';
    }

    /**
     * Regresa el SQL generado a partir de los criterios especificados
     * @deprecated Utilize en su lugar Criteria::createSql() dado que php tira un error fatal
     *             cuando el método toString tira una excepción
     * @since Rev 9
     * @return string
     */
    public function __toString()
    {
        return $this->createSql();
    }

    /**
     * Regresa el SQL generado a partir de los criterios especificados
     * @throws Exception
     * @return string
     */
    public function createSql()
    {
        try
        {
            $whereExpression = array();
            foreach($this->keys() as $key)
            {
                $criterion = $this->getCriterion($key);
                if(isset($criterion['firstCriteria']) && isset($criterion['secondCriteria']))
                {
                    $whereExpression[] = "({$criterion['firstCriteria']}) {$criterion['comparision']} ({$criterion['secondCriteria']})";
                }
                else 
                    if(is_array($criterion['value']))
                    {
                        $in = DBAO::Database()->quote($criterion['value']);
                        if($criterion['comparision'] == self::BETWEEN)
                        {
                            $left = DBAO::Database()->quote($criterion['value'][0]);
                            $right = DBAO::Database()->quote($criterion['value'][1]);
                            $whereExpression[] = "\n{$criterion['column']} {$criterion['comparision']} {$left} AND {$right}  ";
                        }
                        else
                            $whereExpression[] = "\n{$criterion['column']} {$criterion['comparision']} ({$in})";
                    }
                    else
                    {
                      switch ($criterion['comparision'])
                      {
                        case self::LIKE:
                        case self::NOT_LIKE:
                          $criterion['value'] = '%' . $criterion['value'] . '%';
                          break;
                        case self::JUST_LIKE:
                        case self::NOT_JUST_LIKE:
                          $criterion['value'] = $criterion['value'];
                          break;
                        case self::LEFT_LIKE:
                          $criterion['value'] = '%' . $criterion['value'];
                          break;
                        case self::RIGHT_LIKE:
                          $criterion['value'] = $criterion['value'] . '%';
                          break;
                        case self::IS_NULL:
                          $criterion['value'] = '';
                          break;
                        case self::IS_NOT_NULL:
                          $criterion['value'] = '';
                          break;
                      }
                      
                      if($criterion['comparision'] != self::IS_NULL && $criterion['comparision'] != self::IS_NOT_NULL)
                            $criterion['value'] = DBAO::Database()->quote($criterion['value']);
                        
                        if(isset($criterion['mutator']) && $criterion['mutator'] != '')
                            $criterion['value'] = sprintf($criterion['mutator'], $criterion['value']);
                        $whereExpression[] = "\n{$criterion['column']} {$criterion['comparision']} {$criterion['value']}";
                         
                    }
            }
            $sql = implode($this->mode, $whereExpression);
            if(!$this->hasKeys())
                $sql = '1'; //Si no se guardaros criterios de busqueda, talvez solo necesitamos un ordenamiento o paginacion, devuelve true (1)
            

            if(count($this->groupByColumns))
            {
                $sql .= "\n GROUP BY  ";
                $sql .= implode(',', $this->groupByColumns);
            }
            if(count($this->orderByColumns))
            {
                $sql .= "\n ORDER BY  ";
                $sql .= implode(',', $this->orderByColumns);
            }
            if($this->limit != 0)
            {
                $sql .= "\n LIMIT " . $this->limit;
            }
            if($this->offset != 0)
            {
                $sql .= "\n OFFSET " . $this->offset;
            }
        }
        catch(Exception $e)
        {
            throw $e;
        }
        return $sql;
    
    }

}


