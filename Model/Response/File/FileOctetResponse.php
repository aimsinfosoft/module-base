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

namespace Aimsinfosoft\Base\Model\Response\File;

use Aimsinfosoft\Base\Model\MagentoVersion;
use Aimsinfosoft\Base\Model\Response\AbstractOctetResponse;
use Aimsinfosoft\Base\Model\Response\DownloadOutput;
use Aimsinfosoft\Base\Model\Response\OctetResponseInterface;
use Magento\Framework\App;
use Magento\Framework\Filesystem\File\ReadFactory;
use Magento\Framework\Filesystem\File\ReadInterface;
use Magento\Framework\Session\Config\ConfigInterface;
use Magento\Framework\Stdlib;

/**
 * FileOctetResponse class represents an octet-stream response for file downloads.
 */
class FileOctetResponse extends AbstractOctetResponse
{
    /**
     * @var ReadFactory
     */
    private $fileReadFactory;

    /**
     * FileOctetResponse constructor.
     *
     * @param ReadFactory $fileReadFactory
     * @param DownloadOutput $downloadHelper
     * @param MagentoVersion $magentoVersion
     * @param App\Request\Http $request
     * @param Stdlib\CookieManagerInterface $cookieManager
     * @param Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory
     * @param App\Http\Context $context
     * @param Stdlib\DateTime $dateTime
     * @param ConfigInterface|null $sessionConfig
     */
    public function __construct(
        ReadFactory $fileReadFactory,
        DownloadOutput $downloadHelper,
        MagentoVersion $magentoVersion,
        App\Request\Http $request,
        Stdlib\CookieManagerInterface $cookieManager,
        Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory,
        App\Http\Context $context,
        Stdlib\DateTime $dateTime,
        ConfigInterface $sessionConfig = null
    )
    {
        $this->fileReadFactory = $fileReadFactory;

        parent::__construct(
            $downloadHelper,
            $magentoVersion,
            $request,
            $cookieManager,
            $cookieMetadataFactory,
            $context,
            $dateTime,
            $sessionConfig
        );
    }

    /**
     * Gets the read resource by its path.
     *
     * @param string $readResourcePath
     * @return ReadInterface
     */
    public function getReadResourceByPath(string $readResourcePath): ReadInterface
    {
        return $this->fileReadFactory->create($readResourcePath, OctetResponseInterface::FILE);
    }
}
