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

use Aimsinfosoft\Base\Model\SimpleDataObject;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\ObjectManagerInterface;

/**
 * Class Converter
 * @package Aimsinfosoft\Base\Utils\Http\Response\Entity
 *
 * Converts data rows to objects based on entity configuration.
 */
class Converter
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * Converter constructor.
     *
     * @param ObjectManagerInterface $objectManager
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        DataObjectHelper $dataObjectHelper
    )
    {
        $this->objectManager = $objectManager;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * Convert the given row to a SimpleDataObject based on entity configuration.
     *
     * @param mixed $row
     * @param Config $entityConfig
     * @return SimpleDataObject
     */
    public function convertToObject($row, Config $entityConfig): SimpleDataObject
    {
        if ($entityConfig->getDataProcessor()) {
            $row = $entityConfig->getDataProcessor()->process($row);
        }

        $object = $this->objectManager->create($entityConfig->getClassName());
        $this->dataObjectHelper->populateWithArray(
            $object,
            $row,
            $entityConfig->getClassName()
        );

        return $object;
    }
}
