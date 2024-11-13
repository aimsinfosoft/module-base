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


namespace Aimsinfosoft\Base\Plugin\Backend\Block;

use Magento\Backend\Block\Menu as NativeMenu;

/**
 * Class Menu
 *
 * Plugin class to modify the backend menu behavior
 */
class Menu
{
    /**
     * Constant for the maximum number of menu items
     */
    public const MAX_ITEMS = 300;

    /**
     * Plugin method to modify the rendering of the navigation menu
     *
     * @param NativeMenu $subject
     * @param $menu
     * @param int $level
     * @param int $limit
     * @param array $colBrakes
     *
     * @return array
     */
    public function beforeRenderNavigation(NativeMenu $subject, $menu, $level = 0, $limit = 0, $colBrakes = [])
    {
        if ($level !== 0 && $menu->get('Aimsinfosoft_Base::marketplace')) {
            $level = 0;
            $limit = self::MAX_ITEMS;
            if (is_array($colBrakes)) {
                foreach ($colBrakes as $key => $colBrake) {
                    if (isset($colBrake['colbrake'])
                        && $colBrake['colbrake']
                    ) {
                        $colBrakes[$key]['colbrake'] = false;
                    }

                    if (isset($colBrake['colbrake']) && (($key - 1) % $limit) === 0) {
                        $colBrakes[$key]['colbrake'] = true;
                    }
                }
            }
        }

        return [$menu, $level, $limit, $colBrakes];
    }

    /**
     * Plugin method to append JavaScript to the rendered HTML
     *
     * @param NativeMenu $subject
     * @param string $html
     *
     * @return string
     */
    public function afterToHtml(NativeMenu $subject, $html)
    {
        $js = $subject->getLayout()->createBlock(\Magento\Backend\Block\Template::class)
            ->setTemplate('Aimsinfosoft_Base::js.phtml')
            ->toHtml();

        return $html . $js;
    }
}
