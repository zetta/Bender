<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class BenderFieldCollection extends ArrayIterator
{
    /**
     * Appends the value
     * @param BenderField $field
     */
    public function append($field)
    {
        if(!($field instanceof BenderField))
          throw new InvalidArgumentException('Argument passed to append must be an instance of BenderField');
        parent::offsetSet($field->getName(), $field);
        $this->rewind();
    }

    /**
     * Set the value
     * @param BenderField $field
     */
    public function offsetSet($offset, $field)
    {
        if(!($field instanceof BenderField))
          throw new InvalidArgumentException('Argument passed to offsetSet must be an instance of BenderField');
        parent::offsetSet($offset, $field);
        $this->rewind();
    }

    /**
     * Return current array entry
     * @return BenderField
     */
    public function current()
    {
        return parent::current();
    }

    /**
     * Return current array entry and 
     * move to next entry
     * @return BenderField 
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
     * @return BenderField
     */
    public function offsetGet($index)
    {
        return parent::offsetGet($index);    
    }
    
    /**
     * Get the first array entry
     * if exists or null if not 
     * @return BenderField|null 
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
    
    /**
     * @param BenderField $field
     * @return bool
     */
    public function contains(BenderField $searchField){
    	$this->rewind();
    	while ($this->valid()){
    		$field = $this->read();
    		if( $field->getName() == $searchField->getName() )
    		  return true;
    	}
    	$this->rewind();
    	return false;
    }
    
    /**
     * @param BenderFieldCollection $collection
     */
    public function merge(BenderFieldCollection $collection){
    	$collection->rewind();
    	while ( $collection->valid() ){
    		$field = $collection->read();
    		if( !$this->contains($field)) 
    		  $this->append($field);
    	}
    	$collection->rewind();
    }
    
    /**
     * Busca un campo por RegExp
     * @param string $regExp
     * @return BenderField|false
     */
    public function searchField($regExp){
        $this->rewind();
        while ($this->valid()){
            $field = $this->read();
            if( preg_match($regExp, $field->getName()) )
              return $field;
        }
        $this->rewind();
        return false;
    }
}

