<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class BenderTableCollection extends ArrayIterator
{
    /**
     * Appends the value
     * @param BenderTable $field
     */
    public function append($table)
    {
        if(!($table instanceof BenderTable))
          throw new InvalidArgumentException('Argument passed to append must be an instance of BenderTable');
        parent::offsetSet($table->getTableName(), $table);
        $this->rewind();
    }
    
    /**
     * @param BenderTable $field
     * @return bool
     */
    public function contains(BenderTable $searchTable){
    	$this->rewind();
    	while ($this->valid()){
    		$table = $this->read();
    		if( $table->getTableName() == $searchTable->getTableName() )
    		  return true;
    	}
    	$this->rewind();
    	return false;
    }
    
    /**
     * set the value
     * @param BenderTable $field
     */
    public function offsetSet($offset,$table)
    {
        if(!($table instanceof BenderTable))
          throw new InvalidArgumentException('Argument passed to append must be an instance of BenderTable');
        parent::offsetSet($offset, $table);
        $this->rewind();
    }

    /**
     * Return current array entry
     * @return BenderTable
     */
    public function current()
    {
        return parent::current();
    }

    /**
     * Return current array entry and 
     * move to next entry
     * @return BenderTable 
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
     * @return BenderTable
     */
    public function offsetGet($index)
    {
        return parent::offsetGet($index);    
    }
    
    /**
     * Get the first array entry
     * if exists or null if not 
     * @return BenderTable|null 
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

