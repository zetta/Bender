<?php
/**
 * BeanGenerator
 * @author Juan Carlos Jarquin
 */

/**
 * BaseGenerator 
 */
require_once "application/project/Generator/ModelGenerator.php";

/**
 * Clase que se encarga de generar el Factory
 */
class FactoryGenerator extends ModelGenerator
{
    /**
     * Arreglo donde se guardaran los parámetros que se requerirán
     * en el primer método de la Factory
     * @var array
     */
    private $firstParameters = array();
    
    /**
     * Arreglo donde se guardaran los parámetros que se requerirán
     * en el segundo método de la Factory (Internal)
     * @var array
     */
    private $secondParameters = array();
    
    /**
     * Arreglo donde se guardaran los parámetros que se enviarán
     * al segundo método de la Factory (Internal)
     + estos deben de ir sin el tipo de dato ya que eso causaria un error
     * @var array
     */
    private $secondParametersNotCasted = array();
    
    /**
     * Genera el factory y lo almacena para su posterior uso
     */
    public function createFactory()
    {
        CommandLineInterface::getInstance()->printSection('Generator','Creating '.$this->object.'Factory','NOTE'); 
        $this->template->set_filenames(array('factory' => 'Model/Factory'));
        $this->template->assign('className', $this->object);
        $this->template->assign('factory', $this->object . 'Factory');
        $this->template->assign('classVar', $this->getLowerObject());
        
        $this->loopFields($this->table->getFields(),true);
        
        if($this->table->getExtends())
            $this->loopFields($this->table->getExtendedTable()->getFields(),false);
        
        $this->template->assign('firstParameters', implode(', ', $this->firstParameters));
        $this->template->assign('secondParameters', implode(', ', $this->secondParameters));
        $this->template->assign('secondParametersNotCasted', implode(', ', $this->secondParametersNotCasted));
        if($this->table->hasPrimaryField())
        {
            $this->template->showBlock('hasPrimaryField');
            $this->template->assign('primaryKeySetter', $this->table->getPrimaryField()->getSetterName());
            $this->template->assign('primaryKeyPhpName', $this->table->getPrimaryField()->getPhpName());
        }
        $this->fileContent = $this->template->fetch('factory');
    }
    
    /**
     * Itera sobre los items dentro del FieldCollection
     * @param FieldCollection
     * @param boolean $isPrimaryTable the table used is a primary or a extended table?
     */
    private function loopFields(FieldCollection $fields)
    {
        while ( $fields->valid() )
        {
            $field = $fields->current();
            
            #* Si estamos extendiendo otra tabla, no mostrar el PK de la tabla pues se repitiria
            if($this->table->getExtends() &&  $field === $this->table->getExtendedTable()->getPrimaryField())
            {
                $fields->next();
                continue;
            }
            
            $this->template->assignBlock('secondPhpDoc', array(
                    'phpName' => $field->getPhpName(), 
                    'dataType' => $field->getDataType(), 
                    'comment' => $field->getComment()));
            $this->secondParameters[] = $field->getCastDataType() . '$' . $field->getPhpName();
            
            
            # No mostrar la llave primaria en el primer método
            # o de la tabla primaria
            if ($field->isPrimaryKey())
            {
                $fields->next();
                continue;
            }
            
            $this->secondParametersNotCasted[] = '$' . $field->getPhpName();
            $this->template->assignBlock('firstPhpDoc', array(
                    'phpName' => $field->getPhpName(), 
                    'dataType' => $field->getDataType(), 
                    'comment' => $field->getComment()));
            $this->template->assignBlock('firstSetters', array('phpName' => $field->getPhpName(), 'setter' => $field->getSetterName()));
            $this->firstParameters[] = $field->getCastDataType() . '$' . $field->getPhpName();
            
            $fields->next();
        }
        $fields->rewind();
    }

}











