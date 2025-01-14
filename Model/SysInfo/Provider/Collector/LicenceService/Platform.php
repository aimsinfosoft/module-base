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

namespace Aimsinfosoft\Base\Model\SysInfo\Provider\Collector\LicenceService;

use Aimsinfosoft\Base\Model\LicenceService\Request\Data\InstanceInfo\Platform as RequestPlatform;
use Aimsinfosoft\Base\Model\SysInfo\Provider\Collector\CollectorInterface;
use Magento\Framework\App\ProductMetadataInterface;

/**
 * Class Platform
 *
 * Collector for platform information used in the license service.
 *
 * @since 1.0.0
 */
class Platform implements CollectorInterface
{
    /**
     * @var ProductMetadataInterface
     */
    private $productMetadata;

    /**
     * Platform constructor.
     *
     * @param ProductMetadataInterface $productMetadata
     */
    public function __construct(ProductMetadataInterface $productMetadata)
    {
        $this->productMetadata = $productMetadata;
    }

    /**
     * Get collected platform information for the license service.
     *
     * @return array
     */
    public function get(): array
    {
        return [
            RequestPlatform::NAME => 'Magento ' . $this->productMetadata->getEdition(),
            RequestPlatform::VERSION => $this->productMetadata->getVersion()
        ];
    }
}
