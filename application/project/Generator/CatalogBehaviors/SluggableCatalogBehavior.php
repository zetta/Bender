<?php

class SluggableCatalogBehavior extends CatalogBehavior
{
    
    /**
     * Constructor de la clase
     *
     * @param DbTable $dbTable
     * @param array $behaviorData
     * @return SluggableCatalogBehavior
     */
    public function SluggableCatalogBehavior(DbTable $dbTable, $behaviorData)
    {
        $this->table = $dbTable;
        $this->behaviorData = $behaviorData;
    }
    
    /**
     * Genera la llamada al behavior
     * @return string
     */
    public function generate()
    {
        $table = strtoupper($this->table->getTable());
        $source = $this->table->getField($this->behaviorData['source'])->getConstantName();
        $target = $this->table->getField($this->behaviorData['target'])->getConstantName();
        return "new SluggableBehavior({$table},{$source},{$target})";
    }

}

