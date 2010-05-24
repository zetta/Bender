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
class FileSaverTest extends BenderTest
{

	/**
	 * Set Up
	 */
	public function setUp()
	{
	    parent::setUp();
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('example-dir'));
	}

    /**
     * Probando el método que guarda los archivos
     */
    public function testSaveFile()
    {
        $fs = new FileSaver();
        $fs->setOutputDir(vfsStream::url('example-dir'));
        // aun no creamos el archivo, no existe
        $this->assertFalse(vfsStreamWrapper::getRoot()->hasChild('myfile'));
        $fs->saveFile('myfile','content');
        // lo creamos, el count deberia decir 1, y myfile debe existir
        $this->assertEquals(1,$fs->getCount());
        $this->assertTrue(vfsStreamWrapper::getRoot()->hasChild('myfile'));
    }

}
