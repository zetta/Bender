<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once 'application/tests/core/BenderTest.php';

/**
 *  unit test case.
 */
class AutoloaderTest extends BenderTest
{

    /**
     * Probando el método, has class de bender
     */
    public function testClassExists()
    {
        //una clase que si existe
        $this->assertTrue(BenderAutoloader::getInstance()->hasClass('BenderRequest'));
    }
    
    /**
     * probamos el metodo hasclass del autoloader con una clase que no existe
     */
    public function testClassDontExists()
    {
        //una clase que NO existe
        $this->assertFalse(BenderAutoloader::getInstance()->hasClass('BenderClassThatDontExists'));
    }

}
