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

namespace Aimsinfosoft\Base\Utils\Http\Response\Entity;

use Magento\Framework\DataObject;

/**
 * Class Config
 *
 * Represents the configuration for an HTTP response entity.
 */
class Config extends DataObject
{
    public const CLASS_NAME = 'class_name';
    public const TYPE = 'type';
    public const DATA_PROCESSOR = 'data_processor';

    /**
     * Get the class name for the HTTP response entity.
     *
     * @return string
     */
    public function getClassName(): string
    {
        return $this->getData(self::CLASS_NAME);
    }

    /**
     * Get the type of the HTTP response entity.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->getData(self::TYPE);
    }

    /**
     * Get the data processor for the HTTP response entity.
     *
     * @return DataProcessorInterface|null
     */
    public function getDataProcessor(): ?DataProcessorInterface
    {
        return $this->getData(self::DATA_PROCESSOR);
    }
}
