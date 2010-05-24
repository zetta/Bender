<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
require_once 'application/tests/core/BenderTest.php';

/**
 *  unit test case.
 */
class TableCollectionTest extends BenderTest
{
    /**
     * @var BenderTableCollection
     */
	private $tables = null;
	
	/**
	 * Set Up
	 */
	public function setUp()
	{
        parent::setUp();
	    $this->tables = BenderDatabase::getInstance()->getTables();
	}
	
	/**
	 * testing the append method
	 * @expectedException InvalidArgumentException
	 */
	public function testAppend()
	{
	   //verificamos que podemos agregar una tabla al collection
	   $table = new BenderTable(array('table'=>'testTable'));
	   
	   $count = $this->tables->count();
	   $this->tables->append($table);
	   
	   $this->assertEquals($count+1, $this->tables->count());
	   
	   
	   //agregar otro field 
	   $table2 = new BenderTable(array('table'=>'testField2'));
	   $this->tables->append($table2);
	   $this->assertEquals($count+2, $this->tables->count());
	   
	   //un field que ya existe no deberia agregarse de nuevo
	   $this->tables->append($table2);
	   $this->assertEquals($count+2, $this->tables->count());
	   
	   //verificamos si el collection contiene el field que enviamos
	   $this->assertTrue( $this->tables->contains( $table ) );
	   
	   //despues del append, devemos ver que se pueda obtener un field 
	   $table3 = $this->tables->offsetGet('testTable');
	   $this->assertEquals($table3,$table);
	   
	   // si le enviamos al append un objecto que no es un benderfield, deberia tirar
	   // una excepción
	   $badObject = new StdClass();
	   $this->tables->append($badObject);
	   
	}
	
	/**
	 * Test for the getOne method
	 */
	public function testGetOne()
	{
	   // debe regresar un benderField
	   $table = $this->tables->getOne();
	   $this->assertType('BenderTable',$table);
	   
	   // debe regresar nulo, porque es un collection nuevo
       $collection = new BenderTableCollection();
       $this->assertNull( $collection->getOne() );
       
       // agregamos un field y checamos que ese sea el mismo que nos regresa
       $table = new BenderTable(array('table'=>'testGetOne'));
       $collection->append($table);
       $testTable = $collection->getOne();
       $this->assertEquals($table,$testTable);
	}
	
    /**
	 * Test for the contains method
	 */
	public function testContains()
	{
	    $table1 = new BenderTable(array('table'=>'testContains1'));
	    $table2 = new BenderTable(array('table'=>'testContains2'));
	    $collection = new BenderTableCollection();
	    $collection->append( $table1 );
	    
	    // probamos que contenga el que si le pasamos
	    $this->assertTrue( $collection->contains($table1) );
	    // probamos que contenga el que no le pasamos
	    $this->assertFalse( $collection->contains($table2) );
	}
	
	/**
	 * Test looping the collection
	 */
	public function testLoop()
	{
        $this->assertTrue( $this->tables->valid() );
	    while( $this->tables->valid())
	    {
	        $table = $this->tables->read();
	        $this->assertType('BenderTable',$table);
	    }
        $this->assertFalse( $this->tables->valid() );
	    
	    // terminamos el while, y no regresamos el collection, read debe ser null
	    $table = $this->tables->read();
	    $this->assertNull($table);
	}
}
