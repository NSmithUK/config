<?php
namespace Noodlehaus;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-04-21 at 22:37:22.
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers                   Noodlehaus\Config::load
     * @covers                   Noodlehaus\Config::loadJson
     * @expectedException        Exception
     * @expectedExceptionMessage JSON parse error
     */
    public function testLoadWithInvalidJson()
    {
        $config = Config::load(__DIR__ . '/mocks/error.json');
    }

    /**
     * @covers                   Noodlehaus\Config::load
     * @covers                   Noodlehaus\Config::loadIni
     * @expectedException        Exception
     * @expectedExceptionMessage INI parse error
     */
    public function testLoadWithInvalidIni()
    {
        $config = Config::load(__DIR__ . '/mocks/error.ini');
    }

    /**
     * @covers                   Noodlehaus\Config::load
     * @covers                   Noodlehaus\Config::loadPhp
     * @expectedException        Exception
     * @expectedExceptionMessage PHP file does not return an array
     */
    public function testLoadWithInvalidPhp()
    {
        $config = Config::load(__DIR__ . '/mocks/error.php');
    }

    /**
     * @covers                   Noodlehaus\Config::load
     * @covers                   Noodlehaus\Config::loadPhp
     * @expectedException        Exception
     * @expectedExceptionMessage PHP file threw an exception
     */
    public function testLoadWithExceptionalPhp()
    {
        $config = Config::load(__DIR__ . '/mocks/error-exception.php');
    }

    /**
     * @covers                   Noodlehaus\Config::__construct
     * @expectedException        Exception
     * @expectedExceptionMessage Unsupported configuration format
     */
    public function testLoadWithUnsupportedFormat()
    {
        $config = Config::load(__DIR__ . '/mocks/error.yaml');
    }

    /**
     * @covers                   Noodlehaus\Config::__construct
     * @expectedException        Exception
     * @expectedExceptionMessage Configuration file: [ladadeedee] cannot be found
     */
    public function testConstructWithInvalidPath()
    {
        $config = new Config('ladadeedee');
    }

    /**
     * @covers       Noodlehaus\Config::__construct
     * @covers       Noodlehaus\Config::loadPhp
     */
    public function testConstructWithPhpArray()
    {
        $config = new Config(__DIR__ . '/mocks/config.php');
        $this->assertEquals('localhost', $config->get('host'));
        $this->assertEquals('80', $config->get('port'));
    }

    /**
     * @covers       Noodlehaus\Config::__construct
     * @covers       Noodlehaus\Config::loadPhp
     */
    public function testConstructWithPhpCallable()
    {
        $config = new Config(__DIR__ . '/mocks/config-exec.php');
        $this->assertEquals('localhost', $config->get('host'));
        $this->assertEquals('80', $config->get('port'));
    }

    /**
     * @covers       Noodlehaus\Config::__construct
     * @covers       Noodlehaus\Config::loadIni
     */
    public function testConstructWithIni()
    {
        $config = new Config(__DIR__ . '/mocks/config.ini');
        $this->assertEquals('localhost', $config->get('host'));
        $this->assertEquals('80', $config->get('port'));
    }

    /**
     * @covers       Noodlehaus\Config::__construct
     * @covers       Noodlehaus\Config::loadJson
     */
    public function testConstructWithJson()
    {
        $config = new Config(__DIR__ . '/mocks/config.json');
        $this->assertEquals('localhost', $config->get('host'));
        $this->assertEquals('80', $config->get('port'));
    }

    /**
     * @covers       Noodlehaus\Config::get
     * @dataProvider providerConfig
     */
    public function testGet($config)
    {
        $this->assertEquals('localhost', $config->get('host'));
    }

    /**
     * @covers       Noodlehaus\Config::get
     * @dataProvider providerConfig
     */
    public function testGetWithDefaultValue($config)
    {
        $this->assertEquals(128, $config->get('ttl', 128));
    }

    /**
     * @covers       Noodlehaus\Config::get
     * @dataProvider providerConfig
     */
    public function testGetNestedKey($config)
    {
        $this->assertEquals('configuration', $config->get('application.name'));
    }

    /**
     * @covers       Noodlehaus\Config::get
     * @dataProvider providerConfig
     */
    public function testGetNestedKeyWithDefaultValue($config)
    {
        $this->assertEquals(128, $config->get('application.ttl', 128));
    }

    /**
     * @covers       Noodlehaus\Config::get
     * @dataProvider providerConfig
     */
    public function testGetNonexistentKey($config)
    {
        $this->assertNull($config->get('proxy'));
    }

    /**
     * @covers       Noodlehaus\Config::get
     * @dataProvider providerConfig
     */
    public function testGetNonexistentNestedKey($config)
    {
        $this->assertNull($config->get('proxy.name'));
    }

    /**
     * @covers       Noodlehaus\Config::get
     * @dataProvider providerConfig
     */
    public function testGetReturnsArray($config)
    {
        $this->assertArrayHasKey('name', $config->get('application'));
        $this->assertEquals('configuration', $config->get('application.name'));
        $this->assertCount(2, $config->get('application'));
    }

    /**
     * @covers       Noodlehaus\Config::set
     * @dataProvider providerConfig
     */
    public function testSet($config)
    {
        $config->set('region', 'apac');
        $this->assertEquals('apac', $config->get('region'));
    }

    /**
     * @covers       Noodlehaus\Config::set
     * @dataProvider providerConfig
     */
    public function testSetNestedKey($config)
    {
        $config->set('location.country', 'Singapore');
        $this->assertEquals('Singapore', $config->get('location.country'));
    }

    /**
     * @covers       Noodlehaus\Config::set
     * @dataProvider providerConfig
     */
    public function testSetArray($config)
    {
        $config->set('database', array(
            'host' => 'localhost',
            'name' => 'mydatabase'
        ));
        $this->assertTrue(is_array($config->get('database')));
        $this->assertEquals('localhost', $config->get('database.host'));
    }

    /**
     * @covers       Noodlehaus\Config::set
     * @dataProvider providerConfig
     */
    public function testSetAndUnsetArray($config)
    {
        $config->set('database', array(
            'host' => 'localhost',
            'name' => 'mydatabase'
        ));
        $this->assertTrue(is_array($config->get('database')));
        $this->assertEquals('localhost', $config->get('database.host'));
        $config->set('database.host', null);
        $this->assertNull($config->get('database.host'));
        $config->set('database', null);
        $this->assertNull($config->get('database'));
    }

    /**
     * @covers       Noodlehaus\Config::offsetGet
     * @dataProvider providerConfig
     */
    public function testOffsetGet($config)
    {
        $this->assertEquals('localhost', $config['host']);
    }

    /**
     * @covers       Noodlehaus\Config::offsetGet
     * @dataProvider providerConfig
     */
    public function testOffsetGetNestedKey($config)
    {
        $this->assertEquals('configuration', $config['application.name']);
    }

    /**
     * @covers       Noodlehaus\Config::offsetExists
     * @dataProvider providerConfig
     */
    public function testOffsetExists($config)
    {
        $this->assertTrue(isset($config['host']));
    }

    /**
     * @covers       Noodlehaus\Config::offsetExists
     * @dataProvider providerConfig
     */
    public function testOffsetExistsReturnsFalseOnNonexistentKey($config)
    {
        $this->assertFalse(isset($config['database']));
    }

    /**
     * @covers       Noodlehaus\Config::offsetSet
     * @dataProvider providerConfig
     */
    public function testOffsetSet($config)
    {
        $config['newkey'] = 'newvalue';
        $this->assertEquals('newvalue', $config['newkey']);
    }

    /**
     * @covers       Noodlehaus\Config::offsetUnset
     * @dataProvider providerConfig
     */
    public function testOffsetUnset($config)
    {
        unset($config['application']);
        $this->assertNull($config['application']);
    }

    /**
     * Provides names of example configuration files
     */
    public function providerConfig()
    {
        return array(
            array(new Config(__DIR__ . '/mocks/config.ini')),
            array(new Config(__DIR__ . '/mocks/config.json')),
            array(new Config(__DIR__ . '/mocks/config-exec.php')),
            array(new Config(__DIR__ . '/mocks/config.php'))
        );
    }

}
