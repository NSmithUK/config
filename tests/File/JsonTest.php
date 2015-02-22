<?php
namespace Noodlehaus\File\Test;

use Noodlehaus\File\Json;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-04-21 at 22:37:22.
 */
class JsonTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Json
     */
    protected $json;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->json = new Json();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers                   Noodlehaus\File\Json::load
     * @expectedException        Noodlehaus\Exception\ParseException
     * @expectedExceptionMessage Syntax error
     */
    public function testLoadInvalidJson()
    {
        $this->json->load(__DIR__ . '/../mocks/fail/error.json');
    }

    /**
     * @covers Noodlehaus\File\Json::load
     */
    public function testLoadJson()
    {
        $actual = $this->json->load(__DIR__ . '/../mocks/pass/config.json');
        $this->assertEquals('localhost', $actual['host']);
        $this->assertEquals('80', $actual['port']);
    }
}