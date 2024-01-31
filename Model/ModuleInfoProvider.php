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

use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Module\Dir\Reader;

/**
 * Class ModuleInfoProvider
 *
 * The ModuleInfoProvider class provides information about Magento modules.
 *
 * @package Aimsinfosoft\Base\Model
 */
class ModuleInfoProvider
{
    /**
     * Key for storing module version.
     */
    public const MODULE_VERSION_KEY = 'version';

    /**
     * @var string[]
     */
    protected $moduleDataStorage = [];

    /**
     * @var string[]
     */
    protected $restrictedModules = [
        'Aimsinfosoft_CommonRules',
        'Aimsinfosoft_Router'
    ];

    /**
     * @var Reader
     */
    private $moduleReader;

    /**
     * @var File
     */
    private $filesystem;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * ModuleInfoProvider constructor.
     *
     * @param Reader $moduleReader
     * @param File $filesystem
     * @param Serializer $serializer
     */
    public function __construct(
        Reader $moduleReader,
        File $filesystem,
        Serializer $serializer
    )
    {
        $this->moduleReader = $moduleReader;
        $this->filesystem = $filesystem;
        $this->serializer = $serializer;
    }

    /**
     * Read information about the extension from the composer.json file.
     *
     * @param string $moduleCode
     *   The code of the module.
     *
     * @return mixed
     *   Returns an array of information about the module.
     */
    public function getModuleInfo(string $moduleCode)
    {
        if (!isset($this->moduleDataStorage[$moduleCode])) {
            $this->moduleDataStorage[$moduleCode] = [];

            try {
                $dir = $this->moduleReader->getModuleDir('', $moduleCode);
                $file = $dir . '/composer.json';

                $string = $this->filesystem->fileGetContents($file);
                $this->moduleDataStorage[$moduleCode] = $this->serializer->unserialize($string);
            } catch (FileSystemException $e) {
                $this->moduleDataStorage[$moduleCode] = [];
            }
        }

        return $this->moduleDataStorage[$moduleCode];
    }

    /**
     * Check whether the module was installed via Magento Marketplace.
     *
     * @param string $moduleCode
     *   The code of the module.
     *
     * @return bool
     *   Returns true if the module was installed via Magento Marketplace, false otherwise.
     */
    public function isOriginMarketplace(string $moduleCode = 'Aimsinfosoft_Base'): bool
    {
        $moduleInfo = $this->getModuleInfo($moduleCode);
        $origin = isset($moduleInfo['extra']['origin']) ? $moduleInfo['extra']['origin'] : null;

        return 'marketplace' === $origin;
    }

    /**
     * Get an array of restricted modules.
     *
     * @return array
     *   Returns an array of restricted modules.
     */
    public function getRestrictedModules(): array
    {
        return $this->restrictedModules;
    }
}
