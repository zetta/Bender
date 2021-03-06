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
 * Comportamiento de los catálogo que se encarga de generar un slug único
 *
 * @category   lib
 * @package    lib_db
 * @subpackage lib_db_behaviors
 * @copyright  {{ copyright }}
{% if showBenderSignature %}
 * @copyright  {{ benderSignature }}
 * @author     zetta 
{% endif %}
 * @version    {{ version }} SVN: $Revision$
 */
class SluggableBehavior extends BehaviorObserver
{
    /**
     * El objeto al que se va a accesar
     * @var mixed
     */
    private $object;
    
    /**
     * Catalogo utilizado
     * @var CatalogInterface $catalog
     */
    private $catalog;
    
    /**
     * Nombre del campo donde vamos a sacar el slug
     * @var string
     */
    private $source;
    
    /**
     * Nombre del campo donde vamos a guardar el slug
     * @var string
     */
    private $target;
    
    /**
     * Nombre del campo sin tabla donde se sacará el slug
     * @var string
     */
    private $sourceName;
    
    /**
     * Nombre del campo sin tabla donde se guardará el slug
     * @var string
     */
    private $targetName;
    
    /**
     * Separador utilizado en el slug
     */ 
    protected $separator = '-';
    
    /**
     * Constructor de la Clase
     *
     * @param string $source
     * @param string $target
     * @param string $separator
     * @return SluggableBehavior
     */
    public function SluggableBehavior($params)
    {
        $this->source = $params['source'];
        $this->target = $params['target'];
        $sourceName = explode('.', $source);
        $this->sourceName = $sourceName[1];
        $targetName = explode('.', $target);
        $this->targetName = $targetName[1];
        $this->separator = isset($params['separator']) ? $params['separator'] : '-';
    }
    
    /**
     * Dispara el evento
     *
     * @param Catalog $catalog
     * @param mixed $object
     * @param int $event see Catalog constants
     */
    public function fireEvent(Catalog $catalog, $object, $event)
    {
        $this->catalog = $catalog;
        $this->object = $object;
        switch ( $event)
        {
            case Catalog::EVENT_CREATE :
                $this->setNewSlug();
            break;
        }
    }
    
    /**
     * Obtiene el valor del slug y lo pone en el bean
     */
    private function setNewSlug()
    {
        $criteria = new Criteria();
        $getterName = $this->getCamelCase('get_' . $this->sourceName);
        $setterName = $this->getCamelCase('set_' . $this->targetName);
        $slug = $this->getSlugString($this->object->$getterName());
        $criteria->add($this->target, $slug, Criteria::RIGHT_LIKE);
        $slugs = $this->catalog->getCustomFieldByCriteria($criteria, $this->target);
        if ($slugs)
        {
            $index = 1;
            while ( $index > 0 )
            {
                $temporalSlug = $slug . $this->separator . $index;
                if (! in_array($temporalSlug, $slugs))
                {
                    $slug = $temporalSlug;
                    $index = - 1;
                }
                $index ++;
            }
        }
        $this->object->$setterName($slug);
    }
    
    /**
     * Obtiene el slug dependiendo una cadena
     *
     * @param string $str
     * @return string
     */
    private function getSlugString($str)
    {
        $str = strtolower(trim($str));
        $str = preg_replace("/[^a-z0-9{$this->separator}]/", $this->separator, $str);
        $str = preg_replace("/{$this->separator}+/", "-", $str);
        return trim($str,$this->separator);
    }

}


