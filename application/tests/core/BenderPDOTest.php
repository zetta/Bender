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
class BenderPDOTest extends BenderTest
{

    const SQLITE = 'sqlite:application/config/bender.db';
    
    /**
     * Probando el método, has class de bender
     */
    public function testFactory()
    {
        $driver = BenderPDO::factory('mysql:dbname=testdb;host=127.0.0.1');
        $this->assertType('BenderMysqlPDO',$driver);
        $this->assertType('BenderPDOInterface',$driver);
        $driver = BenderPDO::factory(self::SQLITE);
        $this->assertType('BenderSqlitePDO',$driver);
        $this->assertType('BenderPDOInterface',$driver);
    }
    
    /**
     * Probando si la clase sirve en si
     */
    public function testConstructor()
    {
        $pdo = new BenderPDO(self::SQLITE,'','');
        $this->assertType('BenderPDO',$pdo);
        $this->assertType('PDO',$pdo);
    }
    
    /**
     *
	 * @expectedException BenderDatabaseException
     */
    public function testExceptionFactory()
    {
        $driver = BenderPDO::factory('driverNotFound:');
    }
    

}
