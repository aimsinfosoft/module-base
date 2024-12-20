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

use Aimsinfosoft\Base\Model\SysInfo\InfoProviderInterface;
use Magento\Config\Model\ResourceModel\Config\Data\CollectionFactory as ConfigCollectionFactory;

/**
 * Class Config
 *
 * Config class responsible for providing system configuration information.
 *
 * @category    Aimsinfosoft
 * @package     Aimsinfosoft_Base
 * @author      Aimsinfosoft
 * @license     https://www.aimsinfosoft.com/LICENSE.txt
 */
class Config implements InfoProviderInterface
{
    public const CONFIG_PATH_KEY = 'path';
    public const CONFIG_VALUE_KEY = 'value';

    /**
     * @var ConfigCollectionFactory
     */
    private $configCollectionFactory;

    /**
     * Config constructor.
     *
     * @param ConfigCollectionFactory $configCollectionFactory
     */
    public function __construct(
        ConfigCollectionFactory $configCollectionFactory
    )
    {
        $this->configCollectionFactory = $configCollectionFactory;
    }

    /**
     * Generate system configuration information.
     *
     * @return array
     */
    public function generate(): array
    {
        $configData = [];

        $configCollection = $this->configCollectionFactory->create()
            ->addFieldToSelect([self::CONFIG_PATH_KEY, self::CONFIG_VALUE_KEY]);

        foreach ($this->getPathConditions() as $condition) {
            $configCollection->addFieldToFilter(self::CONFIG_PATH_KEY, $condition);
        }

        foreach ($configCollection->getData() as $config) {
            $path = $this->preparePath($config[self::CONFIG_PATH_KEY]);
            $configData[$path] = $config[self::CONFIG_VALUE_KEY];
        }

        return $configData;
    }

    /**
     * Get path conditions for filtering system configuration.
     *
     * @return array
     */
    protected function getPathConditions(): array
    {
        return [
            ['like' => 'am%'],
            ['nlike' => '%token%']
        ];
    }

    /**
     * Prepare path for system configuration.
     *
     * @param string $path
     * @return string
     */
    private function preparePath(string $path): string
    {
        return str_replace('/', '_', $path);
    }
}
