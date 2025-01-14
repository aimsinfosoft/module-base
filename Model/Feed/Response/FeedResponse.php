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

namespace Aimsinfosoft\Base\Model\Feed\Response;

use Magento\Framework\DataObject;

/**
 * Class FeedResponse
 *
 * @package Aimsinfosoft\Base\Model\Feed\Response
 */
class FeedResponse extends DataObject implements FeedResponseInterface
{
    public const CONTENT = 'content';
    public const STATUS = 'status';
    public const IS_NEED_TO_UPDATE_CACHE = 'is_need_to_update_cache';

    /**
     * @var string[]
     */
    private $failedStatuses = ['404'];

    /**
     * @var string[]
     */
    private $skipCacheUpdateStatuses = ['404', '304'];

    /**
     * Get the content of the feed response.
     *
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->getData(self::CONTENT);
    }

    /**
     * Set the content of the feed response.
     *
     * @param string|null $content
     * @return FeedResponseInterface
     */
    public function setContent(?string $content): FeedResponseInterface
    {
        $this->setData(self::CONTENT, $content);

        return $this;
    }

    /**
     * Get the status of the feed response.
     *
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->getData(self::STATUS);
    }

    /**
     * Set the status of the feed response.
     *
     * @param string|null $status
     * @return FeedResponseInterface
     */
    public function setStatus(?string $status): FeedResponseInterface
    {
        $this->setData(self::STATUS, $status);

        return $this;
    }

    /**
     * Check if the cache needs to be updated based on the feed response.
     *
     * @return bool
     */
    public function isNeedToUpdateCache(): bool
    {
        return !empty($this->getContent()) && !in_array($this->getStatus(), $this->skipCacheUpdateStatuses);
    }

    /**
     * Check if the feed response is considered failed.
     *
     * @return bool
     */
    public function isFailed(): bool
    {
        return empty($this->getContent()) || in_array($this->getStatus(), $this->failedStatuses);
    }
}
