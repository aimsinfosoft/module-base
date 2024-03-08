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

namespace Aimsinfosoft\Base\Model\SysInfo\Provider\Collector;

use Magento\Framework\App\ProductMetadataInterface;

/**
 * Class System
 *
 * Collector for system information used in the system information provider.
 *
 * @since 1.0.0
 */
class System implements CollectorInterface
{
    /**
     * Key for Magento version information.
     */
    public const MAGENTO_VERSION_KEY = 'magento_version';

    /**
     * Key for Magento edition information.
     */
    public const MAGENTO_EDITION_KEY = 'magento_edition';

    /**
     * Key for PHP version information.
     */
    public const PHP_VERSION_KEY = 'php_version';

    /**
     * @var ProductMetadataInterface
     */
    private $productMetadata;

    /**
     * System constructor.
     *
     * @param ProductMetadataInterface $productMetadata
     */
    public function __construct(
        ProductMetadataInterface $productMetadata
    )
    {
        $this->productMetadata = $productMetadata;
    }

    /**
     * Get collected system information for the system information provider.
     *
     * @return array
     */
    public function get(): array
    {
        return [
            self::MAGENTO_VERSION_KEY => $this->productMetadata->getVersion(),
            self::MAGENTO_EDITION_KEY => $this->productMetadata->getEdition(),
            self::PHP_VERSION_KEY => phpversion()
        ];
    }
}
