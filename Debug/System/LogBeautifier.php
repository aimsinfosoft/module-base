<?php
/**
 * Aimsinfosoft
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Aimsinfosoft.com license that is
 * available through the world-wide-web at this URL:
 * https://www.aimsinfosoft.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Aimsinfosoft
 * @package     Aimsinfosoft_Base
 * @copyright   Copyright (c) Aimsinfosoft (https://www.aimsinfosoft.com/)
 * @license     https://www.aimsinfosoft.com/LICENSE.txt
 */


namespace Aimsinfosoft\Base\Debug\System;

/**
 * @codeCoverageIgnore
 * @codingStandardsIgnoreFile
 */
class LogBeautifier
{
    /**
     * @var LogBeautifier
     */
    private static $instance;

    /**
     * beautify
     * @param mixed $var
     *
     * @return string
     */
    public function beautify($var)
    {
        switch (strtolower(gettype($var))) {
            case 'string':
                $result = '"' . $var . '"';
                break;
            case 'object':
                $result = $this->prepareObjectVar($var);
                break;
            case 'array':
                $result = $this->prepareArrayVar($var);
                break;
            case 'boolean':
                $result = $var ? 'true' : 'false';
                break;
            case 'null':
                $result = 'null';
                break;
            case 'resource':
            case 'resource (closed)':
                $result = 'resource';
                break;
            default:
                $result = $var;
                break;
        }

        return $result;
    }

    /**
     * array key
     * @param string|int $key
     *
     * @return string
     */
    private function arrayKey($key)
    {
        if (is_string($key)) {
            return '"' . $key . '"';
        }

        return $key;
    }

    /**
     * array simple type
     * @param mixed $var
     *
     * @return string|int|float
     */
    private function arraySimpleType($var)
    {
        switch (strtolower(gettype($var))) {
            case 'string':
                return $var;
            case 'boolean':
                return $var ? 'true' : 'false';
            case 'null':
                return 'null';
            case 'integer':
            case 'float':
            case 'double':
                return $var;
            case 'resource':
            case 'resource (closed)':
                return 'resource';
            default:
                return 'Unknown variable type!';
        }
    }

    /**
     * prepare array varibale
     * @param array $arrayVar
     * @param int $depth
     *
     * @return string
     */
    private function prepareArrayVar($arrayVar, $depth = 1)
    {
        $result = "array: " . count($arrayVar) . " [\n";

        foreach ($arrayVar as $arrayKey => $var) {
            switch (strtolower(gettype($var))) {
                case 'array':
                    $result .= str_repeat(' ', $depth * 4)
                        . $this->arrayKey($arrayKey)
                        . ' => ' . $this->prepareArrayVar($var, $depth + 1) . "\n";
                    break;
                case 'object':
                    $result .= str_repeat(' ', $depth * 4)
                        . $this->arrayKey($arrayKey)
                        . ' => ' . $this->prepareObjectVar($var, $depth + 1) . "\n";
                    break;
                default:
                    $result .= str_repeat(' ', $depth * 4)
                        . $this->arrayKey($arrayKey)
                        . ' => ' . $this->arraySimpleType($var) . "\n";
                    break;
            }
        }
        $result .= str_repeat(' ', ($depth - 1) * 4) . "]";

        return $result;
    }

    /**
     * prepare object var
     * @param AimsinfosoftDump $object
     * @param int $depth
     *
     * @return string
     */
    private function prepareObjectVar($object, $depth = 1)
    {
        if ($depth === 1) {
            $result = 'Object ' . $object->className . "{\n";
        } else {
            $result = 'Object ' . $object->shortClassName . "{\n";
        }

        $result .= str_repeat(' ', $depth * 4) . "Properties => "
            . $this->prepareArrayVar($object->properties, $depth + 1) . "\n";
        $result .= str_repeat(' ', ($depth - 1) * 4) . '}';

        return $result;
    }

    /**
     * get instance
     * @return LogBeautifier
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
