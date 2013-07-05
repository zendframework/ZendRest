<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Rest
 */
namespace ZendRestTest;

use ZendRest\Client;

/**
 * Test cases for Zend_Rest_Server
 *
 * @category Zend
 * @package Zend_Rest
 * @subpackage UnitTests
 *             @group Zend_Rest
 *             @group Zend_Rest_Result
 */
class XMLResultTest extends \PHPUnit_Framework_TestCase
{

    public static $path;

    public function __construct($name = NULL, array $data = array(), $dataName = '')
    {
        self::$path = __DIR__ . '/TestAsset/responses/xml/';
        parent::__construct($name, $data, $dataName);
    }

    public function testResponseSuccess()
    {
        $xml = file_get_contents(self::$path . "returnString.xml");
        $client = Client\Result::parse($xml, Client\Result::TYPE_XML);
        $this->assertTrue($client->isSuccess());
    }

    public function testResponseIsError()
    {
        $xml = file_get_contents(self::$path . "returnError.xml");
        $client = Client\Result::parse($xml, Client\Result::TYPE_XML);
        $this->assertTrue($client->isError());
    }

    public function testResponseString()
    {
        $xml = file_get_contents(self::$path . "returnString.xml");
        $client = Client\Result::parse($xml, Client\Result::TYPE_XML);
        $this->assertEquals("string", $client->__toString());
    }

    public function testResponseInt()
    {
        $xml = file_get_contents(self::$path . "returnInt.xml");
        $client = Client\Result::parse($xml, Client\Result::TYPE_XML);
        $this->assertEquals("123", $client->__toString());
    }

    public function testResponseArray()
    {
        $xml = file_get_contents(self::$path . "returnArray.xml");
        // <foo>bar</foo><baz>1</baz><key_1>0</key_1><bat>123</bat>
        $client = Client\Result::parse($xml, Client\Result::TYPE_XML);
        foreach ($client as $key => $value) {
            $result_array[$key] = (string) $value;
        }
        $this->assertEquals(array(
            "foo" => "bar",
            "baz" => "1",
            "key_1" => "0",
            "bat" => "123",
            "status" => "success"
        ), $result_array);
    }

    public function testResponseObject()
    {
        $xml = file_get_contents(self::$path . "returnObject.xml");
        // <foo>bar</foo><baz>1</baz><bat>123</bat><qux>0</qux><status>success</status>
        $client = Client\Result::parse($xml, Client\Result::TYPE_XML);
        $this->assertEquals("bar", $client->foo());
        $this->assertEquals(1, $client->baz());
        $this->assertEquals(123, $client->bat());
        $this->assertEquals(0, $client->qux());
        $this->assertEquals("success", $client->status());
    }

    public function testResponseTrue()
    {
        $xml = file_get_contents(self::$path . "returnTrue.xml");
        $client = Client\Result::parse($xml, Client\Result::TYPE_XML);
        $this->assertTrue((bool) $client->response);
    }

    public function testResponseFalse()
    {
        $xml = file_get_contents(self::$path . "returnFalse.xml");
        $client = Client\Result::parse($xml, Client\Result::TYPE_XML);
        $this->assertFalse((bool) $client->response());
    }

    public function testResponseVoid()
    {
        $xml = file_get_contents(self::$path . "returnVoid.xml");
        $client = Client\Result::parse($xml, Client\Result::TYPE_XML);
        $this->assertEquals(null, $client->response());
    }

    public function testResponseException()
    {
        $xml = file_get_contents(self::$path . "returnError.xml");
        $client = Client\Result::parse($xml, Client\Result::TYPE_XML);
        $this->assertTrue($client->isError());
    }

    public function testGetXpathValue()
    {
        $xml = file_get_contents(self::$path . DIRECTORY_SEPARATOR . 'returnNestedArray.xml');
        $result = Client\Result::parse($xml, Client\Result::TYPE_XML);
        $key_1 = $result->key_1();
        $this->assertEquals(0, $key_1);
    }

    public function testGetXpathValueMissing()
    {
        $xml = file_get_contents(self::$path . DIRECTORY_SEPARATOR . 'returnNestedArray.xml');
        $result = Client\Result::parse($xml, Client\Result::TYPE_XML);
        $lola = $result->lola;
        $this->assertNull($lola);
    }

    public function testGetXpathValueArray()
    {
        $xml = file_get_contents(self::$path . DIRECTORY_SEPARATOR . 'returnNestedArray.xml');
        $result = Client\Result::parse($xml, Client\Result::TYPE_XML);
        $baz = $result->baz;
        $this->assertTrue(is_array($baz), var_export($baz, 1));
        $this->assertEquals('1', (string) $baz[0]);
        $this->assertEquals('farama', (string) $baz[1]);
    }

    public function testIsset()
    {
        $xml = file_get_contents(self::$path . DIRECTORY_SEPARATOR . 'returnNestedArray.xml');
        $result = Client\Result::parse($xml, Client\Result::TYPE_XML);
        $this->assertTrue(isset($result->bar));
    }

    public function testIssetXpathValue()
    {
        $xml = file_get_contents(self::$path . DIRECTORY_SEPARATOR . 'returnNestedArray.xml');
        $result = Client\Result::parse($xml, Client\Result::TYPE_XML);
        $this->assertTrue(isset($result->baz));
    }

    public function testIssetInvalidValue()
    {
        $xml = file_get_contents(self::$path . DIRECTORY_SEPARATOR . 'returnNestedArray.xml');
        $result = Client\Result::parse($xml, Client\Result::TYPE_XML);
        $this->assertFalse(isset($result->lola));
    }

    public function testCall()
    {
        $xml = file_get_contents(self::$path . DIRECTORY_SEPARATOR . 'returnNestedArray.xml');
        $result = Client\Result::parse($xml, Client\Result::TYPE_XML);
        $returned = $result->key_1();
        $this->assertEquals(0, $returned);
    }

    public function testToArray()
    {
        $xml = file_get_contents(self::$path . DIRECTORY_SEPARATOR . 'returnNestedArray.xml');
        $result = Client\Result::parse($xml, Client\Result::TYPE_XML);
        $array = $result->toArray();
        $expectedArray = $this->assertEquals("farama", $array["foo"]["bat"]["baz"]);
    }
}
