{% extends "_baseModel.tpl" %}
{% block body %}

{% if flags["add-includes"] %}

require_once "{{ route.getLib('ParserGenerator') }}";

{% endif %}
/**
 * Clase {{ Collection }} que representa una collección de objetos {{ Bean }}
 *
 * @category   lib
 * @package    lib_models
 * @subpackage lib_models_collections
 * @copyright  {{ copyright }}
{% if showBenderSignature %}
 * @copyright  {{ benderSignature }}
 * @author     zetta & chentepixtol
{% endif %}
 * @version    {{ version }} SVN: $Revision$
 */
class {{ Collection }} extends ArrayIterator
{

    /**
     * @var Parser
     */
    protected static $parser;

    /**
     * Constructor
     * @param array $array
     * @return void
     */
    public function __construct($array = array())
    {
    	if( null != self::$parser ){
        	self::$parser = new Parser('{{ Bean }}');
        }
        parent::__construct($array);
    }

    /**
     * Appends the value
     * @param {{ Bean }} ${{ bean }}
     */
    public function append(${{ bean }})
    {
{% if table.hasPrimaryField() %}
        parent::offsetSet(${{ bean }}->{{ table.getPrimaryField().getGetterName() }}(), ${{ bean }});
        $this->rewind();
{% else %}
        parent::append(${{ bean }});
        $this->rewind();
{% endif %}
    }

    /**
     * Return current array entry
     * @return {{ Bean }}
     */
    public function current()
    {
        return parent::current();
    }

    /**
     * Return current array entry and
     * move to next entry
     * @return {{ Bean }}
     */
    public function read()
    {
        ${{ bean }} = $this->current();
        $this->next();
        return ${{ bean }};
    }

    /**
     * Get the first array entry
     * if exists or null if not
     * @return {{ Bean }}|null
     */
    public function getOne()
    {
        if ($this->count() > 0)
        {
            $this->seek(0);
            return $this->current();
        } else
            return null;
    }

{% if table.hasPrimaryField() %}
    /**
     * Contains one object with ${{ table.getPrimaryField().getVarName() }}
     * @param  {{ table.getPrimaryField().getDataType() }} ${{ table.getPrimaryField().getVarName() }}
     * @return boolean
     */
    public function contains(${{ table.getPrimaryField().getVarName() }})
    {
        return parent::offsetExists(${{ table.getPrimaryField().getVarName() }});
    }

    /**
     * Remove one object with ${{ table.getPrimaryField().getVarName() }}
     * @param  {{ table.getPrimaryField().getDataType() }} ${{ table.getPrimaryField().getVarName() }}
     */
    public function remove(${{ table.getPrimaryField().getVarName() }})
    {
        if( $this->contains(${{ table.getPrimaryField().getVarName() }}) )
            $this->offsetUnset(${{ table.getPrimaryField().getVarName() }});
    }

    /**
     * Merge two Collections
     * @param {{ Collection }} ${{ collection }}
     * @return void
     */
    public function merge({{ Collection }} ${{ collection }})
    {
        ${{ collection }}->rewind();
        while(${{ collection }}->valid())
        {
            ${{ bean }} = ${{ collection }}->read();
            if( !$this->contains( ${{ bean }}->{{ table.getPrimaryField().getGetterName() }}() ) )
                $this->append(${{ bean }});
        }
        ${{ collection }}->rewind();
    }

    /**
     * Diff two Collections
     * @param {{ Collection }} ${{ collection }}
     * @return void
     */
    public function diff({{ Collection }} ${{ collection }})
    {
        ${{ collection }}->rewind();
        while(${{ collection }}->valid())
        {
            ${{ bean }} = ${{ collection }}->read();
            if( $this->contains( ${{ bean }}->{{ table.getPrimaryField().getGetterName() }}() ) )
                $this->remove(${{ bean }}->{{ table.getPrimaryField().getGetterName() }}());
        }
        ${{ collection }}->rewind();
    }

    /**
     * Intersect two Collections
     * @param {{ Collection }} ${{ collection }}
     * @return {{ Collection }}
     */
    public function intersect({{ Collection }} ${{ collection }})
    {
        $new{{ collection }} = new {{ Collection }}();
        ${{ collection }}->rewind();
        while(${{ collection }}->valid())
        {
            ${{ bean }} = ${{ collection }}->read();
            if( $this->contains( ${{ bean }}->{{ table.getPrimaryField().getGetterName() }}() ) )
                $new{{ collection }}->append(${{ bean }});
        }
        ${{ collection }}->rewind();
        return $new{{ collection }};
    }

    /**
     * Retrieve the array with primary keys
     * @return array
     */
    public function getPrimaryKeys()
    {
        return array_keys($this->getArrayCopy());
    }

    /**
     * Retrieve the {{ Bean }} with primary key
     * @param  {{ table.getPrimaryField().getDataType() }} ${{ table.getPrimaryField().getVarName() }}
     * @return {{ Bean }}
     */
    public function getByPK(${{ table.getPrimaryField().getVarName() }})
    {
        return $this->contains(${{ table.getPrimaryField().getVarName() }}) ? $this[${{ table.getPrimaryField().getVarName() }}] : null;
    }
{% endif %}

    /**
     * Transforma una collection a un array
     * @return array
     */
    public function toArray()
    {
        $array = array();
        while ($this->valid())
        {
            ${{ bean }} = $this->read();
            $this->getParser()->changeBean(${{ bean }});
{% if table.hasPrimaryField() %}
            $array[${{ bean }}->{{ table.getPrimaryField().getGetterName() }}()] = $this->parser->toArray();
{% else %}
            $array[] = $this->getParser()->toArray();
{% endif %}
        }
        $this->rewind();
        return $array;
    }

    /**
     * Crea un array asociativo de $key => $value a partir de las constantes de un bean
     * @param string $ckey
     * @param string $cvalue
     * @return array
     */
    public function toKeyValueArray($ckey, $cvalue)
    {
        $array = array();
        while ($this->valid())
        {
            ${{ bean }} = $this->read();
            $this->getParser()->changeBean(${{ bean }});
            $array += $this->getParser()->toKeyValueArray($ckey, $cvalue);
        }
        $this->rewind();
        return $array;
    }

    /**
     * Retrieve the parser object
     * @return Parser
     */
    public function getParser()
    {
        return self::$parser;
    }

    /**
     * Is Empty
     * @return boolean
     */
    public function isEmpty()
    {
        return $this->count() == 0;
    }


}

{% endblock %}
