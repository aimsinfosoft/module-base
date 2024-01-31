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

declare(strict_types=1);

namespace Aimsinfosoft\Base\Model\SysInfo\Data;

use Aimsinfosoft\Base\Model\SimpleDataObject;
use Aimsinfosoft\Base\Model\SysInfo\Data\RegisteredInstance\Instance;
use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Class RegisteredInstance
 * @since 1.0.0
 */
class RegisteredInstance extends SimpleDataObject implements ExtensibleDataInterface
{
    public const INSTANCES = 'instances';
    public const CURRENT_INSTANCE = 'current_instance';

    /**
     * Get the current instance.
     *
     * @return \Aimsinfosoft\Base\Model\SysInfo\Data\RegisteredInstance\Instance|null
     */
    public function getCurrentInstance(): ?Instance
    {
        return $this->getData(self::CURRENT_INSTANCE);
    }

    /**
     * Set the current instance.
     *
     * @param \Aimsinfosoft\Base\Model\SysInfo\Data\RegisteredInstance\Instance|null $instance
     * @return $this
     */
    public function setCurrentInstance(?Instance $instance): self
    {
        return $this->setData(self::CURRENT_INSTANCE, $instance);
    }

    /**
     * Get the instances.
     *
     * @return \Aimsinfosoft\Base\Model\SysInfo\Data\RegisteredInstance\Instance[]
     */
    public function getInstances(): array
    {
        return $this->getData(self::INSTANCES) ?? [];
    }

    /**
     * Set the instances.
     *
     * @param \Aimsinfosoft\Base\Model\SysInfo\Data\RegisteredInstance\Instance[] $instances
     * @return $this
     */
    public function setInstances(array $instances): self
    {
        return $this->setData(self::INSTANCES, $instances);
    }
}
