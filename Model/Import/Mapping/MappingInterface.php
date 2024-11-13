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
 * Interface MappingInterface
 * @since 1.4.6
 */
interface MappingInterface
{
    /**
     * Get valid column names.
     *
     * @return array
     */
    public function getValidColumnNames();

    /**
     * Get the mapped field for a given column name.
     *
     * @param string $columnName
     * @return string|bool
     * @throws \Aimsinfosoft\Base\Exceptions\MappingColumnDoesntExist
     */
    public function getMappedField($columnName);

    /**
     * Get the master attribute code.
     *
     * @return string
     * @throws \Aimsinfosoft\Base\Exceptions\MasterAttributeCodeDoesntSet
     */
    public function getMasterAttributeCode();
}
