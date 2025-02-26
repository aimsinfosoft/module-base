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

namespace Aimsinfosoft\Base\Model\SysInfo\Command\LicenceService\SendSysInfo;

use Magento\Framework\Serialize\SerializerInterface;

/**
 * Class Encryption
 * @since 1.0.0
 */
class Encryption
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * Encryption constructor.
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(
        SerializerInterface $serializer
    )
    {
        $this->serializer = $serializer;
    }

    /**
     * Encrypt an array and return the hashed value.
     *
     * @param array $value
     * @return string
     */
    public function encryptArray(array $value): string
    {
        $serializedValue = $this->serializer->serialize($value);

        return $this->encryptString($serializedValue);
    }

    /**
     * Encrypt a string and return the hashed value.
     *
     * @param string $value
     * @return string
     */
    public function encryptString(string $value): string
    {
        return hash('sha256', $value);
    }
}
