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

namespace Aimsinfosoft\Base\Block\Adminhtml;

use Aimsinfosoft\Base\Model\ModuleListProcessor;
use Magento\Backend\Block\Template;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Store\Model\ScopeInterface;
use Aimsinfosoft\Base\Model\Source\NotificationType;
use Aimsinfosoft\Base\Model\Config;

/**
 * Class Notification
 * @package Aimsinfosoft\Base\Block\Adminhtml
 *
 * Adminhtml block for displaying extension-related notifications.
 */
class Notification extends Field
{
    /**
     * Template file for the block
     *
     * @var string
     */
    protected $_template = 'Aimsinfosoft_Base::notification.phtml';

    /**
     * @var ModuleListProcessor
     */
    private $moduleListProcessor;

    /**
     * @var Config
     */
    private $config;

    /**
     * Notification constructor.
     *
     * @param Template\Context $context
     * @param ModuleListProcessor $moduleListProcessor
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ModuleListProcessor $moduleListProcessor,
        Config $config,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->moduleListProcessor = $moduleListProcessor;
        $this->config = $config;
    }

    /**
     * Render HTML output.
     *
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->isSetNotification()) {
            return parent::_toHtml();
        }

        return '';
    }

    /**
     * Get the count of available updates.
     *
     * @return int|null
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getUpdatesCount()
    {
        $modules = $this->moduleListProcessor->getModuleList();

        return count($modules['hasUpdate']);
    }

    /**
     * Check if the notification is set based on configuration.
     *
     * @return bool
     */
    protected function isSetNotification()
    {
        $value = $this->config->getEnabledNotificationTypes();

        return in_array(NotificationType::AVAILABLE_UPDATE, $value);
    }
}
