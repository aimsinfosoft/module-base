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


namespace Aimsinfosoft\Base\Model\Import\Mapping;

/**
 * Class Mapping
 * @package Aimsinfosoft\Base\Model\Import\Mapping
 */
class Mapping implements MappingInterface
{
    /**
     * @var string
     */
    protected $masterAttributeCode = '';

    /**
     * FYI: column names pattern [a-z][a-z0-9_]*
     * @var array
     */
    protected $mappings = [
        /**
         * csv_column_name => model_column_name
         * model_column_name (numeric key means model_column_name => model_column_name)
         **/
    ];

    /**
     * @var array
     */
    private $processedMapping;

    /**
     * Get valid column names.
     *
     * @return array
     */
    public function getValidColumnNames()
    {
        return array_keys($this->processedMapping());
    }

    /**
     * Get the mapped field for a given column name.
     *
     * @param string $columnName
     * @return mixed
     * @throws \Aimsinfosoft\Base\Exceptions\MappingColumnDoesntExist
     */
    public function getMappedField($columnName)
    {
        if (!isset($this->processedMapping()[$columnName])) {
            throw new \Aimsinfosoft\Base\Exceptions\MappingColumnDoesntExist();
        }

        return $this->processedMapping()[$columnName];
    }

    /**
     * Get the master attribute code.
     *
     * @return string
     * @throws \Aimsinfosoft\Base\Exceptions\MasterAttributeCodeDoesntSet
     */
    public function getMasterAttributeCode()
    {
        if (empty($this->masterAttributeCode)) {
            throw new \Aimsinfosoft\Base\Exceptions\MasterAttributeCodeDoesntSet();
        }

        return $this->masterAttributeCode;
    }

    /**
     * Processed mapping data.
     *
     * @return array
     */
    public function processedMapping()
    {
        if (null === $this->processedMapping) {
            $this->processedMapping = [];
            foreach ($this->mappings as $csvField => $field) {
                if (is_numeric($csvField)) {
                    $this->processedMapping[$field] = $field;
                } else {
                    $this->processedMapping[$csvField] = $field;
                }
            }
        }

        return $this->processedMapping;
    }
}
