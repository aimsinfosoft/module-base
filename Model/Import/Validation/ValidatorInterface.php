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


namespace Aimsinfosoft\Base\Model\Import\Validation;

/**
 * Interface ValidatorInterface
 * @package Aimsinfosoft\Base\Model\Import\Validation
 * @since 1.4.6
 */
interface ValidatorInterface
{
    /**
     * Validate a row of data.
     *
     * @param array $rowData
     * @param string $behavior
     *
     * @return array|bool
     * @throws \Aimsinfosoft\Base\Exceptions\StopValidation
     */
    public function validateRow(array $rowData, $behavior);

    /**
     * Get error messages.
     *
     * @return array
     */
    public function getErrorMessages();

    /**
     * Add a runtime error.
     *
     * @param string $message
     * @param int $level
     *
     * @return ValidatorInterface
     */
    public function addRuntimeError($message, $level);
}
