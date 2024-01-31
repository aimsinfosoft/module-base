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

use Aimsinfosoft\Base\Model\FlagRepository;

/**
 * Class CacheStorage
 * @since 1.0.0
 */
class CacheStorage
{
    /**
     * Cache identifier prefix.
     */
    public const PREFIX = 'Aimsinfosoft_base_';

    /**
     * @var FlagRepository
     */
    private $flagRepository;

    /**
     * CacheStorage constructor.
     *
     * @param FlagRepository $flagRepository
     */
    public function __construct(FlagRepository $flagRepository)
    {
        $this->flagRepository = $flagRepository;
    }

    /**
     * Get a value from the cache by identifier.
     *
     * @param string $identifier
     * @return string|null
     */
    public function get(string $identifier): ?string
    {
        return $this->flagRepository->get(self::PREFIX . $identifier);
    }

    /**
     * Set a value in the cache by identifier.
     *
     * @param string $identifier
     * @param string $value
     * @return bool
     */
    public function set(string $identifier, string $value): bool
    {
        $this->flagRepository->save(self::PREFIX . $identifier, $value);

        return true;
    }
}
