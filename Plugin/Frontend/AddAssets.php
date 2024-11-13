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

namespace Aimsinfosoft\Base\Plugin\Frontend;

use Aimsinfosoft\Base\Model\LessToCss\Config\Converter;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Asset\File;
use Magento\Framework\View\Page\Config;
use Magento\Framework\View\Page\Config\Renderer;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\View\Design\Theme\ThemeProviderInterface;
use Magento\Framework\App\ObjectManager;

/**
 * Class AddAssets
 *
 * Plugin class to add CSS assets for modules if less functionality for the theme is missing.
 */
class AddAssets
{
    public const CACHE_KEY = 'Aimsinfosoft_should_load_css_files';

    /**
     * @var string[]
     */
    protected $filesToCheck = ['css/styles-l.css', 'css/styles-m.css'];

    /**
     * @var Config
     */
    private $config;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var \Aimsinfosoft\Base\Model\LessToCss\Config
     */
    private $lessConfig;

    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    private $layout;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Magento\Framework\View\Design\FileResolution\Fallback\StaticFile
     */
    private $fallback;

    /**
     * @var ThemeProviderInterface
     */
    private $themeProvider;

    /**
     * @var \Magento\Framework\View\DesignInterface
     */
    private $design;

    /**
     * AddAssets constructor.
     *
     * @param Config $config
     * @param CacheInterface $cache
     * @param \Aimsinfosoft\Base\Model\LessToCss\Config $lessConfig
     * @param \Magento\Framework\View\LayoutInterface $layout
     * @param ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\View\Design\FileResolution\Fallback\StaticFile $fallback
     * @param \Magento\Framework\View\DesignInterface $design
     */
    public function __construct(
        Config $config,
        CacheInterface $cache,
        \Aimsinfosoft\Base\Model\LessToCss\Config $lessConfig,
        \Magento\Framework\View\LayoutInterface $layout,
        ScopeConfigInterface $scopeConfig,
        \Magento\Framework\View\Design\FileResolution\Fallback\StaticFile $fallback,
        \Magento\Framework\View\DesignInterface $design
    ) {
        $this->config = $config;
        $this->cache = $cache;
        $this->lessConfig = $lessConfig;
        $this->layout = $layout;
        $this->scopeConfig = $scopeConfig;
        $this->fallback = $fallback;
        $this->design = $design;
    }

    /**
     * Add CSS assets for modules if less functionality for the theme is missing.
     *
     * @param Renderer $subject
     * @param array $resultGroups
     * @return array
     */
    public function beforeRenderAssets(Renderer $subject, $resultGroups = [])
    {
        $theme = $this->design->getDesignTheme();
        $cacheKey = self::CACHE_KEY . $theme->getCode();
        /** @var bool|int $shouldLoad */
        $shouldLoad = $this->cache->load($cacheKey);
        if ($shouldLoad === false) {
            $shouldLoad = $this->isShouldLoadCss();
            $this->cache->save($shouldLoad, $cacheKey);
        }

        if ($shouldLoad) {
            $modulesConfig = $this->lessConfig->get();
            $currentHandles = $this->layout->getUpdate()->getHandles();
            foreach ($modulesConfig as $moduleName => $moduleConfig) {
                foreach ($moduleConfig[Converter::IFCONFIG] as $configPath) {
                    if (!$this->scopeConfig->isSetFlag($configPath, ScopeInterface::SCOPE_STORE)) {
                        continue 2;
                    }
                }
                foreach ($moduleConfig[Converter::HANDLES] as $handle) {
                    if (in_array($handle, $currentHandles, true)) {
                        $this->addCss($moduleName, $moduleConfig[Converter::CSS_OPTIONS]);
                        continue 2;
                    }
                }
            }
        }

        return [$resultGroups];
    }

    /**
     * Add CSS asset for the specified module.
     *
     * @param string $moduleName
     * @param array $moduleConfig
     */
    private function addCss($moduleName, $moduleConfig)
    {
        // i.e. 'Aimsinfosoft_Checkout::css/styles.css'
        $cssPath = $moduleName . '::' . $moduleConfig[Converter::CSS_OPTION_PATH]
            . '/' . $moduleConfig[Converter::CSS_OPTION_FILENAME] . '.css';

        $this->config->addPageAsset($cssPath);
    }

    /**
     * Check if CSS should be loaded.
     *
     * @return int
     */
    private function isShouldLoadCss()
    {
        /** @var \Magento\Framework\View\Asset\GroupedCollection $collection */
        $collection = $this->config->getAssetCollection();
        $found = 0;
        $shouldFind = count($this->filesToCheck);
        /** @var File $item */
        foreach ($collection->getAll() as $item) {
            if ($item instanceof File
                && in_array($item->getFilePath(), $this->filesToCheck, true)
            ) {
                $found++;
                if ($found === $shouldFind && $this->findLess($item) === false) {
                    //styles with the usual name found, but these styles don't have less
                    return 1;
                }
            }
        }

        return (int)($found < $shouldFind);
    }

    /**
     * Find the less file for the specified CSS asset.
     *
     * @param File $asset
     * @return bool|string
     */
    private function findLess(File $asset)
    {
        try {
            /** @var \Magento\Framework\View\Asset\File\FallbackContext $context */
            $context = $asset->getContext();

            $themeModel = $this->getThemeProvider()->getThemeByFullPath(
                $context->getAreaCode() . '/' . $context->getThemePath()
            );
            $path = preg_replace('#\.css$#', '.less', $asset->getFilePath());

            $sourceFile = $this->fallback->getFile(
                $context->getAreaCode(),
                $themeModel,
                $context->getLocale(),
                $path,
                $asset->getModule()
            );
        } catch (\Exception $e) {
            return false;
        }

        return $sourceFile;
    }

    /**
     * Get the theme provider instance.
     *
     * @return ThemeProviderInterface
     */
    private function getThemeProvider()
    {
        if (null === $this->themeProvider) {
            $this->themeProvider = ObjectManager::getInstance()->get(ThemeProviderInterface::class);
        }

        return $this->themeProvider;
    }
}
