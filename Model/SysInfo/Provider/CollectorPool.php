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

namespace Aimsinfosoft\Base\Model\SysInfo\Provider;

use Aimsinfosoft\Base\Model\SysInfo\Provider\Collector\CollectorInterface;
use Magento\Framework\Exception\NotFoundException;

/**
 * Class CollectorPool
 *
 * CollectorPool class responsible for managing and providing collectors based on group names.
 *
 * @since 1.0.0
 */
class CollectorPool
{
    public const LICENCE_SERVICE_GROUP = 'licenceService';
    public const SYS_INFO_SERVICE_GROUP = 'sysInfoService';

    /**
     * @var CollectorInterface[][]
     */
    private $collectors;

    /**
     * CollectorPool constructor.
     *
     * @param array $collectors
     */
    public function __construct(
        array $collectors
    )
    {
        $this->checkProviderInstance($collectors);
        $this->collectors = $collectors;
    }

    /**
     * Get collectors based on the specified group name.
     *
     * @param string $groupName
     * @return CollectorInterface[]
     * @throws NotFoundException
     */
    public function get(string $groupName): array
    {
        if (!isset($this->collectors[$groupName])) {
            throw new NotFoundException(
                __('The "%1" group name isn\'t defined. Verify the executor and try again.', $groupName)
            );
        }

        return $this->collectors[$groupName];
    }

    /**
     * Check if provided collectors implement the required interface.
     *
     * @param array $collectors
     * @throws \InvalidArgumentException
     */
    private function checkProviderInstance(array $collectors): void
    {
        foreach ($collectors as $groupName => $groupCollectors) {
            foreach ($groupCollectors as $collectorName => $collector) {
                if (!$collector instanceof CollectorInterface) {
                    throw new \InvalidArgumentException(
                        'The collector instance "' . $collectorName . '" from group "'
                        . $groupName . '" must implement ' . CollectorInterface::class
                    );
                }
            }
        }
    }
}
