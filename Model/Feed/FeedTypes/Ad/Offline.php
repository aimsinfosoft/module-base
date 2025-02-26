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


namespace Aimsinfosoft\Base\Model\Feed\FeedTypes\Ad;

use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Module\Dir\Reader as ModuleDirReader;

/**
 * Provides saved ads data.
 * Should not throw any exception.
 */
class Offline
{
    public const OFFLINE_ADS_FILENAME = 'offline_ads.json';

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var ModuleDirReader
     */
    private $moduleReader;

    /**
     * Offline constructor.
     *
     * @param Filesystem $filesystem
     * @param ModuleDirReader $moduleReader
     */
    public function __construct(
        Filesystem $filesystem,
        ModuleDirReader $moduleReader
    )
    {
        $this->filesystem = $filesystem;
        $this->moduleReader = $moduleReader;
    }

    /**
     * Get offline ads data.
     *
     * @param bool $market
     *
     * @return array
     */
    public function getOfflineData($market = false)
    {
        /** @var string $etcDir */
        $etcDirPath = $this->moduleReader->getModuleDir(
            \Magento\Framework\Module\Dir::MODULE_ETC_DIR,
            'Aimsinfosoft_Base'
        );

        $dir = $this->filesystem->getDirectoryRead(DirectoryList::ROOT);
        $fileName = $dir->getRelativePath($etcDirPath . '/' . static::OFFLINE_ADS_FILENAME);

        if (!$dir->isExist($fileName)) {
            return [];
        }

        try {
            $content = $dir->readFile($fileName);
        } catch (\Magento\Framework\Exception\FileSystemException $exception) {
            return [];
        }

        // Decode JSON content
        $data = json_decode($content, true) ?: [];

        // Process the data
        foreach ($data as &$row) {
            if (isset($row['text_market'])) {
                if ($market) {
                    $row['text'] = $row['text_market'];
                }

                unset($row['text_market']);
            }
        }

        return $data;
    }
}
