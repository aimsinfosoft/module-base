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

namespace Aimsinfosoft\Base\Model\Feed\FeedTypes;

use Aimsinfosoft\Base\Model\Feed\FeedContentProvider;
use Aimsinfosoft\Base\Model\LinkValidator;
use Aimsinfosoft\Base\Model\ModuleInfoProvider;
use Aimsinfosoft\Base\Model\Parser;
use Aimsinfosoft\Base\Model\Serializer;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Escaper;

class Extensions
{
    public const EXTENSIONS_CACHE_ID = 'ambase_extensions';
    public const Aimsinfosoft_EXTENSIONS_LAST_MODIFIED_DATE = 'aimsinfosoft_extensions_last_modified_date';

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var FeedContentProvider
     */
    private $feedContentProvider;

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * @var LinkValidator
     */
    private $linkValidator;

    /**
     * @var ModuleInfoProvider
     */
    private $moduleInfoProvider;

    /**
     * Extensions constructor.
     *
     * @param Serializer $serializer
     * @param CacheInterface $cache
     * @param FeedContentProvider $feedContentProvider
     * @param Parser $parser
     * @param Escaper $escaper
     * @param LinkValidator $linkValidator
     * @param ModuleInfoProvider $moduleInfoProvider
     */
    public function __construct(
        Serializer $serializer,
        CacheInterface $cache,
        FeedContentProvider $feedContentProvider,
        Parser $parser,
        Escaper $escaper,
        LinkValidator $linkValidator,
        ModuleInfoProvider $moduleInfoProvider
    ) {
        $this->serializer = $serializer;
        $this->cache = $cache;
        $this->feedContentProvider = $feedContentProvider;
        $this->parser = $parser;
        $this->escaper = $escaper;
        $this->linkValidator = $linkValidator;
        $this->moduleInfoProvider = $moduleInfoProvider;
    }

    /**
     * Executes the main functionality to retrieve extensions data.
     *
     * @return array
     */
    public function execute(): array
    {
        $cache = $this->cache->load(self::EXTENSIONS_CACHE_ID);
        $unserializedCache = $cache ? $this->serializer->unserialize($cache) : null;

        return $unserializedCache ?: $this->getFeed();
    }

    /**
     * Retrieves extensions data and caches it.
     *
     * @return array
     */
    public function getFeed(): array
    {
        $result = [];
        $cachedData = $this->cache->load(self::EXTENSIONS_CACHE_ID);
        $options = $cachedData ? ['modified_since' => $this->getLastModified()] : [];
        $feedResponse = $this->feedContentProvider->getFeedResponse(
            $this->feedContentProvider->getFeedUrl(FeedContentProvider::URN_EXTENSIONS),
            $options
        );
        if ($feedResponse->isNeedToUpdateCache()) {
            $feedXml = $this->parser->parseXml($feedResponse->getContent());
            if (isset($feedXml->channel->item)) {
                $result = $this->prepareFeedData($feedXml);
            }
            $this->saveCache($result);
            $this->setLastModified();
        }

        return $result;
    }

    /**
     * Gets the last modified date from the cache.
     *
     * @return mixed
     */
    private function getLastModified()
    {
        return $this->cache->load(self::Aimsinfosoft_EXTENSIONS_LAST_MODIFIED_DATE);
    }

    /**
     * Sets the last modified date in the cache.
     *
     * @return bool
     */
    private function setLastModified()
    {
        $dateTime = gmdate('D, d M Y H:i:s') . ' GMT';

        return $this->cache->save($dateTime, self::Aimsinfosoft_EXTENSIONS_LAST_MODIFIED_DATE);
    }

    /**
     * Saves the data to cache.
     *
     * @param array $result
     */
    private function saveCache(array $result)
    {
        $this->cache->save(
            $this->serializer->serialize($result),
            self::EXTENSIONS_CACHE_ID,
            [self::EXTENSIONS_CACHE_ID]
        );
    }

    /**
     * Prepares feed data from the XML.
     *
     * @param \SimpleXMLElement $feedXml
     * @return array
     */
    private function prepareFeedData(\SimpleXMLElement $feedXml): array
    {
        $marketplaceOrigin = $this->moduleInfoProvider->isOriginMarketplace();
        $result = [];

        foreach ($feedXml->channel->item as $item) {
            $code = $this->escaper->escapeHtml((string)$item->code);

            if (!isset($result[$code])) {
                $result[$code] = [];
            }

            $title = $this->escaper->escapeHtml((string)$item->title);
            $productPageLink = $marketplaceOrigin ? $item->market_link : $item->link;

            if (!$this->linkValidator->validate((string)$productPageLink)
                || !$this->linkValidator->validate((string)$item->guide)
                || filter_var((string)$item->landing, FILTER_VALIDATE_BOOLEAN)
            ) {
                continue;
            }

            $dateString = !empty((string)$item->date) ? $this->convertDate((string)$item->date) : '';

            $result[$code][$title] = [
                'name' => $title,
                'url' => $this->escaper->escapeUrl((string)$productPageLink),
                'version' => $this->escaper->escapeHtml((string)$item->version),
                'conflictExtensions' => $this->escaper->escapeHtml((string)$item->conflictExtensions),
                'guide' => $this->escaper->escapeUrl((string)$item->guide),
                'date' => $this->escaper->escapeHtml($dateString)
            ];
        }

        return $result;
    }

    /**
     * Converts the date string to a formatted date.
     *
     * @param string $date
     * @return string
     * @throws \Exception
     */
    private function convertDate($date)
    {
        try {
            $dateTimeObject = new \DateTime($date);
        } catch (\Exception $e) {
            return '';
        }

        return $dateTimeObject->format('F j, Y');
    }
}
