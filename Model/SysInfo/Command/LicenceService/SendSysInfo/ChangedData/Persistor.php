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

namespace Aimsinfosoft\Base\Model\SysInfo\Command\LicenceService\SendSysInfo\ChangedData;

use Aimsinfosoft\Base\Model\SysInfo\Command\LicenceService\SendSysInfo\CacheStorage;
use Aimsinfosoft\Base\Model\SysInfo\Command\LicenceService\SendSysInfo\Checker;
use Aimsinfosoft\Base\Model\SysInfo\Command\LicenceService\SendSysInfo\Encryption;
use Aimsinfosoft\Base\Model\SysInfo\Provider\Collector;
use Aimsinfosoft\Base\Model\SysInfo\Provider\CollectorPool;

class Persistor
{
    /**
     * @var Collector
     */
    private $collector;

    /**
     * @var Checker
     */
    private $checker;

    /**
     * @var CacheStorage
     */
    private $cacheStorage;

    /**
     * @var Encryption
     */
    private $encryption;

    /**
     * Persistor constructor.
     *
     * @param Collector $collector
     * @param Checker $checker
     * @param CacheStorage $cacheStorage
     * @param Encryption $encryption
     */
    public function __construct(
        Collector $collector,
        Checker $checker,
        CacheStorage $cacheStorage,
        Encryption $encryption
    ) {
        $this->collector = $collector;
        $this->checker = $checker;
        $this->cacheStorage = $cacheStorage;
        $this->encryption = $encryption;
    }

    /**
     * Get changed data from the collector.
     *
     * @return array
     */
    public function get(): array
    {
        $data = $this->collector->collect(CollectorPool::LICENCE_SERVICE_GROUP);
        $changedData = [];
        foreach ($data as $sysInfoName => $sysInfo) {
            $cacheValue = $this->cacheStorage->get($sysInfoName);
            $newValue = $this->encryption->encryptArray($sysInfo);
            if ($this->checker->isChangedCacheValue($cacheValue, $newValue)) {
                $changedData[$sysInfoName] = $sysInfo;
            }
        }

        return $changedData;
    }

    /**
     * Save changed data to the cache.
     *
     * @param array $data
     * @return void
     */
    public function save(array $data): void
    {
        foreach ($data as $sysInfoName => $sysInfo) {
            $encryptionValue = $this->encryption->encryptArray($sysInfo);
            $this->cacheStorage->set($sysInfoName, $encryptionValue);
        }
    }
}
