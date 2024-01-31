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

namespace Aimsinfosoft\Base\Model\Response;

use Magento\Framework\App;
use Magento\Framework\Filesystem\File\ReadInterface;

/**
 * OctetResponseInterface defines the interface for handling octet-stream responses in Magento.
 */
interface OctetResponseInterface extends App\Response\HttpInterface, App\PageCache\NotCacheableInterface
{
    /**
     * Constant representing file response type.
     */
    public const FILE = 'file';

    /**
     * Constant representing file URL response type.
     */
    public const FILE_URL = 'url';

    /**
     * Sends the octet-stream response.
     */
    public function sendOctetResponse();

    /**
     * Gets the content disposition for the response.
     *
     * @return string
     */
    public function getContentDisposition(): string;

    /**
     * Gets the read resource by its path.
     *
     * @param string $readResourcePath
     * @return ReadInterface
     */
    public function getReadResourceByPath(string $readResourcePath): ReadInterface;

    /**
     * Sets the read resource for the response.
     *
     * @param ReadInterface $readResource
     * @return OctetResponseInterface
     */
    public function setReadResource(ReadInterface $readResource): OctetResponseInterface;

    /**
     * Gets the file name for the response.
     *
     * @return string|null
     */
    public function getFileName(): ?string;

    /**
     * Sets the file name for the response.
     *
     * @param string $fileName
     * @return OctetResponseInterface
     */
    public function setFileName(string $fileName): OctetResponseInterface;
}
