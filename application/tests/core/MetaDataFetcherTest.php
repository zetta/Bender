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
class MetaDataFetcherTest extends BenderTest
{
    
    /**
     * Probando el metodo de obtener los argumentos de un metodo 
     * desde este mismo metodo
     * @param string $myString
     * @param int $myInt
     * @param array $myArray [OPTIONAl]
     * @param string $otherString [OPTIONAL] (default)
     * 
     */
    public function testGetArguments()
    {
       $class = new ReflectionClass(__CLASS__);
       $method = $class->getMethod('testGetArguments');
       $md = new MetaDataFetcher();
       $meta = $md->getArguments($method);
       $this->assertType('array',$meta);
       $this->assertEquals(4, count($meta));
    }
    

}
