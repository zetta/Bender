<?php

/**
 *  unit test case.
 */
class CollectionTest extends UnitTestCase 
{
	
	/**
	 * User Collection de Prueba
	 *
	 * @var UserCollection
	 */
	private $userCollection;
	
	/**
	 * User Collection de prueba
	 *
	 * @var UserCollection
	 */
	private $userCollection2;
	
	
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
        
        $userCollection = new UserCollection();
        $userCollection->append($user1);
        $userCollection->append($user2);
        
        $user3 = new User();
        $user3->setIdUser(6)
        ->setIdPerson(6)
        ->setFirstName('Loucho')
        ->setLastName('Lou')
        ->setBirthDate(new Zend_Date('1986-03-13 05:21:32', 'yyyy-MM-dd HH:mm:ss'))
        ->setUsername('loucho')
        ->setPassword(md5(123));
        
        $userCollection2 = new UserCollection();
        $userCollection2->append($user3);
        
        $this->userCollection = $userCollection;
        $this->userCollection2 = $userCollection2;
	}
	
	
	public function testCreate()
	{
		
        $this->assertIsA($this->userCollection, 'UserCollection');
        $this->assertEqual($this->userCollection->count(), 2);
        $this->assertEqual($this->userCollection->getPrimaryKeys(), array(1,5));
        $this->assertIsA($this->userCollection->toArray(), 'Array');
        $this->assertTrue($this->userCollection->contains(5));
        
        $this->userCollection->merge($this->userCollection2);
        
        $this->assertEqual($this->userCollection->count(), 3);
        $this->assertEqual($this->userCollection->getPrimaryKeys(), array(1,5,6));
        
        $this->assertEqual($this->userCollection->toKeyValueArray(User::FIRST_NAME, User::ID_USER), array(
            'Vicente' => 1,
            'Juan Carlos' => 5,
            'Loucho' => 6,
        ));
        
        
	}
	
}

