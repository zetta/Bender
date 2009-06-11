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
 * Clase que genera los Beans
 */
class BeanGenerator extends ModelGenerator
{
    /**
     * Genera el Bean del objeto y lo almacena para su posterior uso
     */
    public function createBean()
    {
        CommandLineInterface::getInstance()->printSection('Generator', 'Creating ' . $this->object, 'NOTE');
        $this->template->set_filenames(array('bean' => 'Model/Bean'));
        $this->template->assign('className', $this->object);
        $this->template->assign('tableName', $this->table->getTable());
        
        $this->loopFields($this->table->getFields());
        
        if ($this->table->getExtends())
        {
            $this->template->assign('extendedSentence', ' extends '.$this->table->getExtendedTable()->getObject());
            $this->template->assign('extendedBean', $this->table->getExtendedTable()->getObject());
            if($this->settings['add_includes'])
                $this->template->showBlock('extendedInclude');
        }
        $this->fileContent = $this->template->fetch('bean');
    }
    
    /**
     * Método que itera sobre los items dentro del FieldCollection
     * @param FieldCollection $fields
     */
    public function loopFields(FieldCollection $fields)
    {
        while ( $fields->valid() )
        {
            $field = $fields->current();
            $this->template->assignBlock('attributes', array('phpName' => $field->getPhpName(), 'dataType' => $field->getDataType()));
            $this->template->assignBlock('methods', array(
                    'phpName' => $field->getPhpName(), 
                    'dataType' => $field->getDataType(), 
                    'castType' => $field->getCastDataType(), 
                    'setter' => $field->getSetterName(), 
                    'getter' => $field->getGetterName(), 
                    'comment' => $field->getComment()));
            
            if (isset($this->settings['use_constants']) && $this->settings['use_constants'])
                $this->template->assignBlock('constants', array(
                    'fieldName' => $field->getConstantName(), 
                    'fieldValue' => $this->table->getTable().'.'.$field->getName()
                ));
            $fields->next();
        }
        $fields->rewind();
    }

}
