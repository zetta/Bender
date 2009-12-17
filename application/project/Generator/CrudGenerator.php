<?php
/**
 * BeanGenerator
 * @author Juan Carlos Jarquin
 */

/**
 * Clase que genera los Cruds
 */
class CrudGenerator extends ModelGenerator
{
    /**
     * Genera el Bean del objeto y lo almacena para su posterior uso
     */
    public function create()
    {
        CommandLineInterface::getInstance()->printSection('Generator', 'Creating ' . $this->object.'->CRUD', 'INFO');
        $this->template->set_filenames(array('controller' => 'Crud/Controller'));

        
        $this->template->assign('tableName', $this->table->getTable());
        
        $this->loopFields($this->table->getFields());
        
        if ($this->table->getExtends())
        {
            $this->template->assign('extendedSentence', ' extends '.$this->table->getExtendedTable()->getObject());
            $this->template->assign('extendedBean', $this->table->getExtendedTable()->getObject());
            if($this->benderSettings->getAddIncludes())
                $this->template->showBlock('extendedInclude');
        }
        $this->fileContent = $this->template->fetch('controller');
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
            $this->template->assignBlock('attributes', array('phpName' => $field->getPhpName(), 'dataType' => $field->getDataType(), 'comment' => $field->getComment()));
            $this->template->assignBlock('methods', array(
                    'phpName' => $field->getPhpName(), 
                    'dataType' => $field->getDataType(), 
                    'castType' => $field->getCastDataType(), 
                    'setter' => $field->getSetterName(), 
                    'getter' => $field->getGetterName(), 
                    'comment' => $field->getComment()));
            
            if ($this->benderSettings->useConstants())
                $this->template->assignBlock('constants', array(
                    'fieldName' => $field->getConstantName(), 
                    'fieldValue' => $this->table->getTable().'.'.$field->getName()
                ));
            $fields->next();
        }
        $fields->rewind();
    }

}
