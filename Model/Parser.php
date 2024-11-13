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

namespace Aimsinfosoft\Base\Model;

use Magento\Framework\Escaper;
use Magento\Framework\Filesystem\DriverInterface;

/**
 * Class Parser
 *
 * The Parser class is responsible for parsing XML and CSV formats.
 *
 */
class Parser
{
    /**
     * Restricted characters for escaping.
     */
    public const RESTRICTED_CHARS = [
        "\r\n",
        "\n",
        "\r"
    ];

    /**
     * @var Escaper
     */
    private $escaper;
    
    /**
     * fileDriver
     *
     * @var mixed
     */
    protected $fileDriver;

    /**
     * Parser constructor.
     *
     * @param Escaper $escaper
     */
    public function __construct(
        Escaper $escaper,
        DriverInterface   $fileDriver
    ) {
        $this->escaper = $escaper;
        $this->fileDriver = $fileDriver;
    }

    /**
     * Parse XML content and return SimpleXMLElement.
     *
     * @param string $xmlContent
     *
     * @return bool|\SimpleXMLElement
     */
    public function parseXml($xmlContent)
    {
        try {
            $xml = new \SimpleXMLElement($xmlContent);
        } catch (\Exception $e) {
            return false;
        }

        return $xml;
    }

    /**
     * Parse CSV content using fgetcsv for multiline values.
     *
     * @param string $csvContent
     *
     * @return array
     */
    public function parseCsv($csvContent)
    {
        try {

            $fp = $this->fileDriver->fileOpen('php://temp', 'r+');
            $this->fileDriver->fileWrite($fp, $csvContent);
          
            rewind($fp);

            $data = [];
            $header = [];
            $isFirstLine = true;
            while (($row = $this->fileDriver->fileReadCsv($fp)) !== false) { // for multiline values
                $row = array_map([$this, "escape"], $row);

                if ($isFirstLine) {
                    $isFirstLine = false;
                    $header = $row;

                    $row = array_combine($header, $row);
                    if (!isset($row['module_code'], $row['tab_name'], $row['upsell_module_code'], $row['text'], $row['priority'])) {
                        return [];
                    }

                    continue;
                }

                $data[] = array_combine($header, $row);
            }

            return $data;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Trim CSV data for selected columns.
     *
     * @param array $data
     * @param array $columns
     *
     * @return array
     */
    public function trimCsvData($data, $columns = [])
    {
        foreach ($data as $k => $element) {
            foreach ($columns as $column) {
                if (isset($element[$column])) {
                    $data[$k][$column] = preg_replace(
                        '/\s+/',
                        '',
                        $element[$column]
                    );
                }
            }
        }

        return $data;
    }

    /**
     * Escape HTML characters and replace restricted characters.
     *
     * @param string $value
     *
     * @return string
     */
    public function escape($value)
    {
        $value = $this->escaper->escapeHtml($value);
        $value = str_replace(static::RESTRICTED_CHARS, ' ', $value);

        return $value;
    }
}
