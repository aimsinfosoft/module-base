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

use Aimsinfosoft\Base\Model\ModuleInfoProvider;
use Aimsinfosoft\Base\Model\ModuleListProcessor;
use Magento\Backend\Block\Template;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class Extensions
 *
 * Adminhtml block for displaying information about installed modules.
 */
class Extensions extends Field
{
    public const SEO_PARAMS = '?utm_source=extension&utm_medium=backend&utm_campaign=ext_list';

    /**
     * @var string
     */
    protected $_template = 'Aimsinfosoft_Base::modules.phtml';

    /**
     * @var ModuleListProcessor
     */
    private $moduleListProcessor;

    /**
     * @var ModuleInfoProvider
     */
    private $moduleInfoProvider;

    /**
     * Extensions constructor.
     * @param Template\Context $context
     * @param ModuleListProcessor $moduleListProcessor
     * @param ModuleInfoProvider $moduleInfoProvider
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ModuleListProcessor $moduleListProcessor,
        ModuleInfoProvider $moduleInfoProvider,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->moduleListProcessor = $moduleListProcessor;
        $this->moduleInfoProvider = $moduleInfoProvider;
    }

    /**
     * Get HTML for the element.
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->toHtml();
    }

    /**
     * Get the list of installed modules.
     *
     * @return array
     */
    public function getModuleList()
    {
        return $this->moduleListProcessor->getModuleList();
    }

    /**
     * Check if the module origin is from the Magento Marketplace.
     *
     * @return bool
     */
    public function isOriginMarketplace()
    {
        return $this->moduleInfoProvider->isOriginMarketplace();
    }

    /**
     * Return SEO parameters unless the module origin is from the Magento Marketplace.
     *
     * @return string
     */
    public function getSeoparams()
    {
        return !$this->isOriginMarketplace() ? self::SEO_PARAMS : '';
    }
}
