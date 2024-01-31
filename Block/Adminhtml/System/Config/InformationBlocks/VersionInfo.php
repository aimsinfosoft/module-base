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

namespace Aimsinfosoft\Base\Block\Adminhtml\System\Config\InformationBlocks;

use Aimsinfosoft\Base\Block\Adminhtml\System\Config\Information;
use Aimsinfosoft\Base\Model\Feed\ExtensionsProvider;
use Aimsinfosoft\Base\Model\ModuleInfoProvider;
use Aimsinfosoft\Base\Plugin\Backend\Model\Menu\Item;
use Magento\Config\Model\Config\Structure;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\View\Asset\Repository;
use Magento\Framework\View\Element\Template;

class VersionInfo extends Template
{
    /**
     * @var string
     */
    protected $_template = 'Aimsinfosoft_Base::config/information/version_info.phtml';

    /**
     * @var ModuleInfoProvider
     */
    private $moduleInfoProvider;

    /**
     * @var ExtensionsProvider
     */
    private $extensionsProvider;

    /**
     * @var Structure
     */
    private $configStructure;

    /**
     * @var Repository
     */
    private $assetRepo;

    /**
     * @var array
     */
    private $currentModuleFeedData = [];

    /**
     * VersionInfo constructor.
     * @param Template\Context $context
     * @param ModuleInfoProvider $moduleInfoProvider
     * @param ExtensionsProvider $extensionsProvider
     * @param Structure $structure
     * @param Repository $assetRepo
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ModuleInfoProvider $moduleInfoProvider,
        ExtensionsProvider $extensionsProvider,
        Structure $structure,
        Repository $assetRepo,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->moduleInfoProvider = $moduleInfoProvider;
        $this->extensionsProvider = $extensionsProvider;
        $this->configStructure = $structure;
        $this->assetRepo = $assetRepo;
    }

    /**
     * get current version
     * @return string
     */
    public function getCurrentVersion(): string
    {
        $moduleCode = $this->getElement()->getDataByPath('group/module_code');

        return $this->moduleInfoProvider->getModuleInfo($moduleCode)['version'] ?? '';
    }

    /**
     * get current module feed data
     * @return array
     */
    public function getCurrentModuleFeedData(): array
    {
        if (!$this->currentModuleFeedData) {
            $moduleCode = $this->getElement()->getDataByPath('group/module_code');
            $this->currentModuleFeedData = $this->extensionsProvider->getFeedModuleData($moduleCode);
        }

        return $this->currentModuleFeedData;
    }

    /**
     * is last version
     * @return bool
     */
    public function isLastVersion(): bool
    {
        $currentVer = $this->getCurrentVersion();
        $feedData = $this->getCurrentModuleFeedData();

        if ($feedData
            && isset($feedData['version'])
            && version_compare($feedData['version'], $currentVer, '>')
        ) {
            return false;
        }

        return true;
    }

    /**
     * get config module name
     * @return string
     */
    public function getConfigModuleName(): string
    {
        $configPath = $this->getElement()->getDataByPath('group/path');
        $name = $this->configStructure->getElementByConfigPath($configPath)->getData()['label'] ?? '';

        if (!$name) {
            if ($this->getCurrentModuleFeedData() && isset($this->getCurrentModuleFeedData()['name'])) {
                $name = $this->getCurrentModuleFeedData()['name'];
                $name = str_replace(' for Magento 2', '', $name);
            } else {
                $name = (string)__('Extension');
            }
        }

        return $name;
    }

    /**
     * get logo href
     * @return string
     */
    public function getLogoHref(): string
    {
        $moduleCode = $this->getElement()->getDataByPath('group/module_code');

        if ($this->moduleInfoProvider->isOriginMarketplace()) {
            $href = Item::MAGENTO_MARKET_URL;
        } else {
            $href = 'https://store.aimsinfosoft.com/' . Information::SEO_PARAMS . 'Aimsinfosoft_logo_' . $moduleCode;
        }

        return $href;
    }

    /**
     * get logo url
     * @return string
     */
    public function getLogoUrl(): string
    {
        return $this->assetRepo->getUrl('Aimsinfosoft_Base::images/Aimsinfosoft_logo.svg');
    }

    /**
     * is orgin market place
     * @return bool
     */
    public function isOriginMarketplace(): bool
    {
        return $this->moduleInfoProvider->isOriginMarketplace();
    }

    /**
     * is get changelog link
     * @return string
     */
    public function getChangelogLink(): string
    {
        if ($this->getCurrentModuleFeedData() && isset($this->getCurrentModuleFeedData()['url'])) {
            return $this->getCurrentModuleFeedData()['url'] . Information::SEO_PARAMS
                . 'changelog_' . $this->getElement()->getDataByPath('group/module_code')
                . '#changelog';
        }

        return '';
    }

    /**
     * get element
     * @return AbstractElement
     */
    public function getElement(): AbstractElement
    {
        return $this->getParentBlock()->getElement();
    }
}
