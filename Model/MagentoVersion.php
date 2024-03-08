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

namespace Aimsinfosoft\Base\Model;

/**
 * Class MagentoVersion
 *
 * The MagentoVersion class is used for faster retrieving the Magento version.
 *
 * @package Aimsinfosoft\Base\Model
 */
class MagentoVersion
{
    /**
     * Cache key for storing Magento version.
     */
    public const MAGENTO_VERSION = 'Aimsinfosoft_magento_version';

    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    private $productMetadata;

    /**
     * @var \Magento\Framework\App\Cache\Type\Config
     */
    private $cache;

    /**
     * @var string|null
     */
    private $magentoVersion;

    /**
     * MagentoVersion constructor.
     *
     * @param \Magento\Framework\App\Cache\Type\Config $cache
     * @param \Magento\Framework\App\ProductMetadataInterface $productMetadata
     */
    public function __construct(
        \Magento\Framework\App\Cache\Type\Config $cache,
        \Magento\Framework\App\ProductMetadataInterface $productMetadata
    )
    {
        $this->productMetadata = $productMetadata;
        $this->cache = $cache;
    }

    /**
     * Get the Magento version.
     *
     * @return string
     */
    public function get(): string
    {
        if (!$this->magentoVersion
            && !($this->magentoVersion = $this->cache->load(self::MAGENTO_VERSION))
        ) {
            $this->magentoVersion = $this->productMetadata->getVersion();
            $this->cache->save($this->magentoVersion, self::MAGENTO_VERSION);
        }

        return $this->magentoVersion;
    }
}
