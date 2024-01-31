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

namespace Aimsinfosoft\Base\Block\Adminhtml\System\Config\InformationBlocks;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\View\Element\Template;

class Basic extends Template
{
    /**
     * @var string
     */
    private $class;

    /**
     * to html
     * @return string
     */
    public function toHtml()
    {
        $html = '';

        foreach ($this->getChildNames() as $childName) {
            $html .= $this->getChildBlock($childName)->toHtml();
        }

        if ($this->getClass()) {
            $html = '<div class="' . $this->getClass() . '">' . $html . '</div>';
        }

        return $html;
    }

    /**
     * get element
     * @return AbstractElement
     */
    public function getElement(): AbstractElement
    {
        return $this->getData('element') ?? $this->getParentBlock()->getElement();
    }

    /**
     * set class
     * @param string $class
     */
    public function setClass(string $class): void
    {
        $this->class = $class;
    }

    /**
     * get class
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }
}
