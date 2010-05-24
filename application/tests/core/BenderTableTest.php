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
 * Test class for BenderTable.
 */
class BenderTableTest extends BenderTest
{

    /**
     * test para la herencia
     */
    public function testExtends()
    {
        $person = new BenderTable(array('table' => 'person'));
        $user = new BenderTable(array('table' => 'user'));
        $user->setExtendedTable($person);
        
        $this->assertTrue( $user->getExtends() );
        $this->assertEquals( $person, $user->getExtendedTable() );
        $this->assertEquals('person',$user->getExtendedTableName());
        $this->assertEquals('user',$user->getTableName());
    }

    /**
     * Test para las many 2 many relations
     */
    public function testManyToMany()
    {
        $photo = new BenderTable(array('table' => 'photo'));
        $album = new BenderTable(array('table' => 'album'));
        $this->assertEquals(0,$photo->countManyToManyRelations());
        $this->assertFalse($photo->hasManyToManyRelations());
        $photo->addManyToManyRelation($album,'albums_photos');
        
        $this->assertTrue($photo->hasManyToManyRelations());
        $this->assertEquals(1,$photo->countManyToManyRelations());
        $this->assertType('array',$photo->getManyToManyRelations());
        $this->assertType('array',$photo->getManyToManyRelation('albums_photos'));
        $this->assertNull($photo->getManyToManyRelation('unexistent_relation'));
    }
    
    /**
     * 
     * @expectedException BenderDatabaseException
     */
    public function testExceptionManyToMany()
    {
        $photo = new BenderTable(array('table' => 'photo'));
        $album = new BenderTable(array('table' => 'album'));
        $photo->addManyToManyRelation($album,'albums_photos_error');
    }
    
}
