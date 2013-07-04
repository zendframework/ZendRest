<?php
namespace ZendRest\Client;

/**
 * parse the response to a Result Object, since we don't instance it,
 * we make it abstract
 *
 * @author zhangzhi
 *
 */
abstract class Result
{

    /**
     * Predefined data format as json string,it's the default format
     */
    const TYPE_JSON = 'JSONResult';

    /**
     * Predefined data format as xml string
     */
    const TYPE_XML = 'XMLResult';

    public static function parse($data, $dataFormat = self::TYPE_JSON)
    {
        $class = __NAMESPACE__ . '\\' . $dataFormat;
        return new $class($data);
    }
}

?>