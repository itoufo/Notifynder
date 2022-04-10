<?php

namespace Itoufo\Notifer\Senders;

use Itoufo\Notifer\Models\Notification;
use Itoufo\Notifer\Contracts\SenderContract;
use Itoufo\Notifer\Contracts\SenderManagerContract;

/**
 * Class SingleSender.
 */
class SingleSender implements SenderContract
{
    /**
     * @var \Itoufo\Notifer\Builder\Notification
     */
    protected $notification;

    /**
     * SingleSender constructor.
     *
     * @param array $notifications
     */
    public function __construct(array $notifications)
    {
        $this->notification = array_values($notifications)[0];
    }

    /**
     * Send the single notification.
     *
     * @param SenderManagerContract $sender
     * @return bool
     */
    public function send(SenderManagerContract $sender)
    {
        $model = app('notifer.resolver.model')->getModel(Notification::class);

        $notification = new $model($this->notification);

        return $notification->save();
    }
}
