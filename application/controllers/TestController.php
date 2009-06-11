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
        DBAO::$config = $bender['mysql'];
        
        
        
        $artist = ArtistFactory::createArtist('Green Day','es una banda','Su bio',1,1,'',new Zend_Date(),new Zend_Date());
        ArtistCatalog::getInstance()->createArtist($artist);
        
        
        print_r($artist);
        echo "\n";
        echo $artist->getIdArtist();
    }
    
}