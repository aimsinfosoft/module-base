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

namespace Aimsinfosoft\Base\Utils\Http;

use Aimsinfosoft\Base\Model\SimpleDataObject;
use Aimsinfosoft\Base\Utils\Http\Response\ResponseFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\HTTP\Adapter\CurlFactory as FrameworkCurlFactory;

/**
 * Class Curl
 * @package Aimsinfosoft\Base\Utils\Http
 */
class Curl
{
    /**
     * Connection timeout, seconds
     */
    public const CONNECTION_TIMEOUT = 60;

    /**
     * @var FrameworkCurlFactory
     */
    private $curlFactory;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var array
     */
    private $headers = [];

    /**
     * Curl constructor.
     *
     * @param FrameworkCurlFactory $curlFactory
     * @param ResponseFactory $responseFactory
     */
    public function __construct(
        FrameworkCurlFactory $curlFactory,
        ResponseFactory $responseFactory
    )
    {
        $this->curlFactory = $curlFactory;
        $this->responseFactory = $responseFactory;
    }

    /**
     * Perform an HTTP request using cURL.
     *
     * @param string $url
     * @param mixed $params
     * @param string $method
     * @return SimpleDataObject
     * @throws LocalizedException
     */
    public function request(
        string $url,
        $params = '',
        string $method = \Laminas\Http\Request::METHOD_POST
    ): SimpleDataObject
    {
        $curl = $this->curlFactory->create();
        $curl->setConfig(['timeout' => self::CONNECTION_TIMEOUT, 'header' => false, 'verifypeer' => false]);

        $curl->write(
            $method,
            $url,
            '1.1',
            $this->getHeaders(),
            $params
        );

        $responseData = $curl->read();
        $responseData = json_decode($responseData, true) ?? [];
        $httpCode = $curl->getInfo(CURLINFO_HTTP_CODE);

        if (!in_array($httpCode, [200, 204])) {
            throw new LocalizedException(__('Invalid request.'));
        }
        $curl->close();

        return $this->responseFactory->create($url, $responseData);
    }

    /**
     * Set custom headers for the HTTP request.
     *
     * @param array $headers
     * @return $this
     */
    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Get formatted headers for the HTTP request.
     *
     * @return array
     */
    private function getHeaders(): array
    {
        $headers = [];
        foreach ($this->headers as $name => $value) {
            $headers[] = implode(': ', [$name, $value]);
        }

        return $headers;
    }
}
