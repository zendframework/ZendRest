<?php
namespace ZendRest\Client;

use Zend\Json\Json;
use Zend\Json\Exception\RuntimeException;
use ZendRest\Client\Exception\ResultException;

/**
 *
 * @author zhangzhi
 *
 */
class JSONResult implements ResultInterface
{

    /**
     *
     * @var string
     */
    protected $_data;

    /**
     *
     * @var \stdClass
     */
    protected $_jsonObject;

    /**
     * error information
     *
     * @var string
     */
    protected $_errstr;

    /**
     * constructor
     *
     * @see \ZendRest\Client\ResultInterface::__construct()
     * @throws \ZendRest\Client\Exception\ResultException;
     *
     */
    public function __construct($data)
    {
        $this->_data = $data;

        try {
            $this->_jsonObject = Json::decode($this->_data);
        } catch (RuntimeException $e) {
            $message = $e->getMessage();
            throw new ResultException($message);
        }
    }

    /**
     * Get Property Overload
     *
     * @param string $name
     * @return null mixed
     */
    public function __get($name)
    {
        if (isset($this->_jsonObject->{$name})) {
            return $this->_jsonObject->{$name};
        }
        return null;
    }

    /**
     * Isset Overload
     *
     * @param string $name
     * @return boolean
     */
    public function __isset($name)
    {
        if (isset($this->_jsonObject->{$name})) {
            return true;
        }

        return false;
    }

    /**
     * Get response error code
     */
    public function getErrorCode()
    {
        return $this->_jsonObject->code;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \ZendRest\Client\ResultInterface::isSuccess()
     *
     */
    public function isSuccess()
    {
        $errorCode = $this->getErrorCode();
        if ($errorCode) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * (non-PHPdoc)
     *
     * @see \ZendRest\Client\ResultInterface::isError()
     *
     */
    public function isError()
    {
        $errorCode = $this->getErrorCode();
        if ($errorCode) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * (non-PHPdoc) @see \ZendRest\Client\ResultInterface::toArray()
     */
    public function toArray()
    {
        return Json::decode($this->_data, Json::TYPE_ARRAY);
    }
}

?>