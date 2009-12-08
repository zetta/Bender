<?php
/**
 * CrudController
 * @author Juan Carlos Jarquin
 */

/**
 * Modulo para generar los CRUDS
 *
 */
class CrudController extends GenericController
{    
    private $library = array(
            'CatalogInterface' => '{db-location}/CatalogInterface.php', 
            'Catalog' => '{db-location}/Catalog.php',
            'Criteria' => '{db-location}/Criteria.php', 
            'DBAO' => '{db-location}/DBAO.php', 
            'BehaviorObserver' => '{db-location}/Behavior/BehaviorObserver.php', 
            'Observer' => '{db-location}/Behavior/Observer.php', 
            'SluggableBehavior' => '{db-location}/Behavior/SluggableBehavior.php');
    
    /**
     * Genera los catálogos
     */
    public function generateAction()
    {
        $benderSettings = BenderSettings::getInstance();
        $schemaFile = "application/data/{$benderSettings->getSchema()}.schema.yml";
        
        if (! file_exists($schemaFile))
            throw new ErrorException("El archivo [{$schemaFile}] no se encuentra");
        $yaml = Spyc::YAMLLoad($schemaFile);
        $bender['models'] = $yaml['schema'];
        $benderSettings->setUp($yaml);
        
        if(isset($yaml['workcopy']))
            ProjectAutoloader::getInstance()->addDirectory($benderSettings->getWorkCopyLocation());
        if(isset($yaml['libPath']))
            ProjectAutoloader::getInstance()->addDirectory($benderSettings->getLibraryLocation());
        
        foreach ( $bender['models'] as $objectName => $model )
            $bender['models'][$objectName]['object'] = $objectName;
        $benderSettings->setModels($bender['models']);
        $models = array();
        foreach ( $bender['models'] as $objectName => $model )
        {
            $dbTable = new DbTable($model['table'], $benderSettings->getDbName(), $model);
            $dbTable->initialize();
            $models[$objectName] =$dbTable;
        }
        
        
       
        
        foreach ( $models as $objectName => $dbTable )
        {
            $crudGenerator = new CrudGenerator($objectName, $dbTable);
            $crudGenerator->createCrud();
            $crudGenerator->saveFile("{$benderSettings->getWorkCopyLocation()}/{$benderSettings->getControllerLocation()}/{$objectName}Controller.php", $benderSettings->getPreserveChanges());
            
            
        }
        
       
    }
    

    
    
    


}







