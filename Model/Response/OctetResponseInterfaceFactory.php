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

namespace Aimsinfosoft\Base\Model\Response;

use Magento\Framework\Filesystem\Io;

/**
 * OctetResponseInterfaceFactory is responsible for creating instances of OctetResponseInterface.
 */
class OctetResponseInterfaceFactory
{
    /**
     * @var Io\File
     */
    private $ioFile;

    /**
     * @var array
     */
    private $responseFactoryAssociationMap;

    /**
     * OctetResponseInterfaceFactory constructor.
     *
     * @param Io\File $ioFile
     * @param array $responseFactoryAssociationMap
     */
    public function __construct(
        Io\File $ioFile,
        array $responseFactoryAssociationMap = []
    )
    {
        $this->ioFile = $ioFile;
        $this->responseFactoryAssociationMap = $responseFactoryAssociationMap;
    }

    /**
     * Creates an instance of OctetResponseInterface based on the resource type.
     *
     * @param string $resourcePath
     * @param string $resourceType
     * @param string|null $fileName
     * @return OctetResponseInterface
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function create(
        string $resourcePath,
        string $resourceType = OctetResponseInterface::FILE,
        string $fileName = null
    ): OctetResponseInterface
    {
        if (!isset($this->responseFactoryAssociationMap[$resourceType])) {
            throw new \InvalidArgumentException('There is no resource handler for type ' . $resourceType);
        }

        $concreteOctetResponse = $this->responseFactoryAssociationMap[$resourceType]->create();

        if (!$concreteOctetResponse instanceof OctetResponseInterface) {
            throw new \LogicException(
                sprintf(
                    'OctetResponse class %s must implement %s interface',
                    get_class($concreteOctetResponse),
                    OctetResponseInterface::class
                )
            );
        }

        $readResource = $concreteOctetResponse->getReadResourceByPath($resourcePath);
        $concreteOctetResponse->setReadResource($readResource);
        $fileName = $fileName ?? $this->getFileNameFromResourcePath($resourcePath);
        $concreteOctetResponse->setFileName($fileName);

        return $concreteOctetResponse;
    }

    /**
     * Retrieves the file name from the resource path.
     *
     * @param string $resourcePath
     * @return string
     */
    private function getFileNameFromResourcePath(string $resourcePath): string
    {
        $resourcePathInfo = $this->ioFile->getPathInfo($resourcePath);

        return $resourcePathInfo['basename'] ?? 'file';
    }
}
