<?php

use Itoufo\Notifer\Models\Notification as ModelNotification;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class NotiferFacadeTest extends NotiferTestCase
{
    public function testSendSingleNotification()
    {
        $category = $this->createCategory();
        $sent = \Notifer::category($category->getKey())
            ->from(1)
            ->to(2)
            ->send();

        $this->assertTrue($sent);

        $notifications = ModelNotification::all();
        $this->assertCount(1, $notifications);
        $this->assertInstanceOf(EloquentCollection::class, $notifications);
    }
}
