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

namespace Aimsinfosoft\Base\Model\Import\Behavior;

/**
 * Interface BehaviorProviderInterface
 * @package Aimsinfosoft\Base\Model\Import\Behavior
 */
interface BehaviorProviderInterface
{
    /**
     * Get the behavior instance based on the behavior code.
     *
     * @param string $behaviorCode
     * @return \Aimsinfosoft\Base\Model\Import\Behavior\BehaviorInterface
     * @throws \Aimsinfosoft\Base\Exceptions\NonExistentImportBehavior
     */
    public function getBehavior($behaviorCode);
}
