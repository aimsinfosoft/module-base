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


namespace Aimsinfosoft\Base\Model\Feed;

/**
 * Class ExtensionsProvider
 *
 * Provides data about extensions through feeds
 */
class ExtensionsProvider
{
    /**
     * @var array|null
     */
    protected $modulesData = null;

    /**
     * @var FeedTypes\Extensions
     */
    private $extensionsFeed;

    /**
     * ExtensionsProvider constructor.
     *
     * @param FeedTypes\Extensions $extensionsFeed
     */
    public function __construct(
        FeedTypes\Extensions $extensionsFeed
    )
    {
        $this->extensionsFeed = $extensionsFeed;
    }

    /**
     * Get data for all feed extensions
     *
     * @return array
     */
    public function getAllFeedExtensions()
    {
        if ($this->modulesData === null) {
            $this->modulesData = $this->extensionsFeed->execute();
        }

        return $this->modulesData;
    }

    /**
     * Get data for a specific feed module
     *
     * @param string $moduleCode
     * @return array
     */
    public function getFeedModuleData($moduleCode)
    {
        $allModules = $this->getAllFeedExtensions();
        $moduleData = [];

        if ($allModules && isset($allModules[$moduleCode])) {
            $module = $allModules[$moduleCode];
            if ($module && is_array($module)) {
                $module = array_shift($module);
            }
            $moduleData = $module;
        }

        return $moduleData;
    }
}
