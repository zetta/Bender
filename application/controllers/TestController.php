<?php

class TestController extends GenericController
{
    
    /**
     * Test 
     */
    public function testAction()
    {
        
        $settingsFile = 'application/data/settings.yml';
        $yaml = Spyc::YAMLLoad($settingsFile);
        $bender = new ArrayObject(isset($yaml['bender']) ? $yaml['bender'] : null);
        $config = new Zend_Config(array('database' => array('adapter' => 'Pdo_Mysql', 'params' => $bender['mysql'])));
        DBAO::$config = $config->database;
        
        $user = new User();
        $user->setUsername('zetta');
        $user->setPassword('secret');
        $user->setFirstName('Juan Carlos');
        
        UserCatalog::create($user);
        
        
        

    }
}
