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

namespace Aimsinfosoft\Base\Model\SysInfo\Data\RegisteredInstance;

use Aimsinfosoft\Base\Model\SimpleDataObject;
use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Class Instance
 * @since 1.0.0
 */
class Instance extends SimpleDataObject implements ExtensibleDataInterface
{
    public const DOMAIN = 'domain';
    public const SYSTEM_INSTANCE_KEY = 'system_instance_key';

    /**
     * Get the domain.
     *
     * @return string
     */
    public function getDomain(): string
    {
        return $this->getData(self::DOMAIN);
    }

    /**
     * Set the domain.
     *
     * @param string $domain
     * @return $this
     */
    public function setDomain(string $domain): self
    {
        return $this->setData(self::DOMAIN, $domain);
    }

    /**
     * Get the system instance key.
     *
     * @return string
     */
    public function getSystemInstanceKey(): string
    {
        return $this->getData(self::SYSTEM_INSTANCE_KEY);
    }

    /**
     * Set the system instance key.
     *
     * @param string $systemInstanceKey
     * @return $this
     */
    public function setSystemInstanceKey(string $systemInstanceKey): self
    {
        return $this->setData(self::SYSTEM_INSTANCE_KEY, $systemInstanceKey);
    }
}
