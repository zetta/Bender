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
 * Test class for Formatter.
 * Generated by PHPUnit on 2010-05-13 at 15:58:06.
 */
class FormatterTest extends BenderTest
{

    const CAMELCASE = 'formattedStringForTest';
    const UPPERCAMELCASE = 'FormattedStringForTest';
    const UPPERCASE = 'FORMATTED_STRING_FOR_TEST';
    const UNDERSCORE = 'formatted_string_for_test';
    const SLUG = 'formatted-string-for-test';

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * UpperCaseToCammelCase().
     */
    public function testUpperCaseToCammelCase()
    {
        $this->assertEquals( self::CAMELCASE , Formatter::upperCaseToCammelCase( self::UPPERCASE ) );
    }

    /**
     * @todo Implement testUpperCaseToUpperCammelCase().
     */
    public function testUpperCaseToUpperCammelCase()
    {
        $this->assertEquals( self::UPPERCAMELCASE , Formatter::upperCaseToUpperCammelCase( self::UPPERCASE ) );
    }

    /**
     * @todo Implement testUpperCaseToUnderScore().
     */
    public function testUpperCaseToUnderScore()
    {
        $this->assertEquals( self::UNDERSCORE , Formatter::upperCaseToUnderScore( self::UPPERCASE ) );
    }

    /**
     * @todo Implement testUpperCaseToSlug().
     */
    public function testUpperCaseToSlug()
    {
        $this->assertEquals( self::SLUG , Formatter::upperCaseToSlug( self::UPPERCASE ) );
    }

    /**
     * @todo Implement testCamelCaseToUpperCase().
     */
    public function testCamelCaseToUpperCase()
    {
        $this->assertEquals( self::UPPERCASE , Formatter::camelCaseToUpperCase( self::CAMELCASE ) );
    }

    /**
     * @todo Implement testCamelCaseToUnderscore().
     */
    public function testCamelCaseToUnderscore()
    {
        $this->assertEquals( self::UNDERSCORE , Formatter::camelCaseToUnderscore( self::CAMELCASE ) );
    }

    /**
     * @todo Implement testCamelCaseToUpperCamelCase().
     */
    public function testCamelCaseToUpperCamelCase()
    {
        $this->assertEquals( self::UPPERCAMELCASE , Formatter::camelCaseToUpperCamelCase( self::CAMELCASE ) );
    }

    /**
     * @todo Implement testCamelCaseToSlug().
     */
    public function testCamelCaseToSlug()
    {
        $this->assertEquals( self::SLUG , Formatter::camelCaseToSlug( self::CAMELCASE ) );
    }

    /**
     * @todo Implement testUpperCamelCaseToCamelCase().
     */
    public function testUpperCamelCaseToCamelCase()
    {
        $this->assertEquals( self::CAMELCASE , Formatter::upperCamelCaseToCamelCase( self::UPPERCAMELCASE ) );
    }

    /**
     * @todo Implement testUpperCamelCaseToUpperCase().
     */
    public function testUpperCamelCaseToUpperCase()
    {
        $this->assertEquals( self::UPPERCASE , Formatter::upperCamelCaseToUpperCase( self::UPPERCAMELCASE ) );
    }

    /**
     * @todo Implement testUpperCamelCaseToUnderScore().
     */
    public function testUpperCamelCaseToUnderScore()
    {
        $this->assertEquals( self::UNDERSCORE , Formatter::upperCamelCaseToUnderScore( self::UPPERCAMELCASE ) );
    }

    /**
     * @todo Implement testUpperCamelCaseToSlug().
     */
    public function testUpperCamelCaseToSlug()
    {
        $this->assertEquals( self::SLUG , Formatter::upperCamelCaseToSlug( self::UPPERCAMELCASE ) );
    }

    /**
     * @todo Implement testUnderScoreToCamelCase().
     */
    public function testUnderScoreToCamelCase()
    {
        $this->assertEquals( self::CAMELCASE , Formatter::underScoreToCamelCase( self::UNDERSCORE ) );
    }

    /**
     * @todo Implement testUnderScoreToUpperCamelCase().
     */
    public function testUnderScoreToUpperCamelCase()
    {
        $this->assertEquals( self::UPPERCAMELCASE , Formatter::underScoreToUpperCamelCase( self::UNDERSCORE ) );
    }

    /**
     * @todo Implement testUnderScoreToUpperCase().
     */
    public function testUnderScoreToUpperCase()
    {
        $this->assertEquals( self::UPPERCASE , Formatter::underScoreToUpperCase( self::UNDERSCORE ) );
    }

    /**
     * @todo Implement testUnderScoreToSlug().
     */
    public function testUnderScoreToSlug()
    {
        $this->assertEquals( self::SLUG , Formatter::underScoreToSlug( self::UNDERSCORE ) );
    }

    /**
     * @todo Implement testSlugToUpperCase().
     */
    public function testSlugToUpperCase()
    {
        $this->assertEquals( self::UPPERCASE , Formatter::slugToUpperCase( self::SLUG ) );
    }

    /**
     * @todo Implement testSlugToUpperCamelCase().
     */
    public function testSlugToUpperCamelCase()
    {
        $this->assertEquals( self::UPPERCAMELCASE , Formatter::slugToUpperCamelCase( self::SLUG ) );
    }

    /**
     * @todo Implement testSlugToCamelCase().
     */
    public function testSlugToCamelCase()
    {
        $this->assertEquals( self::CAMELCASE , Formatter::slugToCamelCase( self::SLUG ) );
    }

    /**
     * @todo Implement testSlugToUnderScore().
     */
    public function testSlugToUnderScore()
    {
        $this->assertEquals( self::UNDERSCORE , Formatter::slugToUnderScore( self::SLUG ) );
    }
}


