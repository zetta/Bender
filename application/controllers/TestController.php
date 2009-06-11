<?php


class TestController extends GenericController 
{

    /**
     * Test 
     */
    public function userAction()
    {
        
        $settingsFile = 'application/data/settings.yml';
        $yaml = Spyc::YAMLLoad($settingsFile);
        $bender = new ArrayObject(isset($yaml['bender']) ? $yaml['bender'] : null);
        $config = new Zend_Config(array('database' => array(
                'adapter' => 'Pdo_Mysql',
                'params' => $bender['mysql']
        )));
        DBAO::$config = $config->database;
        
        $artist = ArtistFactory::createArtist('Green Day','green-day','Su Mario bross','A la bio',1,1);
        ArtistCatalog::getInstance()->create($artist);
        
        
        print_r($artist);
        echo $artist->getId();
        echo "\n";
        
    }
    
}
