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

namespace Aimsinfosoft\Base\Model\LicenceService\Request\Url;

use Aimsinfosoft\Base\Model\Config;

/**
 * Class Builder
 * @package Aimsinfosoft\Base\Model\LicenceService\Request\Url
 */
class Builder
{
    /**
     * @var Config
     */
    private $config;

    /**
     * Builder constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Build the URL for the license service API request.
     *
     * @param string $path
     * @param array $params
     * @return string
     */
    public function build($path, $params = []): string
    {
        $apiUrl = $this->config->getLicenceServiceApiUrl();
        $requestParams = [$apiUrl, $path];
        if (!empty($params)) {
            $requestParams[] = '?';
            $requestParams[] = http_build_query($params);
        }

        return implode('', $requestParams);
    }
}
