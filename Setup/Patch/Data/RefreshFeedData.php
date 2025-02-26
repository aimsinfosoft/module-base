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

namespace Aimsinfosoft\Base\Setup\Patch\Data;

use Aimsinfosoft\Base\Model\Feed\FeedTypes\Extensions;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Psr\Log\LoggerInterface;

/**
 * Class RefreshFeedData
 * @package Aimsinfosoft\Base\Setup\Patch\Data
 *
 * Data Patch to refresh feed data.
 */
class RefreshFeedData implements DataPatchInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var State
     */
    private $appState;

    /**
     * @var Extensions
     */
    private $extensionsFeed;

    /**
     * RefreshFeedData constructor.
     *
     * @param State $appState
     * @param LoggerInterface $logger
     * @param Extensions $extensionsFeed
     */
    public function __construct(
        State $appState,
        LoggerInterface $logger,
        Extensions $extensionsFeed
    )
    {
        $this->logger = $logger;
        $this->appState = $appState;
        $this->extensionsFeed = $extensionsFeed;
    }

    /**
     * Apply the data patch.
     */
    public function apply()
    {
        $this->appState->emulateAreaCode(
            Area::AREA_ADMINHTML,
            [$this, 'refreshFeedData'],
            []
        );
    }

    /**
     * Get dependencies for the patch.
     *
     * @return array
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * Get aliases for the patch.
     *
     * @return array
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * Refresh the feed data.
     */
    public function refreshFeedData(): void
    {
        try {
            $this->extensionsFeed->getFeed();
        } catch (\Exception $ex) {
            $this->logger->critical($ex);
        }
    }
}
