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

namespace Aimsinfosoft\Base\Model\SysInfo\Command\LicenceService\RegisterLicenceKey;

use Aimsinfosoft\Base\Model\SysInfo\Data\RegisteredInstance;
use Aimsinfosoft\Base\Model\SysInfo\Data\RegisteredInstance\Instance;
use Aimsinfosoft\Base\Model\SysInfo\Data\RegisteredInstance\InstanceFactory;
use Aimsinfosoft\Base\Model\SysInfo\Data\RegisteredInstanceFactory;
use Magento\Framework\Api\DataObjectHelper;

/**
 * Class Converter
 * @since 1.0.0
 */
class Converter
{
    /**
     * @var RegisteredInstanceFactory
     */
    private $registeredInstanceFactory;

    /**
     * @var InstanceFactory
     */
    private $instanceFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * Converter constructor.
     *
     * @param RegisteredInstanceFactory $registeredInstanceFactory
     * @param InstanceFactory $instanceFactory
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        RegisteredInstanceFactory $registeredInstanceFactory,
        InstanceFactory $instanceFactory,
        DataObjectHelper $dataObjectHelper
    )
    {
        $this->registeredInstanceFactory = $registeredInstanceFactory;
        $this->instanceFactory = $instanceFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * Convert an array to a RegisteredInstance object.
     *
     * @param array $data
     * @return RegisteredInstance
     */
    public function convertArrayToRegisteredInstance(array $data): RegisteredInstance
    {
        /** @var RegisteredInstance $registeredInstance */
        $registeredInstance = $this->registeredInstanceFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $registeredInstance,
            $data,
            RegisteredInstance::class
        );

        return $registeredInstance;
    }

    /**
     * Convert an array to an Instance object.
     *
     * @param array $data
     * @return Instance
     */
    public function convertArrayToInstance(array $data): Instance
    {
        /** @var Instance $instance */
        $instance = $this->instanceFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $instance,
            $data,
            Instance::class
        );

        return $instance;
    }
}
