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
