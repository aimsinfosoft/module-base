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

/**
 * Interface FeedResponseInterface
 *
 * @package Aimsinfosoft\Base\Model\Feed\Response
 */
interface FeedResponseInterface
{
    /**
     * Get the content of the feed response.
     *
     * @return string|null
     */
    public function getContent(): ?string;

    /**
     * Set the content of the feed response.
     *
     * @param string|null $content
     * @return FeedResponseInterface
     */
    public function setContent(?string $content): FeedResponseInterface;

    /**
     * Get the status of the feed response.
     *
     * @return string|null
     */
    public function getStatus(): ?string;

    /**
     * Set the status of the feed response.
     *
     * @param string|null $status
     * @return FeedResponseInterface
     */
    public function setStatus(?string $status): FeedResponseInterface;

    /**
     * Check if the cache needs to be updated based on the feed response.
     *
     * @return bool
     */
    public function isNeedToUpdateCache(): bool;

    /**
     * Check if the feed response is considered failed.
     *
     * @return bool
     */
    public function isFailed(): bool;
}
