<?php
namespace ZendRest\Client;

use ZendRest\Client;

/**
 * JSONResult test case.
 */
class JSONResultTest extends \PHPUnit_Framework_TestCase
{

    public static $path;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        self::$path = __DIR__ . '/TestAsset/responses/json/';
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testResponseIsSuccess()
    {
        $json = file_get_contents(self::$path . "returnString.json");
        $client = Client\Result::parse($json);
        $this->assertTrue($client->isSuccess());
    }

    public function testResponseIsError()
    {
        $json = file_get_contents(self::$path . "returnError.json");
        $client = Client\Result::parse($json);
        $this->assertTrue($client->isError());
    }

    public function testResponseString()
    {
        $json = file_get_contents(self::$path . "returnString.json");
        $client = Client\Result::parse($json);
        $this->assertEquals("string", $client->response);
    }

    public function testResponseInt()
    {
        $json = file_get_contents(self::$path . "returnInt.json");
        $client = Client\Result::parse($json);
        $this->assertEquals(123, $client->response);
    }

    public function testResponseArray()
    {
        $json = file_get_contents(self::$path . "returnArray.json");
        $client = Client\Result::parse($json);
        foreach ($client->response as $key => $value) {
            $resultArray[$key] = $value;
        }
        $this->assertEquals(array(
            "foo" => "bar",
            "baz" => 1,
            "key_1" => 0,
            "bat" => 123
        ), $resultArray);
    }

    public function testResponseObject()
    {
        $json = file_get_contents(self::$path . "returnObject.json");
        $client = Client\Result::parse($json);
        $this->assertEquals("bar", $client->response->foo);
        $this->assertEquals(1, $client->response->baz);
        $this->assertEquals(123, $client->response->bat);
        $this->assertEquals(0, $client->response->qux);
    }

    public function testResponseTrue()
    {
        $json = file_get_contents(self::$path . "returnTrue.json");
        $client = Client\Result::parse($json);
        $this->assertTrue($client->response);
    }

    public function testResponseFalse()
    {
        $json = file_get_contents(self::$path . "returnFalse.json");
        $client = Client\Result::parse($json);
        $this->assertFalse($client->response);
    }

    public function testResponseVoid()
    {
        $json = file_get_contents(self::$path . "returnVoid.json");
        $client = Client\Result::parse($json);
        $this->assertNull($client->response);
    }

    public function testResponseError()
    {
        $json = file_get_contents(self::$path . "returnError.json");
        $client = Client\Result::parse($json);
        $this->assertTrue($client->isError());
        $this->assertEquals("An error occurred.", $client->response->message);
    }

    public function testResponseIssetValidValue()
    {
        $json = file_get_contents(self::$path . "returnNestedArray.json");
        $client = Client\Result::parse($json);
        $this->assertTrue(isset($client->foo->bat->baz));
    }

    public function testResponseIssetInvalidValue()
    {
        $json = file_get_contents(self::$path . "returnNestedArray.json");
        $client = Client\Result::parse($json);
        $this->assertFalse(isset($client->foo->bat->bat));
    }

    public function testToArray()
    {
        $json = file_get_contents(self::$path . DIRECTORY_SEPARATOR . 'returnNestedArray.json');
        $result = Client\Result::parse($json);
        $array = $result->toArray();
        $expectedArray = $this->assertEquals("farama", $array["foo"]["bat"]["baz"]);
    }
}

