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
     * Genera un test básico
     */
    public function testAction()
    {
        $user = UserCatalog::factory();
        $user->setUsername('zetta');
        $user->setPassword('secret');
        $user->setBirthDate(new Zend_Date());
        
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
     * Realiza las pruebas de los objetos Collection
     */
    public function collectionAction()
    {   
        $testSuite = new TestSuite('Collection Test');
        $testSuite->add(new CollectionTest());
        $testSuite->add(new ValidatorTest());
        $testSuite->run(new TextReporter());
    }
    
    /**
     * Entra a modo interactivo
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
    
    /**
     * Carga los datos de prueba
     */
    public function loadTestDataAction()
    {
      $bender = BenderSettings::getInstance();
      CommandLineInterface::getInstance()->printSection('Test','Loading test data');
      exec("mysql -u {$bender->getUsername()} -p{$bender->getPassword()} bender < application/data/sample-sql.sql");
      CommandLineInterface::getInstance()->printSection('Test','done.');
    }
    
}
