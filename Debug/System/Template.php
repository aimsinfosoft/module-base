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


namespace Aimsinfosoft\Base\Debug\System;

/**
 * @codeCoverageIgnore
 * @codingStandardsIgnoreFile
 */
class Template
{
    public static $varWrapper = '<div class="aimsinfosoft-base-debug-wrapper"><code>%s</code></div>';

    public static $string = '"<span class="aimsinfosoft-base-string">%s</span>"';

    public static $var = '<span class="aimsinfosoft-base-var">%s</span>';

    public static $arrowsOpened = '<span class="aimsinfosoft-base-arrow" data-opened="true">&#x25BC;</span>
        <div class="aimsinfosoft-base-array">';

    public static $arrowsClosed = '<span class="aimsinfosoft-base-arrow" data-opened="false">&#x25C0;</span>
        <div class="aimsinfosoft-base-array aimsinfosoft-base-hidden">';

    public static $arrayHeader = '<span class="aimsinfosoft-base-info">array:%s</span> [';

    public static $array = '<div class="aimsinfosoft-base-array-line" style="padding-left:%s0px">
            %s  => %s
        </div>';

    public static $arrayFooter = '</div>]';

    public static $arrayKeyString = '"<span class="aimsinfosoft-base-array-key">%s</span>"';

    public static $arrayKey = '<span class="aimsinfosoft-base-array-key">%s</span>';

    public static $arraySimpleVar = '<span class="aimsinfosoft-base-array-value">%s</span>';

    public static $arraySimpleString = '"<span class="aimsinfosoft-base-array-string-value">%s</span>"';

    public static $objectHeader = '<span class="aimsinfosoft-base-info" title="%s">Object: %s</span> {';

    public static $objectMethod = '<div class="aimsinfosoft-base-object-method-line" style="padding-left:%s0px">
            #%s
        </div>';

    public static $objectMethodHeader = '<span style="margin-left:%s0px">Methods: </span>
        <span class="aimsinfosoft-base-arrow" data-opened="false">◀</span>
        <div class="aimsinfosoft-base-array  aimsinfosoft-base-hidden">';

    public static $objectMethodFooter = '</div>';

    public static $objectFooter = '</div> }';

    public static $debugJsCss = '<script>
            var aimsinfosoftToggle = function() {
                if (this.dataset.opened == "true") {
                    this.innerHTML = "&#x25C0";
                    this.dataset.opened = "false";
                    this.nextElementSibling.className = "aimsinfosoft-base-array aimsinfosoft-base-hidden";
                } else {
                    this.innerHTML = "&#x25BC;";
                    this.dataset.opened = "true";
                    this.nextElementSibling.className = "aimsinfosoft-base-array";
                }
            };
            document.addEventListener("DOMContentLoaded", function() {
                arrows = document.getElementsByClassName("aimsinfosoft-base-arrow");
                for (i = 0; i < arrows.length; i++) {
                    arrows[i].addEventListener("click", aimsinfosoftToggle,false);
                }
            });
        </script>
        <style>
            .aimsinfosoft-base-debug-wrapper {
                background-color: #263238;
                color: #ff9416;
                font-size: 13px;
                padding: 10px;
                border-radius: 3px;
                z-index: 1000000;
                margin: 20px 0;
            }
            .aimsinfosoft-base-debug-wrapper code {
                background: transparent !important;
                color: inherit !important;
                padding: 0;
                font-size: inherit;
                white-space: inherit;
            }
            .aimsinfosoft-base-info {
                color: #82AAFF;
            }
            .aimsinfosoft-base-var, .aimsinfosoft-base-array-key {
                color: #fff;
            }
            .aimsinfosoft-base-array-value {
                color: #C792EA;
                font-weight: bold;
            }
            .aimsinfosoft-base-arrow {
                cursor: pointer;
                color: #82aaff;
            }
            .aimsinfosoft-base-hidden {
                display:none;
            }
            .aimsinfosoft-base-string, .aimsinfosoft-base-array-string-value {
                font-weight: bold;
                color: #c3e88d;
            }
            .aimsinfosoft-base-object-method-line {
                color: #fff;
            }
        </style>';
}
