<?php

class OnceSenderTest extends NotiferTestCase
{
    public function testGetQueryInstanceFail()
    {
        app('notifer.resolver.model')->setModel(\Itoufo\Notifer\Models\Notification::class, \Itoufo\Tests\Models\FakeModel::class);

        $this->expectException(BadMethodCallException::class);

        $manager = app('notifer.sender');
        $manager->sendOnce([
            new \Itoufo\Notifer\Builder\Notification(),
        ]);
    }
}
