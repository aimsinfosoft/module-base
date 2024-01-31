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

namespace Aimsinfosoft\Base\Plugin\Framework\View\TemplateEngine;

use Magento\Framework\View\Element\BlockInterface;

/**
 * Class Php
 * @package Aimsinfosoft\Base\Plugin\Framework\View\TemplateEngine
 *
 * Plugin class for \Magento\Framework\View\TemplateEngine\Php
 * It injects the escaper instance into the dictionary before rendering a PHP template.
 */
class Php
{
    /**
     * @var \Magento\Framework\Escaper
     */
    private $escaper;

    /**
     * Php constructor.
     *
     * @param \Magento\Framework\Escaper $escaper
     */
    public function __construct(\Magento\Framework\Escaper $escaper)
    {
        $this->escaper = $escaper;
    }

    /**
     * Before plugin method to modify the parameters before rendering a PHP template.
     *
     * @param \Magento\Framework\View\TemplateEngine\Php $subject
     * @param BlockInterface $block
     * @param string $fileName
     * @param array $dictionary
     * @return array
     */
    public function beforeRender(
        \Magento\Framework\View\TemplateEngine\Php $subject,
        BlockInterface $block,
        $fileName,
        array $dictionary = []
    )
    {
        if (!isset($dictionary['escaper'])) {
            $dictionary['escaper'] = $this->escaper;
        }

        return [$block, $fileName, $dictionary];
    }
}
