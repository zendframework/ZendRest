<?php
namespace ZendRest\Client;

/**
 *
 * @author zhangzhi
 *
 */
interface ResultInterface
{

    public function __construct($data);

    public function isSuccess();

    public function isError();

    public function toArray();
}

?>