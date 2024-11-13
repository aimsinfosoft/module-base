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

namespace Aimsinfosoft\Base\Model;

use Magento\Framework\Flag;
use Magento\Framework\Flag\FlagResource;
use Magento\Framework\FlagFactory;

class FlagRepository
{
    /**
     * @var FlagResource
     */
    private $flagResource;

    /**
     * @var FlagFactory
     */
    private $flagFactory;

    /**
     * FlagRepository constructor.
     *
     * @param FlagResource $flagResource
     * @param FlagFactory $flagFactory
     */
    public function __construct(
        FlagResource $flagResource,
        FlagFactory $flagFactory
    ) {
        $this->flagResource = $flagResource;
        $this->flagFactory = $flagFactory;
    }

    /**
     * Get the value of a flag by its code.
     *
     * @param string $code
     * @return string|null
     */
    public function get(string $code): ?string
    {
        return $this->getFlagObject($code)->getFlagData();
    }

    /**
     * Save the value of a flag.
     *
     * @param string $code
     * @param string $value
     * @return bool
     */
    public function save(string $code, string $value): bool
    {
        $flag = $this->getFlagObject($code);
        $flag->setFlagData($value);
        $this->flagResource->save($flag);

        return true;
    }

    /**
     * Get the Flag object by its code.
     *
     * @param string $code
     * @return Flag
     */
    private function getFlagObject(string $code): Flag
    {
        $flagModel = $this->flagFactory->create(['data' => ['flag_code' => $code]]);
        $this->flagResource->load(
            $flagModel,
            $code,
            'flag_code'
        );

        return $flagModel;
    }
}
