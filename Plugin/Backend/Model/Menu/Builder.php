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


namespace Aimsinfosoft\Base\Plugin\Backend\Model\Menu;

use Aimsinfosoft\Base\Model\Feed\ExtensionsProvider;
use Aimsinfosoft\Base\Model\ModuleInfoProvider;
use Magento\Backend\Model\Menu;
use Magento\Backend\Model\Menu\Config;
use Magento\Backend\Model\Menu\Filter\IteratorFactory;
use Magento\Backend\Model\Menu\ItemFactory;
use Magento\Config\Model\Config\Structure;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\DataObjectFactory as ObjectFactory;
use Magento\Framework\Module\ModuleListInterface;
use Magento\Store\Model\ScopeInterface;
use Psr\Log\LoggerInterface;

class Builder
{
    public const BASE_MENU = 'MenuAimsinfosoft_Base::menu';

    public const SETTING_ENABLE = 'aimsinfosoft_base/menu/enable';

    /**
     * @var Config
     */
    private $menuConfig;

    /**
     * @var array|null
     */
    private $AimsinfosoftItems = null;

    /**
     * @var IteratorFactory
     */
    private $iteratorFactory;

    /**
     * @var ItemFactory
     */
    private $itemFactory;

    /**
     * @var ModuleListInterface
     */
    private $moduleList;

    /**
     * @var Structure
     */
    private $configStructure;

    /**
     * @var ObjectFactory
     */
    private $objectFactory;

    /**
     * @var ProductMetadataInterface
     */
    private $metadata;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ExtensionsProvider
     */
    private $extensionsProvider;

    /**
     * @var ModuleInfoProvider
     */
    private $moduleInfoProvider;

    /**
     * Builder constructor.
     *
     * @param Config $menuConfig
     * @param IteratorFactory $iteratorFactory
     * @param ItemFactory $itemFactory
     * @param ModuleListInterface $moduleList
     * @param Structure $configStructure
     * @param ProductMetadataInterface $metadata
     * @param ObjectFactory $objectFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param ExtensionsProvider $extensionsProvider
     * @param LoggerInterface $logger
     * @param ModuleInfoProvider $moduleInfoProvider
     */
    public function __construct(
        Config $menuConfig,
        IteratorFactory $iteratorFactory,
        ItemFactory $itemFactory,
        ModuleListInterface $moduleList,
        Structure $configStructure,
        ProductMetadataInterface $metadata,
        ObjectFactory $objectFactory,
        ScopeConfigInterface $scopeConfig,
        ExtensionsProvider $extensionsProvider,
        LoggerInterface $logger,
        ModuleInfoProvider $moduleInfoProvider
    )
    {
        $this->menuConfig = $menuConfig;
        $this->iteratorFactory = $iteratorFactory;
        $this->itemFactory = $itemFactory;
        $this->moduleList = $moduleList;
        $this->configStructure = $configStructure;
        $this->objectFactory = $objectFactory;
        $this->metadata = $metadata;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
        $this->extensionsProvider = $extensionsProvider;
        $this->moduleInfoProvider = $moduleInfoProvider;
    }

    /**
     * after get result
     * @param \Magento\Backend\Model\Menu\Builder $subject
     * @param Menu $menu
     *
     * @return Menu
     */
    public function afterGetResult($subject, Menu $menu)
    {
        try {
            $menu = $this->observeMenu($menu);
            //phpcs:ignore Magento2.CodeAnalysis.EmptyBlock.DetectedCatch
        } catch (\Exception $ex) {
            //do nothing - do not show our menu
        }

        return $menu;
    }

