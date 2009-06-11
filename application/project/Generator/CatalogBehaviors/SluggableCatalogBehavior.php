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
        $source = $this->table->getField($this->behaviorData['source'])->getCatalogAccesor();
        $target = $this->table->getField($this->behaviorData['target'])->getCatalogAccesor();
        return "new SluggableBehavior({$source},{$target})";
    }
}

