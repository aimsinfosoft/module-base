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


namespace Aimsinfosoft\Base\Model\Import;

/**
 * Class ImportCounter
 * @package Aimsinfosoft\Base\Model\Import
 */
class ImportCounter
{
    /**
     * @var int
     */
    private $created = 0;

    /**
     * @var int
     */
    private $updated = 0;

    /**
     * @var int
     */
    private $deleted = 0;

    /**
     * Increment the count of created items.
     *
     * @param int $incrementOn
     */
    public function incrementCreated($incrementOn = 1)
    {
        $this->created += (int)$incrementOn;
    }

    /**
     * Increment the count of updated items.
     *
     * @param int $incrementOn
     */
    public function incrementUpdated($incrementOn = 1)
    {
        $this->updated += (int)$incrementOn;
    }

    /**
     * Increment the count of deleted items.
     *
     * @param int $incrementOn
     */
    public function incrementDeleted($incrementOn = 1)
    {
        $this->deleted += (int)$incrementOn;
    }

    /**
     * Get the count of created items.
     *
     * @return int
     */
    public function getCreatedCount()
    {
        return $this->created;
    }

    /**
     * Get the count of updated items.
     *
     * @return int
     */
    public function getUpdatedCount()
    {
        return $this->updated;
    }

    /**
     * Get the count of deleted items.
     *
     * @return int
     */
    public function getDeletedCount()
    {
        return $this->deleted;
    }
}
