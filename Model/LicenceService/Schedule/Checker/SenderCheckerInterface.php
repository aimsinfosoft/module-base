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

namespace Aimsinfosoft\Base\Model\LicenceService\Schedule\Checker;

/**
 * Interface SenderCheckerInterface
 *
 * @package Aimsinfosoft\Base\Model\LicenceService\Schedule\Checker
 */
interface SenderCheckerInterface
{
    /**
     * Check if it is necessary to send based on the specified flag.
     *
     * @param string $flag
     *   The identifier or flag for the scheduled task.
     *
     * @return bool
     *   Returns true if it is necessary to send, false otherwise.
     */
    public function isNeedToSend(string $flag): bool;
}
