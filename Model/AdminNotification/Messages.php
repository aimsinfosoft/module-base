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

namespace Aimsinfosoft\Base\Model\AdminNotification;

/**
 * Class Messages
 *
 * @package Aimsinfosoft\Base\Model\AdminNotification
 */
class Messages
{
    /**
     * Session identifier for admin messages
     */
    public const AMBASE_SESSION_IDENTIFIER = 'ambase-session-messages';

    /**
     * @var \Magento\Backend\Model\Session
     */
    private $session;

    /**
     * Messages constructor.
     *
     * @param \Magento\Backend\Model\Session $session
     */
    public function __construct(
        \Magento\Backend\Model\Session $session
    )
    {
        $this->session = $session;
    }

    /**
     * Add a message to the admin session.
     *
     * @param string $message
     */
    public function addMessage($message)
    {
        $messages = $this->session->getData(self::AMBASE_SESSION_IDENTIFIER);
        if (!$messages || !is_array($messages)) {
            $messages = [];
        }

        $messages[] = $message;
        $this->session->setData(self::AMBASE_SESSION_IDENTIFIER, $messages);
    }

    /**
     * Get all messages from the admin session and clear the session.
     *
     * @return array
     */
    public function getMessages()
    {
        $messages = $this->session->getData(self::AMBASE_SESSION_IDENTIFIER);
        $this->clear();
        if (!$messages || !is_array($messages)) {
            $messages = [];
        }

        return $messages;
    }

    /**
     * Clear all messages from the admin session.
     */
    public function clear()
    {
        $this->session->setData(self::AMBASE_SESSION_IDENTIFIER, []);
    }
}
