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
class BenderDumperTest extends BenderTest
{
	/**
	 * Set Up
	 */
	public function setUp()
	{
	    parent::setUp();
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('example-dump'));
	}
	
    /**
     * Probando el método, 
     */
    public function testDeleteDirectoryContent()
    {
        $dumper = new BenderDumper();
        
    }
    
    /**
     *
     */
    public function testDeleteFile()
    {
        $file = 'please-delete-me';
        $dumper = new BenderDumper();
        $root = vfsStreamWrapper::getRoot();
        $root->addChild( new vfsStreamFile($file) );
        $this->assertTrue($root->hasChild($file));
        $dumper->deleteFile( vfsStream::url('example-dump/'.$file) );
        $this->assertFalse($root->hasChild($file));
    }
    
    
    public function testDeleteDirectory()
    {
        $dumper = new BenderDumper();
        $root = vfsStreamWrapper::getRoot();
        $root->addChild( new vfsStreamDirectory('dir') );
        $dir = $root->getChild('dir');
        $dir->addChild( new vfsStreamFile('one') );
        $dir->addChild( new vfsStreamFile('two') );
        $dir->addChild( new vfsStreamFile('three') );
        
        $this->assertTrue( $root->hasChild('dir') );
        $dumper->deleteDirectoryContent( vfsStream::url('example-dump/dir'), true );
        $this->assertFalse( $root->hasChild('dir') );
    }

}
