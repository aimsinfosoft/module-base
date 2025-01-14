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


namespace Aimsinfosoft\Base\Controller\Adminhtml\Notification;

use Aimsinfosoft\Base\Model\Config;
use Magento\Backend\App\Action;

class Frequency extends \Magento\Backend\App\Action
{
    /**
     * @var \Aimsinfosoft\Base\Model\Config
     */
    private $config;

    /**
     * @var \Aimsinfosoft\Base\Model\Source\Frequency
     */
    private $frequency;

    /**
     * Frequency constructor.
     * @param Action\Context $context
     * @param Config $config
     * @param \Aimsinfosoft\Base\Model\Source\Frequency $frequency
     */
    public function __construct(
        Action\Context $context,
        \Aimsinfosoft\Base\Model\Config $config,
        \Aimsinfosoft\Base\Model\Source\Frequency $frequency
    )
    {
        parent::__construct($context);
        $this->config = $config;
        $this->frequency = $frequency;
    }

    /**
     * execute
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $action = $this->getRequest()->getParam('action');

        switch ($action) {
            case 'less':
                $this->increaseFrequency();
                break;
            case 'more':
                $this->decreaseFrequency();
                break;
            default:
                $this->messageManager->addErrorMessage(
                    __(
                        'An error occurred while changing the frequency.'
                    )
                );
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setRefererUrl();

        return $resultRedirect;
    }

    /**
     * is allowed
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(
            'Aimsinfosoft_Base::config'
        );
    }

    /**
     * decrease freuqncey
     */
    protected function decreaseFrequency()
    {
        $currentValue = $this->config->getCurrentFrequencyValue();
        $allValues = $this->frequency->toOptionArray();
        $resultValue = null;
        foreach ($allValues as $option) {
            if ($option['value'] != $currentValue) {
                $resultValue = $option['value'];
            } else {
                if ($resultValue) {
                    $this->config->changeFrequency($resultValue);
                }

                break;
            }
        }

        $this->messageManager->addSuccessMessage(
            __(
                'You will get more messages of this type. Notification frequency has been updated.'
            )
        );
    }

    /**
     * increase increaseFrequency
     */
    protected function increaseFrequency()
    {
        $currentValue = $this->config->getCurrentFrequencyValue();
        $allValues = $this->frequency->toOptionArray();
        $resultValue = null;
        foreach ($allValues as $option) {
            if ($option['value'] == $currentValue) {
                $resultValue = $option['value'];
            }

            if ($resultValue && $option['value'] != $resultValue) {
                $this->config->changeFrequency($option['value']);//save next option
                break;
            }
        }

        $this->messageManager->addSuccessMessage(
            __(
                'You will get less messages of this type. Notification frequency has been updated.'
            )
        );
    }
}
