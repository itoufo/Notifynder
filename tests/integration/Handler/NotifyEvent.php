<?php

use Itoufo\Notifynder\Contracts\NotifyListener;
use Itoufo\Notifynder\Handler\NotifynderEvent;

/**
 * Class NotifyEvent.
 */
class NotifyEvent implements NotifyListener
{
    /**
     * @var NotifynderEvent
     */
    public $notifynderEvent;

    /**
     * @param $notifynderEvent
     */
    public function __construct(NotifynderEvent $notifynderEvent)
    {
        $this->notifynderEvent = $notifynderEvent;
    }

    /**
     * @return NotifynderEvent
     */
    public function getNotifynderEvent()
    {
        return $this->notifynderEvent;
    }
}
