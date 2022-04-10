<?php

namespace Itoufo\Notifynder\Senders;

use Itoufo\Notifynder\Contracts\DefaultSender;
use Itoufo\Notifynder\Contracts\StoreNotification;

/**
 * Class SendMultiple.
 *
 * Send multiple categories
 */
class SendMultiple implements DefaultSender
{
    /**
     * @var array
     */
    protected $infoNotifications = [];

    /**
     * @param $infoNotifications
     */
    public function __construct($infoNotifications)
    {
        $this->infoNotifications = $infoNotifications;
    }

    /**
     * Send multiple notifications.
     *
     * @param  StoreNotification $sender
     * @return mixed
     */
    public function send(StoreNotification $sender)
    {
        return $sender->storeMultiple($this->infoNotifications);
    }
}
