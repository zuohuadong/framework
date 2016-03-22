<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 16:16
 */
namespace Notadd\Payment\Commons;
use InvalidArgumentException;
/**
 * Class Helper
 * @package Notadd\Payment\Commons
 */
class Helper {
    /**
     * @param $str
     * @return mixed
     */
    public static function camelCase($str) {
        $str = self::convertToLowercase($str);
        return preg_replace_callback('/_([a-z])/', function ($match) {
            return strtoupper($match[1]);
        }, $str);
    }
    /**
     * @param $str
     * @return string
     */
    protected static function convertToLowercase($str) {
        $explodedStr = explode('_', $str);
        if(count($explodedStr) > 1) {
            foreach($explodedStr as $value) {
                $lowercasedStr[] = strtolower($value);
            }
            $str = implode('_', $lowercasedStr);
        }
        return $str;
    }
    /**
     * @param $number
     * @return bool
     */
    public static function validateLuhn($number) {
        $str = '';
        foreach(array_reverse(str_split($number)) as $i => $c) {
            $str .= $i % 2 ? $c * 2 : $c;
        }
        return array_sum(str_split($str)) % 10 === 0;
    }
    /**
     * @param $target
     * @param $parameters
     */
    public static function initialize($target, $parameters) {
        if(is_array($parameters)) {
            foreach($parameters as $key => $value) {
                $method = 'set' . ucfirst(static::camelCase($key));
                if(method_exists($target, $method)) {
                    $target->$method($value);
                }
            }
        }
    }
    /**
     * @param $className
     * @return string
     */
    public static function getGatewayShortName($className) {
        if(0 === strpos($className, '\\')) {
            $className = substr($className, 1);
        }
        if(0 === strpos($className, 'Notadd\\Payment\\')) {
            return trim(str_replace('\\', '_', substr($className, 8, -7)), '_');
        }
        return '\\' . $className;
    }
    /**
     * @param $shortName
     * @return mixed|string
     */
    public static function getGatewayClassName($shortName) {
        if(0 === strpos($shortName, '\\')) {
            return $shortName;
        }
        $shortName = str_replace('_', '\\', $shortName);
        if(false === strpos($shortName, '\\')) {
            $shortName .= '\\';
        }
        return '\\Notadd\\Payment\\' . $shortName . 'Gateway';
    }
    /**
     * @param $value
     * @return float
     */
    public static function toFloat($value) {
        if(!is_string($value) && !is_int($value) && !is_float($value)) {
            throw new InvalidArgumentException('Data type is not a valid decimal number.');
        }
        if(is_string($value)) {
            if(!preg_match('/^[-]?[0-9]+(\.[0-9]*)?$/', $value)) {
                throw new InvalidArgumentException('String is not a valid decimal number.');
            }
        }
        return (float)$value;
    }
}