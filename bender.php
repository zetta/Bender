#!/usr/bin/php -q 
<?php
/**
 * BenderModeler
 * @author Juan Carlos Jarquin
 */


require_once 'application/project/Autoloader/ProjectAutoloader.php';
$autoloader = ProjectAutoloader::getInstance('application/data/autoloadCache.file',true)->register();


try 
{
    $output = CommandLineInterface::getInstance();
    $cli = new CommandLineInterpreter();
    $cli->run();
}
catch (Exception $e)
{
    if(isset($output))
        $output->renderException($e);
    else
        throw $e; 
}


