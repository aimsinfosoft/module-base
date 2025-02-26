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

namespace Aimsinfosoft\Base\Model\SysInfo\Formatter;

/**
 * Interface FormatterInterface
 * @since 1.0.0
 */
interface FormatterInterface
{
    /**
     * Get the formatted content.
     *
     * @return string
     */
    public function getContent(): string;

    /**
     * Get the extension associated with the formatted content.
     *
     * @return string
     */
    public function getExtension(): string;
}
