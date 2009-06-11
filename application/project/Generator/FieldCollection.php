<?php
/**
 * @author     Juan Carlos Jarquín, $LastChangedBy$
 */

class FieldCollection extends ArrayIterator
{
    /**
     * Appends the value
     * @param DbField $field
     */
    public function append($field)
    {
        parent::append($field);
    }

    /**
     * Return current array entry
     * @return DbField
     */
    public function current()
    {
        return parent::current();
    }

    /**
     * Return current array entry and 
     * move to next entry
     * @return DbField 
     */
    public function read()
    {
        $field = $this->current();
        $this->next();
        return $field;
    }
    /**
     * Regresa el valor guardado en X indice 
     *
     * @param string|int $index
     * @return dbField
     */
    public function offsetGet($index)
    {
        return parent::offsetGet($index);    
    }
    
    /**
     * Get the first array entry
     * if exists or null if not 
     * @return DbField|null 
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
}

