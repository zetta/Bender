#!/usr/bin/php -q 
<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

chdir(dirname(__FILE__));
error_reporting(-1); // making strict =D
set_error_handler(create_function('$a, $b, $c, $d', 'throw new ErrorException($b, 0, $a, $c, $d);'), E_ALL & ~E_NOTICE);
require_once 'application/lib/autoloader/BenderAutoloader.php';
$autoloader = BenderAutoloader::getInstance('cache/autoloadCache.file',true)->register();


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


