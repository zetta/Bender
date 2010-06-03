<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Generate a basic schema for the selected database
 */
class SchemaGenerator
{
  
  /**
   * Extract all the info in the database and create generated.schema file
   */
  public function generate($schemaName)
  {
    $pdo = BenderDatabase::getPDO();
    $st =  $pdo->showFullTables();
    if($st === FALSE)
       throw new BenderDatabaseException(print_r($pdo->errorInfo(),true));
    $schema = array();
    foreach($st as $table)
    {
        $tableName = $table[0];
        $objectName = Formatter::underscoreToUpperCamelCase($tableName, '_', true);
        
        $dbTable = new BenderTable(array('object' => $tableName, 'table' => $tableName));
        $dbTable->initialize($objectName);
        
        $dbFields = array();
        
        $fields = $dbTable->getFields();
        
        while ($fields->valid())
        {
          $field = $fields->current();
          $dbFields[ $field->getName() ] = array(
            'type' => $field->getBaseDataType()
          );
          $fields->next();
        }
        if(!BenderRequest::getInstance()->getFlag('no-fields'))
           $schema[$objectName] = array('table' => $tableName, 'extends' => false,'fields' => $dbFields);
        else
           $schema[$objectName] = array('table' => $tableName, 'extends' => false);
    }
    $schema = array('schema' => $schema);
    $yaml = sfYaml::dump($schema,4);
    $fs = new FileSaver();
    $fs->setOutputDir('application/config');
    $fs->saveFile($schemaName.'.schema.yml',$yaml);
    
  }

}
