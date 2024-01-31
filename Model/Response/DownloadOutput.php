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

namespace Aimsinfosoft\Base\Model\Response;

use Magento\Downloadable\Helper\Download;
use Magento\Framework\Filesystem\File\ReadInterface;

/**
 * DownloadOutput class extends Magento Download helper for handling file downloads.
 */
class DownloadOutput extends Download
{
    /**
     * @var ReadInterface|null
     */
    private $resourceHandler;

    /**
     * Set the resource handler for the download.
     *
     * @param ReadInterface $readResource
     * @return $this
     */
    public function setResourceHandler(ReadInterface $readResource): self
    {
        $this->resourceHandler = $readResource;

        return $this;
    }

    /**
     * Get the resource handler for the download.
     *
     * @return ReadInterface|null
     */
    protected function _getHandle(): ?ReadInterface
    {
        return $this->resourceHandler;
    }
}
