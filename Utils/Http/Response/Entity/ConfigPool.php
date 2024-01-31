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

namespace Aimsinfosoft\Base\Utils\Http\Response\Entity;

use Aimsinfosoft\Base\Utils\Http\Url\UrlComparator;
use Magento\Framework\Exception\NotFoundException;

/**
 * Class ConfigPool
 * @package Aimsinfosoft\Base\Utils\Http\Response\Entity
 *
 * Represents a pool of configurations for HTTP response entities.
 */
class ConfigPool
{
    /**
     * @var Config[]
     */
    private $configs;

    /**
     * @var UrlComparator
     */
    private $urlComparator;

    /**
     * ConfigPool constructor.
     *
     * @param UrlComparator $urlComparator
     * @param array $configs
     */
    public function __construct(
        UrlComparator $urlComparator,
        array $configs
    )
    {
        $this->checkConfigInstance($configs);
        $this->urlComparator = $urlComparator;
        $this->configs = $configs;
    }

    /**
     * Get the configuration for the given path.
     *
     * @param string $path
     * @return Config
     * @throws NotFoundException
     */
    public function get(string $path): Config
    {
        $result = false;
        foreach ($this->configs as $configPath => $config) {
            if ($this->urlComparator->isEqual($path, $configPath)) {
                $result = $config;
                break;
            }
        }

        if (!$result) {
            throw new NotFoundException(__('Entity config not found for given path %1.', $path));
        }

        return $result;
    }

    /**
     * Check if the provided configurations are instances of Config class.
     *
     * @param array $configs
     * @throws \InvalidArgumentException
     */
    private function checkConfigInstance(array $configs): void
    {
        foreach ($configs as $configPath => $config) {
            if (!$config instanceof Config) {
                throw new \InvalidArgumentException(
                    'The config instance "' . $configPath . '" must be ' . Config::class
                );
            }
        }
    }
}
