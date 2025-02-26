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

namespace Aimsinfosoft\Base\Model\SysInfo\Provider\Module\Dir;

use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\Config\ConfigOptionsListConstants;
use Magento\Framework\Config\FileIterator;
use Magento\Framework\Config\FileIteratorFactory;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\RuntimeException;
use Magento\Framework\Exception\ValidatorException;
use Magento\Framework\Module\Dir;
use Magento\Framework\Module\Dir\Reader as FrameworkDirReader;
use Magento\Framework\Filesystem\Directory\ReadFactory;

/**
 * Class Reader
 *
 * Reader class responsible for reading module configuration files.
 *
 * @category    Aimsinfosoft
 * @package     Aimsinfosoft_Base
 * @author      Aimsinfosoft
 * @license     https://www.aimsinfosoft.com/LICENSE.txt
 */
class Reader
{
    /**
     * @var FileIteratorFactory
     */
    private $fileIteratorFactory;

    /**
     * @var ReadFactory
     */
    private $readFactory;

    /**
     * @var FrameworkDirReader
     */
    private $frameworkDirReader;

    /**
     * @var DeploymentConfig
     */
    private $deploymentConfig;

    /**
     * Found configuration files grouped by configuration types (filename).
     *
     * @var array
     */
    private $fileIterators = [];

    /**
     * Reader constructor.
     *
     * @param FileIteratorFactory $fileIteratorFactory
     * @param ReadFactory $readFactory
     * @param FrameworkDirReader $frameworkDirReader
     * @param DeploymentConfig $deploymentConfig
     */
    public function __construct(
        FileIteratorFactory $fileIteratorFactory,
        ReadFactory $readFactory,
        FrameworkDirReader $frameworkDirReader,
        DeploymentConfig $deploymentConfig
    )
    {
        $this->fileIteratorFactory = $fileIteratorFactory;
        $this->readFactory = $readFactory;
        $this->frameworkDirReader = $frameworkDirReader;
        $this->deploymentConfig = $deploymentConfig;
    }

    /**
     * Go through all modules and find configuration files of active modules.
     *
     * @param string $filename
     * @param string $moduleProviderName
     * @param string|bool $moduleStatus
     * @return FileIterator
     * @throws FileSystemException
     * @throws ValidatorException|RuntimeException
     */
    public function getConfigurationFiles(
        string $filename,
        string $moduleProviderName = 'all',
        $moduleStatus = 'all'
    ): FileIterator
    {
        return $this->getFilesIterator($filename, $moduleProviderName, $moduleStatus, Dir::MODULE_ETC_DIR);
    }

    /**
     * Retrieve iterator for files with $filename from components located in component $subDir.
     *
     * @param string $filename
     * @param string $moduleProviderName
     * @param string|bool $moduleStatus
     * @param string $subDir
     * @return FileIterator
     * @throws FileSystemException
     * @throws ValidatorException|RuntimeException
     */
    private function getFilesIterator(
        string $filename,
        string $moduleProviderName,
        $moduleStatus,
        string $subDir = ''
    ): FileIterator
    {
        if (!isset($this->fileIterators[$subDir][$filename][$moduleProviderName][$moduleStatus])) {
            $this->fileIterators[$subDir][$filename][$moduleProviderName][$moduleStatus] =
                $this->fileIteratorFactory->create(
                    $this->getFiles($filename, $moduleProviderName, $moduleStatus, $subDir)
                );
        }

        return $this->fileIterators[$subDir][$filename][$moduleProviderName][$moduleStatus];
    }

    /**
     * Go through all modules and find corresponding files of active modules
     *
     * @param string $filename
     * @param string $moduleProviderName
     * @param string|bool $requiredModuleStatus
     * @param string $subDir
     * @return array
     * @throws FileSystemException
     * @throws ValidatorException|RuntimeException
     */
    private function getFiles(
        string $filename,
        string $moduleProviderName,
        $requiredModuleStatus,
        string $subDir = ''
    ): array
    {
        $result = [];
        $moduleList = $this->deploymentConfig->get(ConfigOptionsListConstants::KEY_MODULES);
        foreach ($moduleList as $moduleName => $moduleStatus) {
            if ($this->isValid($moduleName, $moduleStatus, $moduleProviderName, $requiredModuleStatus)) {
                try {
                    $moduleSubDir = $this->frameworkDirReader->getModuleDir($subDir, $moduleName);
                } catch (\InvalidArgumentException $e) {
                    continue;
                }
                $file = $moduleSubDir . '/' . $filename;
                $directoryRead = $this->readFactory->create($moduleSubDir);
                $path = $directoryRead->getRelativePath($file);
                if ($directoryRead->isExist($path)) {
                    $result[] = $file;
                }
            }
        }

        return $result;
    }

    /**
     * Validate if the module is valid based on the provided criteria.
     *
     * @param string $moduleName
     * @param string|bool $moduleStatus
     * @param string $moduleProviderName
     * @param string|bool $requiredModuleStatus
     * @return bool
     */
    private function isValid($moduleName, $moduleStatus, $moduleProviderName, $requiredModuleStatus): bool
    {
        $isValid = true;
        if ($moduleProviderName != 'all' && strpos($moduleName, $moduleProviderName . '_') !== 0) {
            $isValid = false;
        }
        if ($requiredModuleStatus != 'all' && $requiredModuleStatus != (bool)$moduleStatus) {
            $isValid = false;
        }

        return $isValid;
    }
}
