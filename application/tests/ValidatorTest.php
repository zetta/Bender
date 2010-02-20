<?php

/**
 *  unit test case.
 */
class ValidatorTest extends UnitTestCase 
{
	
	/**
	 * Set Up
	 *
	 */
	public function setUp()
	{
	}
	
	public function testRequired()
	{
	    $personValidator = new PersonValidator();
	    $userValidator = new UserValidator();
	    $user = new User();
	    $user->setIdPerson(1)
	    ->setIdUser(1)
	    ->setFirstName('Vicente')
	    ->setLastName('Mendoza');
	     
	    // Username es requerido
	    try
	    {
	        $userValidator->validate($user);
	        $this->fail('Fallo!');
	    }
	    catch(ValidatorException $e)
	    {
	        $this->pass('Paso');
	    }
        
	    $user->setUsername('Chentepixtol');
	    
	    try
	    {
	       $userValidator->validate($user);
	       $this->pass('Fallo!');
	    }
	    catch (ValidatorException $e)
	    {
	        $this->fail( implode(', ', $e->getErrors()));
	    }
	    
	}
	
}

