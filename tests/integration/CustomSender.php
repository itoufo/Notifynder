<?php

use Itoufo\Notifynder\Contracts\NotifynderSender;
use Itoufo\Notifynder\Contracts\Sender;

/**
 * Class CustomSender.
 */
class CustomDefaultSender implements Sender
{
    /**
     * @var array
     */
    protected $notifications;

    /**
     * @var \Itoufo\Notifynder\NotifynderManager
     */
    private $notifynder;

    /**
     * @param array                        $notifications
     * @param \Itoufo\Notifynder\NotifynderManager $notifynder
     */
    public function __construct(array $notifications, \Itoufo\Notifynder\NotifynderManager $notifynder)
    {
        $this->notifications = $notifications;
        $this->notifynder = $notifynder;
    }

    /**
     * Send notification.
     *
     * @param NotifynderSender $sender
     * @return mixed
     */
    public function send(NotifynderSender $sender)
    {
        //        dd($storeNotification);
        return $sender->send($this->notifications);
    }
}