    /**
     * observer menu
     * @param Menu $menu
     *
     * @return Menu
     *
     * @throws \Exception
     */
    private function observeMenu(Menu $menu)
    {
        $item = $menu->get(self::BASE_MENU);
        if (!$item) {
            return $menu;
        }

        if (version_compare($this->metadata->getVersion(), '2.2.0', '<')
            || !$this->scopeConfig->isSetFlag(self::SETTING_ENABLE, ScopeInterface::SCOPE_STORE)

        ) {
            $menu->remove(self::BASE_MENU);
            return $menu;
        }

        $origMenu = $this->menuConfig->getMenu();
        $menuItems = $this->getMenuItems($origMenu);
        $configItems = $this->getConfigItems();

        foreach ($this->getInstalledModules($configItems) as $title => $installedModule) {

            $moduleInfo = $this->extensionsProvider->getFeedModuleData($installedModule);

            if (isset($menuItems[$installedModule])) {
                $itemsToAdd = $this->cloneMenuItems($menuItems[$installedModule], $menu);
            } else {
                $itemsToAdd = [];
            }

            if (isset($configItems[$installedModule]['id'])) {
                $AimsinfosoftItem = $this->generateMenuItem(
                    $installedModule . '::menuconfig',
                    $installedModule,
                    self::BASE_MENU,
                    'adminhtml/system_config/edit/section/' . $configItems[$installedModule]['id'],
                    __('Configuration')->render()
                );

                if ($AimsinfosoftItem) {
                    $itemsToAdd[] = $AimsinfosoftItem;
                }
            }

            if (isset($moduleInfo['guide']) && $moduleInfo['guide']) {
                $AimsinfosoftItem = $this->generateMenuItem(
                    $installedModule . '::menuguide',
                    $installedModule,
                    self::BASE_MENU,
                    'adminhtml/system_config/edit/section/ambase',
                    __('User Guide')->render()
                );

                if ($AimsinfosoftItem) {
                    $itemsToAdd[] = $AimsinfosoftItem;
                }
            }

            $parentNodeResource = '';
            foreach ($itemsToAdd as $key => $itemToAdd) {
                $itemToAdd = $itemToAdd->toArray();
                if (empty($itemToAdd['action'])) {
                    $parentNodeResource = $itemToAdd['resource'];
                    unset($itemsToAdd[$key]);
                }
            }

            if ($itemsToAdd) {
                $itemId = $installedModule . '::container';
                $moduleConfigResource = $configItems[$installedModule]['resource'] ?? $installedModule . '::config';
                /** @var \Magento\Backend\Model\Menu\Item $module */
                $module = $this->itemFactory->create(
                    [
                        'data' => [
                            'id' => $itemId,
                            'title' => $this->normalizeTitle($title),
                            'module' => $installedModule,
                            'resource' => $parentNodeResource ?: $moduleConfigResource
                        ]
                    ]
                );
                $menu->add($module, self::BASE_MENU, 1);
                foreach ($itemsToAdd as $copy) {
                    if ($copy) {
                        $menu->add($copy, $itemId, null);
                    }
                }
            }
        }

        return $menu;
    }

    /**
     * According to default validation rules, title can't be longer than 50 characters
     * @param string $title
     * @return string
     */
    private function normalizeTitle(string $title): string
    {
        if (mb_strlen($title) > 50) {
            $title = mb_substr($title, 0, 47) . '...';
        }

        return $title;
    }

    /**
     * clone the menu items
     * @param $menuItems
     * @param Menu $menu
     * @return array
     */
    private function cloneMenuItems($menuItems, Menu $menu)
    {
        $itemsToAdd = [];
        foreach ($menuItems as $link) {
            $AimsinfosoftItem = $menu->get($link);
            if ($AimsinfosoftItem) {
                $itemData = $AimsinfosoftItem->toArray();
                if (isset($itemData['id'], $itemData['resource'], $itemData['title'])) {
                    $itemToAdd = $this->generateMenuItem(
                        $itemData['id'] . 'menu',
                        $this->getModuleFullName($itemData),
                        $itemData['resource'],
                        $itemData['action'],
                        $itemData['title']
                    );

                    if ($itemToAdd) {
                        $itemsToAdd[] = $itemToAdd;
                    }
                }
            }
        }
        return $itemsToAdd;
    }

    /**
     * get module full name
     * @param $itemData
     *
     * @return string
     */
    private function getModuleFullName($itemData)
    {
        if (isset($itemData['module'])) {
            return $itemData['module'];
        } else {
            return current(explode('::', $itemData['resource']));
        }
    }

    /**
     * generating menu items
     * @param $id
     * @param $installedModule
     * @param $resource
     * @param $url
     * @param $title
     *
     * @return bool|Menu\Item
     */
    private function generateMenuItem($id, $installedModule, $resource, $url, $title)
    {
        try {
            $item = $this->itemFactory->create(
                [
                    'data' => [
                        'id' => $id,
                        'title' => $title,
                        'module' => $installedModule,
                        'action' => $url,
                        'resource' => $resource
                    ]
                ]
            );
        } catch (\Exception $ex) {
            $this->logger->warning($ex);
            $item = false;
        }

        return $item;
    }

