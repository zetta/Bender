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
 *  unit test case.
 */
abstract class BenderTest extends PHPUnit_Framework_TestCase
{
    /**
	 * Set Up
	 */
	protected function setUp()
	{
	    require_once 'application/lib/autoloader/BenderAutoloader.php';
        $autoloader = BenderAutoloader::getInstance('cache/autoloadCache.file',true)->register();
        BenderRequest::getInstance()->setFlag('quiet',true);
	}

}
