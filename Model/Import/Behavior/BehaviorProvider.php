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
 * Class BehaviorProvider
 * @package Aimsinfosoft\Base\Model\Import\Behavior
 */
class BehaviorProvider implements BehaviorProviderInterface
{
    /**
     * @var \Aimsinfosoft\Base\Model\Import\Behavior\BehaviorInterface[]
     */
    private $behaviors;

    /**
     * BehaviorProvider constructor.
     *
     * @param array $behaviors
     * @throws \Aimsinfosoft\Base\Exceptions\WrongBehaviorInterface
     */
    public function __construct($behaviors)
    {
        $this->behaviors = [];
        foreach ($behaviors as $behaviorCode => $behavior) {
            if (!($behavior instanceof BehaviorInterface)) {
                throw new \Aimsinfosoft\Base\Exceptions\WrongBehaviorInterface();
            }

            $this->behaviors[$behaviorCode] = $behavior;
        }
    }

    /**
     * @inheritdoc
     *
     * @param string $behaviorCode
     * @return \Aimsinfosoft\Base\Model\Import\Behavior\BehaviorInterface
     * @throws \Aimsinfosoft\Base\Exceptions\NonExistentImportBehavior
     */
    public function getBehavior($behaviorCode)
    {
        if (!isset($this->behaviors[$behaviorCode])) {
            throw new \Aimsinfosoft\Base\Exceptions\NonExistentImportBehavior();
        }
        return $this->behaviors[$behaviorCode];
    }
}
