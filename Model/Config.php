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

use Aimsinfosoft\Base\Model\Source\NotificationType;
use Magento\Framework\App\Config\ReinitableConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Config
 * Provide configuration data for the module
 *
 * @package Aimsinfosoft\Base\Model
 */
class Config extends ConfigProviderAbstract
{
    /**
     * XPath prefix of the module (section)
     *
     * @var string
     */
    protected $pathPrefix = 'aimsinfosoft_base/';

    /**#@+
     * XPath group parts
     */
    public const NOTIFICATIONS_BLOCK = 'notifications/';

    public const SYSTEM_VALUE_BLOCK = 'system_value/';

    public const LICENCE_SERVICE_VALUE_BLOCK = 'licence_service/';

    /**#@-*/

    /**#@+
     * XPath field parts
     */
    public const LAST_UPDATE = 'last_update';

    public const FREQUENCY = 'frequency';

    public const FIRST_MODULE_RUN = 'first_module_run';

    public const REMOVE_DATE = 'remove_date';

    public const ADS_ENABLE = 'ads_enable';

    public const NOTIFICATIONS_TYPE = 'type';

    public const LICENCE_SERVICE_API_URL = 'api_url';

    /**#@-*/

    /**
     * Number of seconds in an hour
     */
    public const HOUR_MIN_SEC_VALUE = 60 * 60 * 24;

    /**
     * Frequency value for removing expired data
     */
    public const REMOVE_EXPIRED_FREQUENCY = 60 * 60 * 6; // 4 times per day

    /**
     * @var WriterInterface
     */
    private $configWriter;

    /**
     * @var ReinitableConfigInterface
     */
    private $reinitableConfig;

    /**
     * Config constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param WriterInterface $configWriter
     * @param ReinitableConfigInterface $reinitableConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        WriterInterface $configWriter,
        ReinitableConfigInterface $reinitableConfig
    )
    {
        parent::__construct($scopeConfig);
        $this->configWriter = $configWriter;
        $this->reinitableConfig = $reinitableConfig;
    }

    /**
     * Get the frequency in seconds
     *
     * @return int
     */
    public function getFrequencyInSec()
    {
        return $this->getCurrentFrequencyValue() * self::HOUR_MIN_SEC_VALUE;
    }

    /**
     * Get the last update timestamp
     *
     * @return int
     */
    public function getLastUpdate()
    {
        return $this->getValue(self::SYSTEM_VALUE_BLOCK . self::LAST_UPDATE);
    }

    /**
     * Get the last removal timestamp
     *
     * @return int
     */
    public function getLastRemovement()
    {
        return $this->getValue(self::SYSTEM_VALUE_BLOCK . self::REMOVE_DATE);
    }

    /**
     * Save the last update timestamp
     */
    public function setLastUpdate()
    {
        $this->configWriter->save($this->pathPrefix . self::SYSTEM_VALUE_BLOCK . self::LAST_UPDATE, time());
        $this->reinitableConfig->reinit();
        $this->clean();
    }

    /**
     * Get the first module run timestamp
     *
     * @return int
     */
    public function getFirstModuleRun()
    {
        $result = $this->getValue(self::SYSTEM_VALUE_BLOCK . self::FIRST_MODULE_RUN);
        if (!$result) {
            $result = time();
            $this->configWriter->save($this->pathPrefix . self::SYSTEM_VALUE_BLOCK . self::FIRST_MODULE_RUN, $result);
            $this->reinitableConfig->reinit();
            $this->clean();
        }

        return $result;
    }

    /**
     * Save the last removal timestamp
     */
    public function setLastRemovement()
    {
        $this->configWriter->save($this->pathPrefix . self::SYSTEM_VALUE_BLOCK . self::REMOVE_DATE, time());
        $this->reinitableConfig->reinit();
        $this->clean();
    }

    /**
     * Get the current frequency value
     *
     * @return int
     */
    public function getCurrentFrequencyValue()
    {
        return $this->getValue(self::NOTIFICATIONS_BLOCK . self::FREQUENCY);
    }

    /**
     * Change the frequency value
     *
     * @param int $value
     */
    public function changeFrequency($value)
    {
        $this->configWriter->save($this->pathPrefix . self::NOTIFICATIONS_BLOCK . self::FREQUENCY, $value);
        $this->reinitableConfig->reinit();
        $this->clean();
    }

    /**
     * Check if ads are enabled
     *
     * @return bool
     */
    public function isAdsEnabled()
    {
        return (bool)$this->getValue(self::NOTIFICATIONS_BLOCK . self::ADS_ENABLE);
    }

    /**
     * Get the enabled notification types
     *
     * @return array
     */
    public function getEnabledNotificationTypes()
    {
        $value = $this->getValue(self::NOTIFICATIONS_BLOCK . self::NOTIFICATIONS_TYPE);

        return empty($value) ? [] : explode(',', $value);
    }

    /**
     * Get the Licence Service API URL
     *
     * @return string
     */
    public function getLicenceServiceApiUrl()
    {
        return $this->getValue(self::LICENCE_SERVICE_VALUE_BLOCK . self::LICENCE_SERVICE_API_URL);
    }
}
