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

namespace Aimsinfosoft\Base\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class ConfigProviderAbstract
 *
 * @since 1.4.4
 * @since 1.12.9 fixed cache for emulated store
 */
abstract class ConfigProviderAbstract
{
    /**
     * XPath prefix of the module (section)
     *
     * @var string '{section}/'
     */
    protected $pathPrefix = '/';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Stored values by scopes
     *
     * @var array
     */
    protected $data = [];

    /**
     * ConfigProviderAbstract constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     *
     * @throws \LogicException
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
        if ($this->pathPrefix === '/') {
            throw new \LogicException('$pathPrefix should be declared');
        }
    }

    /**
     * Clear local storage
     *
     * @return void
     */
    public function clean()
    {
        $this->data = [];
    }

    /**
     * An alias for scope config with default scope type SCOPE_STORE
     *
     * @param string $path '{group}/{field}'
     * @param int|ScopeInterface|null $storeId Scope code
     * @param string $scope
     *
     * @return mixed
     */
    protected function getValue(
        $path,
        $storeId = null,
        $scope = ScopeInterface::SCOPE_STORE
    )
    {
        if ($storeId === null && $scope !== ScopeConfigInterface::SCOPE_TYPE_DEFAULT) {
            return $this->scopeConfig->getValue($this->pathPrefix . $path, $scope, $storeId);
        }

        if ($storeId instanceof \Magento\Framework\App\ScopeInterface) {
            $storeId = $storeId->getId();
        }
        $scopeKey = $storeId . $scope;
        if (!isset($this->data[$path]) || !\array_key_exists($scopeKey, $this->data[$path])) {
            $this->data[$path][$scopeKey] = $this->scopeConfig->getValue($this->pathPrefix . $path, $scope, $storeId);
        }

        return $this->data[$path][$scopeKey];
    }

    /**
     * An alias for scope config with scope type Default
     *
     * @param string $path '{group}/{field}'
     *
     * @return mixed
     */
    protected function getGlobalValue($path)
    {
        return $this->getValue($path, null, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
    }

    /**
     * Check if a flag is set
     *
     * @param string $path '{group}/{field}'
     * @param int|ScopeInterface|null $storeId
     * @param string $scope
     *
     * @return bool
     */
    protected function isSetFlag(
        $path,
        $storeId = null,
        $scope = ScopeInterface::SCOPE_STORE
    )
    {
        return (bool)$this->getValue($path, $storeId, $scope);
    }

    /**
     * Check if a global flag is set
     *
     * @param string $path '{group}/{field}'
     *
     * @return bool
     */
    protected function isSetGlobalFlag($path)
    {
        return $this->isSetFlag($path, null, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
    }
}
