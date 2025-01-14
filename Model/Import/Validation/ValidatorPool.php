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


namespace Aimsinfosoft\Base\Model\Import\Validation;

/**
 * Class ValidatorPool
 * @package Aimsinfosoft\Base\Model\Import\Validation
 */
class ValidatorPool implements ValidatorPoolInterface
{
    /**
     * @var \Aimsinfosoft\Base\Model\Import\Validation\ValidatorInterface[]
     */
    private $validators;

    /**
     * ValidatorPool constructor.
     *
     * @param array $validators
     * @throws \Aimsinfosoft\Base\Exceptions\WrongValidatorInterface
     */
    public function __construct($validators)
    {
        $this->validators = [];
        foreach ($validators as $validator) {
            if (!($validator instanceof ValidatorInterface)) {
                throw new \Aimsinfosoft\Base\Exceptions\WrongValidatorInterface();
            }

            $this->validators[] = $validator;
        }
    }

    /**
     * Get the list of validators.
     *
     * @return \Aimsinfosoft\Base\Model\Import\Validation\ValidatorInterface[]
     */
    public function getValidators()
    {
        return $this->validators;
    }

    /**
     * Add a validator to the pool.
     *
     * @param \Aimsinfosoft\Base\Model\Import\Validation\ValidatorInterface $validator
     * @throws \Aimsinfosoft\Base\Exceptions\WrongValidatorInterface
     */
    public function addValidator($validator)
    {
        if (!($validator instanceof ValidatorInterface)) {
            throw new \Aimsinfosoft\Base\Exceptions\WrongValidatorInterface();
        }

        $this->validators[] = $validator;
    }
}
