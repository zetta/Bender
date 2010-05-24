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
class FieldCollectionTest extends BenderTest
{

    /**
     * @var BenderFieldCollection
     */
	private $fields = null;
	
	/**
	 * Set Up
	 */
	public function setUp()
	{
	    parent::setUp();
	    $this->fields = BenderDatabase::getInstance()->getTables()->getOne()->getFields();
	}
	
	/**
	 * Test for the Append method
	 * @expectedException InvalidArgumentException
	 */
	public function testAppend()
	{
	   //verificamos que podemos agregar un field al collection
	   $field = new BenderField('testField');
	   $count = $this->fields->count();
	   $this->fields->append($field);
	   $this->assertEquals($count+1, $this->fields->count());
	   
	   //agregar otro field 
	   $field2 = new BenderField('testField2');
	   $this->fields->append($field2);
	   $this->assertEquals($count+2, $this->fields->count());
	   
	   //un field que ya existe no deberia agregarse de nuevo
	   $this->fields->append($field2);
	   $this->assertEquals($count+2, $this->fields->count());
	   
	   //verificamos si el collection contiene el field que enviamos
	   $this->assertTrue( $this->fields->contains( $field ) );
	   
	   //despues del append, devemos ver que se pueda obtener un field 
	   $field3 = $this->fields->offsetGet('testField');
	   $this->assertEquals($field3,$field);
	   
	   // si le enviamos al append un objecto que no es un benderfield, deberia tirar
	   // una excepción
	   $badObject = new StdClass();
	   $this->fields->append($badObject);
	}
	
	/**
	 * Test for the Merge method
	 */
	public function testMerge()
	{
	    $field = new BenderField('testMergeField');
	    $count = $this->fields->count();
	    $collection = new BenderFieldCollection();
	    $collection->append($field);
	    $this->fields->merge($collection);
	    // al hacer el merge debe sumar 1 mas, pues la collection tenia 1
	    $this->assertEquals($count+1, $this->fields->count());
	    
	    //verificamos si el collection contiene el field que enviamos
	    $this->assertTrue( $this->fields->contains( $field ) );
	}
	
	/**
	 * Test the error for the Merge method
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testMergeError()
	{
	    //debe enviar un Error de Php porque no le estamos enviando un BenderFieldCollection
	    $this->fields->merge(new StdClass());
	}
	
	/**
	 * Test for the getOne method
	 */
	public function testGetOne()
	{
	   // debe regresar un benderField
	   $field = $this->fields->getOne();
	   $this->assertType('BenderField',$field);
	   
	   // debe regresar nulo, porque es un collection nuevo
       $collection = new BenderFieldCollection();
       $this->assertNull( $collection->getOne() );
       
       // agregamos un field y checamos que ese sea el mismo que nos regresa
       $field = new BenderField('testGetOne');
       $collection->append($field);
       $testField = $collection->getOne();
       $this->assertEquals($field,$testField);
	}
	
	/**
	 * Test for the contains method
	 */
	public function testContains()
	{
	    $field1 = new BenderField('testContains1');
	    $field2 = new BenderField('testContains2');
	    $collection = new BenderFieldCollection();
	    $collection->append( $field1 );
	    
	    // probamos que contenga el que si le pasamos
	    $this->assertTrue( $collection->contains($field1) );
	    // probamos que contenga el que no le pasamos
	    $this->assertFalse( $collection->contains($field2) );
	}
	
	/**
	 * Test looping the collection
	 */
	public function testLoop()
	{
        $this->assertTrue( $this->fields->valid() );
	    while( $this->fields->valid())
	    {
	        $field = $this->fields->read();
	        $this->assertType('BenderField',$field);
	    }
        $this->assertFalse( $this->fields->valid() );
	    
	    // terminamos el while, y no regresamos el collection, read debe ser null
	    $field = $this->fields->read();
	    $this->assertNull($field);
	}

	/**
	 * Test for the offsetget method
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testOffsetGet()
	{
	    //obtenemos uno que si existe
	    $field = $this->fields->offsetGet('testField');
	    $this->assertType('BenderField',$field);
	    
	    //intentamos obtener uno que no existe
	    //debe enviar un error
	    $field = $this->fields->offsetGet('testField99');
	}
	
    /**
     * test the searchField method
	 * @expectedException PHPUnit_Framework_Error
     */
	public function testSearchField()
	{
        $field = new BenderField('RegularExpressionSearch');
        $this->fields->append($field);
        $found = $this->fields->searchField('/^Regular/');
        $this->assertEquals($field,$found);
        
        $found = $this->fields->searchField('/^UnMatchedExpression$/');
        $this->assertFalse($found);
        
        // una expresion regular mal formada debe enviar error
        $found = $this->fields->searchField('/^InvalidExpression');
        $this->assertFalse($found);
	}
}

