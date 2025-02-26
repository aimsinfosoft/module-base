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

use Magento\Framework\Exception\NotFoundException;

/**
 * Class Collector
 *
 * Collector class responsible for gathering information from various collectors
 * based on the specified group name.
 *
 * @since 1.0.0
 */
class Collector
{
    /**
     * @var CollectorPool
     */
    private $collectorPool;

    /**
     * Collector constructor.
     *
     * @param CollectorPool $collectorPool
     */
    public function __construct(CollectorPool $collectorPool)
    {
        $this->collectorPool = $collectorPool;
    }

    /**
     * Collect information from various collectors based on the specified group name.
     *
     * @param string $groupName
     * @return array
     * @throws NotFoundException
     */
    public function collect(string $groupName): array
    {
        $data = [];
        $collectors = $this->collectorPool->get($groupName);
        foreach ($collectors as $collectorName => $collector) {
            $data[$collectorName] = $collector->get();
        }

        return $data;
    }
}
