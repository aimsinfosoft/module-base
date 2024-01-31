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

namespace Aimsinfosoft\Base\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Magento\Framework\Setup\Patch\DependentPatchInterface;

class UpgradeExtensionsFeed implements DataPatchInterface, PatchRevertableInterface, PatchVersionInterface, DependentPatchInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Aimsinfosoft\Base\Model\Feed\FeedTypes\Extensions
     */
    private $extensionsFeed;

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * UpgradeExtensionsFeed constructor.
     *
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Aimsinfosoft\Base\Model\Feed\FeedTypes\Extensions $extensionsFeed
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Aimsinfosoft\Base\Model\Feed\FeedTypes\Extensions $extensionsFeed,
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->logger = $logger;
        $this->extensionsFeed = $extensionsFeed;
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * @inheritDoc
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();

        try {
            // Your upgrade logic here
            $this->extensionsFeed->getFeed();
        } catch (\Exception $ex) {
            $this->logger->critical($ex);
        }

        $this->moduleDataSetup->endSetup();
    }

    /**
     * @inheritDoc
     */
    public function revert()
    {
        $this->moduleDataSetup->startSetup();

        // Revert logic if needed

        $this->moduleDataSetup->endSetup();
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        // This method should return other patches that should be executed before this one
        return [];
    }

    /**
     * @inheritDoc
     */
    public static function getVersion()
    {
        // Return the version of the patch
        return '1.0.1';
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies()
    {
        // If there are other patches that should be executed before this one, list them here
        return [];
    }
}
