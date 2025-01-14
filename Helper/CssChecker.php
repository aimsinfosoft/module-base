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


namespace Aimsinfosoft\Base\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\View\Asset\Repository;

class CssChecker extends AbstractHelper
{
    public const CSS_EXIST_PATH = 'css/styles-m.css';

    /**
     * @var \Magento\Framework\Filesystem
     */
    private $filesystem;

    /**
     * @var Repository
     */
    private $asset;

    /**
     * @var \Magento\Store\Model\App\Emulation
     */
    private $appEmulation;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var File
     */
    private $file;

    /**
     * CssChecker constructor.
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Helper\Context $context
     * @param Repository $asset
     * @param \Magento\Store\Model\App\Emulation $appEmulation
     * @param File $file
     * @param \Magento\Framework\Filesystem $filesystem
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Helper\Context $context,
        Repository $asset,
        \Magento\Store\Model\App\Emulation $appEmulation,
        \Magento\Framework\Filesystem\Io\File $file,
        \Magento\Framework\Filesystem $filesystem
    )
    {
        parent::__construct($context);

        $this->filesystem = $filesystem;
        $this->asset = $asset;
        $this->appEmulation = $appEmulation;
        $this->storeManager = $storeManager;
        $this->file = $file;
    }

    /**
     * get getCorruptedWebsites
     * @return array
     */
    public function getCorruptedWebsites()
    {
        $pubStaticPath = $this->filesystem->getDirectoryRead(DirectoryList::STATIC_VIEW)->getAbsolutePath();
        $failWebsites = [];
        $websites = [];

        foreach ($this->storeManager->getStores() as $store) {
            $websiteId = $store->getWebsiteId();
            $websiteName = $this->storeManager->getWebsite()->getName();

            if (in_array($websiteId, $websites, true)) {
                continue;
            }

            $websites[] = $websiteId;

            $storeId = $store->getStoreId();

            $this->appEmulation->startEnvironmentEmulation($storeId, \Magento\Framework\App\Area::AREA_FRONTEND, true);
            $urlPath = $this->asset->getUrlWithParams(self::CSS_EXIST_PATH, []);
            $this->appEmulation->stopEnvironmentEmulation();

            $cssPath = $pubStaticPath . strstr($urlPath, 'frontend/');

            if (!$this->file->fileExists($cssPath)) {
                $failWebsites[$websiteId] = $websiteName;
            }
        }

        return $failWebsites;
    }
}
