<?php

/**
 *  unit test case.
 */
class CollectionTest extends UnitTestCase 
{
	/**
	 * @var User
	 */
	private $user1;
	
	/**
	 * @var User
	 */
	private $user2;
	
	/**
	 * @var User
	 */
	private $user3;
	
	/**
	 * Set Up
	 *
	 */
	public function setUp()
	{
		$user1 = new User();
        $user1->setIdUser(1)
        ->setIdPerson(1)
        ->setFirstName('Vicente')
        ->setLastName('Mendoza')
        ->setBirthDate(new Zend_Date('1986-01-11', 'yyyy-MM-dd'))
        ->setUsername('chentepixtol')
        ->setPassword(md5(123));
        
        $user2 = new User();
        $user2->setIdUser(5)
        ->setIdPerson(5)
        ->setFirstName('Juan Carlos')
        ->setLastName('Jarquin')
        ->setBirthDate(new Zend_Date('1986-03-13', 'yyyy-MM-dd'))
        ->setUsername('zetta')
        ->setPassword(md5(123));
        
        $user3 = new User();
        $user3->setIdUser(6)
        ->setIdPerson(6)
        ->setFirstName('Loucho')
        ->setLastName('Lou')
        ->setBirthDate(new Zend_Date('1986-03-13 05:21:32', 'yyyy-MM-dd HH:mm:ss'))
        ->setUsername('loucho')
        ->setPassword(md5(123));
        
        $this->user1 = $user1;
        $this->user2 = $user2;
        $this->user3 = $user3;
	}
	
	/**
	 * Test for the Append Function
	 */
	public function testAppend()
	{
	    $userCollection = new UserCollection();
	    
	    //Agregar un usuario
	    $userCollection->append($this->user1);
	    $this->assertEqual(count($userCollection), 1);
	    
	    //Agregar otro usuario
	    $userCollection->append($this->user2);
	    $this->assertEqual(count($userCollection), 2);
	    
	    /*Un usuario que ya existe en la collection no deberia de agregarse de nuevo*/
	    $userCollection->append($this->user1);
	    $userCollection->append($this->user2);
	    $this->assertEqual(count($userCollection), 2);
	    
	    /* Verificar que los indices son los correctos */
	    $keys = $userCollection->getPrimaryKeys();
	    $this->assertEqual($keys, array($this->user1->getIdUser(), $this->user2->getIdUser()));
	}
	
	/**
	 * Test for the Merge Function
	 * @return unknown_type
	 */
	public function testMerge()
	{
	    $collection1 = new UserCollection();
	    $collection1->append($this->user1);
	    $collection1->append($this->user2);
	    
	    $collection2 = new UserCollection();
	    $collection2->append($this->user3);
	    
	    $collection1->merge($collection2);
	    
	    $this->assertEqual(count($collection1), 3);
	    $this->assertTrue($collection1->contains($collection2->getOne()->getIdUser()));
	}
	
	/**
	 * Test for the toKeyValueArray Function
	 * @return unknown_type
	 */
	public function testToKeyValueArray()
	{
	    $userCollection = new UserCollection();
	    $userCollection->append($this->user1);
	    $userCollection->append($this->user2);
	    
	    $this->assertEqual($userCollection->toKeyValueArray(User::FIRST_NAME, User::ID_USER), array(
            $this->user1->getFirstName() => $this->user1->getIdUser(),
            $this->user2->getFirstName() => $this->user2->getIdUser(),
        ));
        
        $this->assertEqual($userCollection->toKeyValueArray(User::USERNAME, User::USERNAME), array(
            $this->user1->getUsername() => $this->user1->getUsername(),
            $this->user2->getUsername() => $this->user2->getUsername(),
        ));
	}
	
	/**
	 * Test for the toArrayFunction
	 * @return unknown_type
	 */
	public function testToArray()
	{
		$userCollection = new UserCollection();
		$userCollection->append($this->user1);
		
		$users = $userCollection->toArray();
		
		$this->assertIsA($users, 'Array');
		$this->assertEqual(count($users), 1);
		$this->assertEqual($users[$this->user1->getIdUser()]['username'], $this->user1->getUsername());
		$this->assertEqual($users[$this->user1->getIdUser()]['idUser'], $this->user1->getIdUser());
	}
	
	/**
	 * Test for the getOne Function
	 * @return unknown_type
	 */
	public function testGetOne()
	{
	    $userCollection = new UserCollection();
	    
	    /* No se han agregado usuarios, debe de regresar nulo*/
	    $user = $userCollection->getOne();
	    $this->assertNull($user);
	    
	    $userCollection->append($this->user2);
	    $userCollection->append($this->user1);
	    
	    /* El usuario debe de ser el primero que se agrego */
	    $this->assertReference($this->user2, $userCollection->getOne());
	}
	
	/**
	 * Test for the contains Function
	 * @return unknown_type
	 */
	public function testContains()
	{
	    $userCollection = new UserCollection();
	    $userCollection->append($this->user1);
	    
	    // Preguntar por un idUser que no se encuentra en la collection 
	    $this->assertFalse($userCollection->contains($this->user2->getIdUser()));
	    
	    // Preguntar por un idUser que si se encuentra en la collection
	    $this->assertTrue($userCollection->contains($this->user1->getIdUser()));
	}
	
	/**
	 * Test for the getPrimaryKeys Function
	 * @return unknown_type
	 */
	public function testGetPrimaryKeys()
	{
	    $userCollection = new UserCollection();
	    $this->assertEqual($userCollection->getPrimaryKeys(), array());
	    
	    $userCollection->append($this->user1);
	    $userCollection->append($this->user2);
	    $userCollection->append($this->user3);
	    
	    $this->assertEqual($userCollection->getPrimaryKeys(), array(
	       $this->user1->getIdUser(),
	       $this->user2->getIdUser(),
	       $this->user3->getIdUser(),
	    ));
	    
	}
	
}

