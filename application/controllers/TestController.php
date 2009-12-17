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
        $user = UserCatalog::factory();
        $user->setUsername('zetta');
        $user->setPassword('secret');
        $user->setBirthDate(new Zend_Date());
        //$user->setBirthDate(new Zend_Date());
        //$user->setEmail('correo@correo.de');
        //$user->setFirstName('Juan Carlos');
        //$user->setBirthDate(new Zend_Date('2009-10-14'));
        
        try 
        {
        
          UserCatalog::getInstance()->create($user);
        }
        catch (ValidatorException $e)
        {
          foreach  ($e->getErrors() as $campo => $error)
          {
            foreach ($error as $code => $message)
            {
              CommandLineInterface::getInstance()->printSection($campo,$message.' '. $code);
            }
          }       
        }
    }
    
    /**
     * Collection Test
     */
    public function collectionAction()
    {   
    	  $testSuite = new TestSuite('Collection Test');
        $testSuite->add(new CollectionTest());
        $testSuite->run(new TextReporter());
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
