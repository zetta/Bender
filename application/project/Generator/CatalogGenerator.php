<?php
/**
 * CatalogGenerator
 * @author Juan Carlos Jarquin
 */

/**
 * BaseGenerator 
 */
require_once "application/project/Generator/ModelGenerator.php";

/**
 * Clase que genera los catalogos
 */
class CatalogGenerator extends ModelGenerator
{
    /**
     * Arreglo donde se guardan el nombre de los campos en la(s) tabla(s)
     * para usarlos en las sentencias tipo TABLE::FIELD
     * @var array
     */
    private $fieldNames = array();
    
    /**
     * Arreglo donde se guardan los campos para usarlos como resulset
     * @var array
     */
    private $results = array();
    
    /**
     * Numero de caracteres que contiene el campo mas largo
     */
    private $maxFieldLength = 0;
    
    /**
     * Genera el Catalog del objeto y lo almacena para su posterior uso
     */
    public function createCatalog()
    {
        $template = $this->table->hasPrimaryField() ? 'Catalog' : 'SimpleCatalog';
        CommandLineInterface::getInstance()->printSection('Generator', 'Creating ' . $this->object . 'Catalog', 'NOTE');
        
        if($this->table->hasBehaviors())
            $this->checkBehaviors();
        
        $this->template->set_filenames(array('catalog' => 'Model/' . $template));
        $this->template->assign('className', $this->object);
        $this->template->assign('catalog', $this->object . 'Catalog');
        $this->template->assign('extendedCatalog', 'Catalog');
        $this->template->assign('classVar', $this->getLowerObject());
        if ($this->table->hasPrimaryField())
        {
            $this->template->showBlock('hasPrimaryField');
            $this->template->assign('primaryKeySetter', $this->table->getPrimaryField()->getSetterName());
            $this->template->assign('primaryKeyName', $this->table->getPrimaryField()->getName());
            $this->template->assign('primaryKeyPhpName', $this->table->getPrimaryField()->getPhpName());
            $this->template->assign('primaryKeyGetter', $this->table->getPrimaryField()->getGetterName());
        }
        $this->template->showBlock((($this->settings['singleton']) ? 'isSingleton' : 'isntSingleton'));
        
        $fields = $this->table->getFields();
        $this->maxFieldLength = $this->getMaxFieldLength($fields);
        $this->loopFields($fields, true, $this->table);
        $privateCriteria = isset($this->settings['private_criteria']) ? $this->settings['private_criteria'] : false;
        $criteriaBlocK = ($privateCriteria) ? 'privateCriteria' : 'publicCriteria';
        $this->template->showBlock($criteriaBlocK);
        $this->template->assign('criteriaVar', ($privateCriteria ? '$this->criteria' : '$criteria'));
        
        if ($this->table->getExtends())
        {
            $this->template->showBlock('willExtend');
            $this->template->showBlock('willExtend.' . $criteriaBlocK);
            $this->template->assign('extendedCatalog', $this->table->getExtendedTable()->getObject() . 'Catalog');
            $this->template->assign('extendedClass', $this->table->getExtendedTable()->getObject());
            $this->template->assign('extendedPrimaryKeyUpper', ucfirst($this->table->getExtendedTable()->getPrimaryField()->getPhpName()));
            $this->template->assign('extendedPrimaryKeyPhpName', $this->table->getExtendedTable()->getPrimaryField()->getPhpName());
            $this->template->assign('extendedPrimaryKeyName', $this->table->getExtendedTable()->getPrimaryField()->getName());
            
            if ($this->settings['add_includes'])
                $this->template->showBlock('extendedInclude');
            
            $this->loopFields($this->table->getExtendedTable()->getFields(), false, $this->table->getExtendedTable());
            $this->template->assign('extendedCondition', "\".{$this->table->getObject()}::{$this->table->getExtendedTable()->getPrimaryField()->getConstantName()}.\" = \".{$this->table->getExtendedTable()->getPrimaryField()->getCatalogAccesor()}.\" and");
        }
        
        $this->template->assign('fieldNames', implode(', ', $this->fieldNames));
        $this->template->assign('results', implode(', ', $this->results));
        $this->fileContent = $this->template->fetch('catalog');
    
    }
    
    /**
     * Get the max field length
     * @param FieldCollection $fiels
     * @return int $maxFieldLength
     */
    private function getMaxFieldLength(FieldCollection $fields)
    {
        $maxFieldLength = 0;
        while ( $fields->valid() )
        {
            $field = $fields->current();
            if ($field->isPrimaryKey())
            {
                $fields->next();
                continue;
            }
            $maxFieldLength = (strlen($field->getName()) > $maxFieldLength) ? strlen($field->getName()) + 1 : $maxFieldLength;
            $fields->next();
        }
        $fields->rewind();
        return $maxFieldLength;
    }
    
    /**
     * Método que itera sobre los items dentro del FieldCollection
     * @param FieldCollection $fields
     * @param boolean $isPrimaryTable the table used is a primary or a extended table?
     */
    public function loopFields(FieldCollection $fields, $isPrimaryTable, dbTable $table)
    {
        while ( $fields->valid() )
        {
            $field = $fields->current();
            $this->fieldNames[] = "\".{$field->getCatalogAccesor()}.\"";
            
            
            if(!(!$isPrimaryTable && $field->isPrimaryKey()))
                $this->results[] = ($field->getDataType() == 'Zend_Date') ? "new Zend_Date(\$result['{$field->getName()}'], \$this->datePart)" : "\$result['{$field->getName()}']";
            
            if ($field->isPrimaryKey() || ! $isPrimaryTable)
            {
                $fields->next();
                continue;
            }

            $spaces = $this->maxFieldLength - strlen($field->getName());
            $spaces = sprintf("% " . $spaces . "s", '');
            $this->template->assignBlock('getters', array('name' => $field->getName(), 'getter' => $field->getCompleteGetterName(), 'spaces' => $spaces));
            $fields->next();
        }
        $fields->rewind();
    }
    
    
    /**
     * Genera la información de los behaviors utilizados en el catálogo
     */
    private function checkBehaviors()
    {
        $behaviorArray = array();
        foreach ($this->table->getBehaviors() as $behaviorName => $behaviorData)
        {
            $behavior =  CatalogBehavior::factory($behaviorName,$behaviorData,$this->table);
            $behaviorArray[] = "        \$this->addObserver({$behavior->generate()});";
        }
        $this->template->assign('behaviors',"\n".implode("\n",$behaviorArray));
    }
   
}











