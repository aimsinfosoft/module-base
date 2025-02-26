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

namespace Aimsinfosoft\Base\Block\Adminhtml\System\Config\SysInfo;

use Magento\Backend\Block\Widget\Button;
use Magento\Config\Block\System\Config\Form\Field;

class DownloadButton extends Field
{
    public const ELEMENT_ID = 'download_info';
    protected const ACTION_URL = 'ambase/sysInfo/download';

    /**
     * to html
     * @return string
     */
    protected function _toHtml()
    {
        $button = $this->getLayout()->createBlock(Button::class)
            ->setData([
                'id' => self::ELEMENT_ID,
                'label' => __('Download'),
                'onclick' => $this->getOnClickAction()
            ]);

        return $button->toHtml();
    }

    /**
     * get on click action
     * @return string
     */
    private function getOnClickAction(): string
    {
        return sprintf(
            "location.href = '%s'",
            $this->_urlBuilder->getUrl(self::ACTION_URL)
        );
    }
}
