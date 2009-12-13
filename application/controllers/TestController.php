<?php

class TestController extends GenericController
{
   public function preDispatch()
   {
        $benderSettings = BenderSettings::getInstance();
        $config = new Zend_Config(array('database' => array('adapter' => 'pdo_mysql', 'params' => $benderSettings->getMysql())));
        DBAO::$config = $config->database;        
   }
  
  
    /**
     * Test 
     */
    public function testAction()
    {
        $user = new User();
        $user->setUsername('zetta');
        $user->setPassword('secret');
        $user->setFirstName('Juan Carlos');
        
        #$user->setBirthDate(new Zend_Date());
        
        try 
        {
        
          UserCatalog::getInstance()->create($user);
        }
        catch (ValidatorException $e)
        {
          foreach  ($e->getErrors() as $error)
          {
            foreach ($error as $field => $message)
            {
              CommandLineInterface::getInstance()->printSection($field,$message);
            }
          }       
        }
    }
    
    public function collectionAction()
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
    
        $users = $userCollection->toArray();
        print_r($users);
        
        $combo = $userCollection->toKeyValueArray(User::ID_USER, User::USERNAME);
        print_r($combo);
        
        $keys = $userCollection->getPrimaryKeys();
        print_r($keys);
        
        
    }
    
    /**
     * Modo interactivo
     */
    public function interactiveAction()
    {
      do{
        $entry = CommandLineInterface::getInstance()->prompt('');
        eval($entry);
        echo "\n";
      }
      while ($entry != 'quit;');
      $figlet = new Zend_Text_Figlet();
      echo $figlet->render('bye!');
    }
    
}
