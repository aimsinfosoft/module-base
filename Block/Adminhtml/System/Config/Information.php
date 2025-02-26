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

namespace Aimsinfosoft\Base\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Context;
use Magento\Backend\Model\Auth\Session;
use Magento\Config\Block\System\Config\Form\Fieldset;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\View\Helper\Js;
use Magento\Framework\View\LayoutFactory;

/**
 * Class Information
 * @package Aimsinfosoft\Base\Block\Adminhtml\System\Config
 *
 * Adminhtml block for rendering additional information in the system configuration.
 */
class Information extends Fieldset
{
    public const SEO_PARAMS = '?utm_source=extension&utm_medium=backend&utm_campaign=';

    /**
     * @var LayoutFactory
     */
    private $layoutFactory;

    /**
     * Information constructor.
     *
     * @param Context $context
     * @param Session $authSession
     * @param Js $jsHelper
     * @param LayoutFactory $layoutFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Session $authSession,
        Js $jsHelper,
        LayoutFactory $layoutFactory,
        array $data = []
    )
    {
        parent::__construct($context, $authSession, $jsHelper, $data);
        $this->layoutFactory = $layoutFactory;
    }

    /**
     * Render the element.
     *
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        if (!($moduleCode = $element->getDataByPath('group/module_code'))
            || $moduleCode === 'Aimsinfosoft_Base'
            || !($innerHtml = $this->getInnerHtml($moduleCode, $element))
        ) {
            return '';
        }

        $html = $this->_getHeaderHtml($element)
            . $innerHtml
            . $this->_getFooterHtml($element);
        $html = str_replace(
            'Aimsinfosoft_information]" type="hidden" value="0"',
            'Aimsinfosoft_information]" type="hidden" value="1"',
            $html
        );

        return preg_replace('(onclick=\"Fieldset.toggleCollapse.*?\")', '', $html);
    }

    /**
     * Get inner HTML.
     *
     * @param string $moduleCode
     * @param AbstractElement $element
     * @return string
     */
    private function getInnerHtml(string $moduleCode, AbstractElement $element): string
    {
        $html = '';

        $layout = $this->layoutFactory->create(['cacheable' => false]);
        $layout->getUpdate()->load(
            [
                'aimsinfosoft_base_information_block',
                strtolower($moduleCode) . '_information_block'
            ]
        );
        $layout->generateXml();
        $layout->generateElements();

        $basicBlock = $layout->getBlock('aminfotab.basic');
        if ($basicBlock) {
            $basicBlock->setData('element', $element);
            $html .= $basicBlock->toHtml();
        }

        return $html;
    }
}
