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

use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Component\ComponentRegistrarInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\DenyListPathValidator;
use Magento\Framework\Filesystem\Directory\WriteFactory;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\ObjectManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class to provide either default Filesystem class or with configured DenyListPathValidator exception paths
 *
 * @package Aimsinfosoft\Base\Model
 */
class FilesystemProvider
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var DirectoryList
     */
    private $directoryList;

    /**
     * @var ComponentRegistrarInterface
     */
    private $componentRegistrar;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var array
     */
    private $exceptionPaths;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * FilesystemProvider constructor.
     *
     * @param ObjectManagerInterface $objectManager
     * @param DirectoryList $directoryList
     * @param ComponentRegistrarInterface $componentRegistrar
     * @param LoggerInterface $logger
     * @param array $exceptionPaths
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        DirectoryList $directoryList,
        ComponentRegistrarInterface $componentRegistrar,
        LoggerInterface $logger,
        array $exceptionPaths = []
    ) {
        $this->objectManager = $objectManager;
        $this->directoryList = $directoryList;
        $this->componentRegistrar = $componentRegistrar;
        $this->logger = $logger;
        $this->exceptionPaths = $exceptionPaths;
    }

    /**
     * Get the Filesystem instance.
     *
     * @return Filesystem
     */
    public function get(): Filesystem
    {
        if ($this->filesystem === null) {
            try {
                if (!empty($this->exceptionPaths) && class_exists(DenyListPathValidator::class)) {
                    $this->filesystem = $this->createConfiguredFilesystem();
                } else {
                    $this->filesystem = $this->objectManager->create(Filesystem::class);
                }
            } catch (\Exception $e) {
                $this->logger->critical($e);
                $this->filesystem = $this->objectManager->create(Filesystem::class);
            }
        }

        return $this->filesystem;
    }

    /**
     * Create a configured Filesystem instance with DenyListPathValidator exception paths.
     *
     * @return Filesystem
     */
    private function createConfiguredFilesystem(): Filesystem
    {
        /** @var DenyListPathValidator $denyListPathValidator */
        $denyListPathValidator = $this->objectManager->create(DenyListPathValidator::class);
        $rootDirectory = $this->directoryList->getRoot();

        foreach ($this->exceptionPaths as $module => $pathsList) {
            $componentPath = $this->componentRegistrar->getPath(ComponentRegistrar::MODULE, $module);

            foreach ($pathsList as $path) {
                $denyListPathValidator->addException($rootDirectory . DIRECTORY_SEPARATOR . $path);
                $denyListPathValidator->addException($componentPath . DIRECTORY_SEPARATOR . $path);
                $denyListPathValidator->addException(
                    str_replace(
                        $rootDirectory . DIRECTORY_SEPARATOR,
                        '',
                        $componentPath . DIRECTORY_SEPARATOR . $path
                    )
                );
            }
        }
        $writeFactory = $this->objectManager->create(
            WriteFactory::class,
            ['denyListPathValidator' => $denyListPathValidator]
        );

        return $this->objectManager->create(Filesystem::class, ['writeFactory' => $writeFactory]);
    }
}
