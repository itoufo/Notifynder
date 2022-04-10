<?php

namespace Itoufo\Notifynder\Contracts;

use Itoufo\Notifynder\Models\Notification;

/**
 * Class NotificationRepository.
 */
interface StoreNotification
{
    /**
     * Save a single notification sent.
     *
     * @param  array        $info
     * @return Notification
     */
    public function storeSingle(array $info);

    /**
     * Save multiple notifications sent
     * at once.
     *
     * @param  array $info
     * @return mixed
     */
    public function storeMultiple(array $info);
}
