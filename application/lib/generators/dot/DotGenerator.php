<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


abstract class DotGenerator extends CodeGenerator
{

    /**
     * mysql data types => php data types
     */
    protected $dataTypes = array(
    );
    
    /**
     * mysql data types => type
     */
    protected $types = array(
    );
    
    /**
     * mysql data types => custom format
     */
     protected $formats = array(
     );
    
    /**
     * mysql data types => if need to cast the data
     */
    protected $castDataTypes = array(
    );

}
