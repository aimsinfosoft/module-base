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


namespace Aimsinfosoft\Base\Plugin\AdminNotification\Block;

use Magento\AdminNotification\Block\ToolbarEntry as NativeToolbarEntry;

/**
 * Class ToolbarEntry
 * @package Aimsinfosoft\Base\Plugin\AdminNotification\Block
 *
 * Plugin class to add html attributes to Aimsinfosoft notifications
 */
class ToolbarEntry
{
    /**
     * Constant for Aimsinfosoft HTML attribute
     */
    public const Aimsinfosoft_ATTRIBUTE = ' data-ambase-logo="1"';

    /**
     * Plugin method to modify the HTML output for Aimsinfosoft notifications
     *
     * @param NativeToolbarEntry $subject
     * @param string $html
     * @return string
     */
    public function afterToHtml(
        NativeToolbarEntry $subject,
        $html
    )
    {
        $collection = $subject->getLatestUnreadNotifications()
            ->clear()
            ->addFieldToFilter('is_Aimsinfosoft', 1);

        foreach ($collection as $item) {
            $search = 'data-notification-id="' . $item->getId() . '"';
            if ($item->getData('image_url')) {
                $html = str_replace(
                    $search,
                    $search . ' style='
                    . '"background: url(' . $item->getData('image_url') . ') no-repeat 5px 7px; background-size: 30px;"'
                    . self::Aimsinfosoft_ATTRIBUTE,
                    $html
                );
            } else {
                $html = str_replace($search, $search . self::Aimsinfosoft_ATTRIBUTE, $html);
            }
        }

        return $html;
    }
}