    /**
     * get installed modules
     * @param $configItems
     *
     * @return array
     */
    private function getInstalledModules($configItems)
    {
        $installed = [];
        $modules = $this->moduleList->getNames();
        $dispatchResult = $this->objectFactory->create(['data' => $modules]);
        $modules = $dispatchResult->toArray();

        foreach ($modules as $moduleName) {
            if ($moduleName === 'Aimsinfosoft_Base'
                || strpos($moduleName, 'Aimsinfosoft_') === false
                || in_array($moduleName, $this->moduleInfoProvider->getRestrictedModules(), true)
            ) {
                continue;
            }

            $title = (isset($configItems[$moduleName]['label']) && $configItems[$moduleName]['label'])
                ? $configItems[$moduleName]['label']
                : $this->getModuleTitle($moduleName);

            $installed[$title] = $moduleName;
        }
        ksort($installed);

        return $installed;
    }

    /**
     * get menu items
     * @param Menu $menu
     *
     * @return array|null
     */
    private function getMenuItems(Menu $menu)
    {
        if ($this->AimsinfosoftItems === null) {
            $all = $this->generateAimsinfosoftItems($menu);
            $this->AimsinfosoftItems = [];
            foreach ($all as $item) {
                $name = explode('::', $item);
                $name = $name[0];
                if (!isset($this->AimsinfosoftItems[$name])) {
                    $this->AimsinfosoftItems[$name] = [];
                }
                $this->AimsinfosoftItems[$name][] = $item;
            }
        }

        return $this->AimsinfosoftItems;
    }

    /**
     * get config items
     * @return array
     */
    private function getConfigItems()
    {
        $configItems = [];
        $config = $this->generateConfigItems();
        foreach ($config as $item => $section) {
            $name = explode('::', $item);
            $name = $name[0];
            $configItems[$name] = $section;
        }

        return $configItems;
    }

    /**
     * generate Aimsinfosoft  module
     * @return array
     */
    private function generateAimsinfosoftItems($menu)
    {
        $Aimsinfosoft = [];
        foreach ($this->getMenuIterator($menu) as $menuItem) {
            if ($this->isCollectedNode($menuItem)) {
                $Aimsinfosoft[] = $menuItem->getId();
            }
            if ($menuItem->hasChildren()) {
                foreach ($this->generateAimsinfosoftItems($menuItem->getChildren()) as $menuChild) {
                    $Aimsinfosoft[] = $menuChild;
                }
            }
        }

        return $Aimsinfosoft;
    }

    /**
     * is collected node
     * @param $menuItem
     *
     * @return bool
     */
    private function isCollectedNode($menuItem)
    {
        if (strpos($menuItem->getId(), 'Aimsinfosoft') === false
            || strpos($menuItem->getId(), 'Aimsinfosoft_Base') !== false) {
            return false;
        }

        if (empty($menuItem->getAction()) || (strpos($menuItem->getAction(), 'system_config') === false)) {
            return true;
        }

        return false;
    }

    /**
     * Get menu filter iterator
     *
     * @param \Magento\Backend\Model\Menu $menu
     *
     * @return \Magento\Backend\Model\Menu\Filter\Iterator
     */
    private function getMenuIterator($menu)
    {
        return $this->iteratorFactory->create(['iterator' => $menu->getIterator()]);
    }

    /**
     * get module title
     * @param $name
     *
     * @return string
     */
    private function getModuleTitle($name)
    {
        $result = $name;
        $module = $this->extensionsProvider->getFeedModuleData($name);
        if ($module && isset($module['name'])) {
            $result = $module['name'];
            $result = str_replace(' for Magento 2', '', $result);
        } else {
            $result = str_replace('Aimsinfosoft_', '', $result);
            $result = preg_replace('/([a-z0-9])([A-Z])/', '$1 $2', $result);
        }

        return $result;
    }

    /**
     * generate config items
     *
     * @return array
     */
    private function generateConfigItems()
    {
        $result = [];
        $configTabs = $this->configStructure->getTabs();
        $config = $this->findResourceChildren($configTabs, 'Aimsinfosoft');

        if ($config) {
            foreach ($config as $item) {
                $data = $item->getData('resource');
                if (isset($data['resource'], $data['id']) && $data['id']) {
                    $result[$data['resource']] = $data;
                }
            }
        }

        return $result;
    }

    /**
     * @param \Magento\Config\Model\Config\Structure\Element\Iterator $config
     * @param string $name
     *
     * @return \Magento\Config\Model\Config\Structure\Element\Iterator|null
     */
    private function findResourceChildren($config, $name)
    {
        /** @var \Magento\Config\Model\Config\Structure\Element\Tab|null $currentNode */
        $currentNode = null;
        foreach ($config as $node) {
            if ($node->getId() === $name) {
                $currentNode = $node;
                break;
            }
        }

        if ($currentNode) {
            return $currentNode->getChildren();
        }

        return null;
    }
}
