<?php

use Itoufo\Notifer\Models\Notification;

class ModelResolverTest extends NotiferTestCase
{
    public function testGetModelDefault()
    {
        $resolver = app('notifer.resolver.model');
        $class = $resolver->getModel(Notification::class);
        $this->assertEquals(Notification::class, $class);
    }

    public function testGetModelCustom()
    {
        $resolver = app('notifer.resolver.model');
        $resolver->setModel(Notification::class, 'This\Model\Is\Custom');
        $class = $resolver->getModel(Notification::class);
        $this->assertEquals('This\Model\Is\Custom', $class);
    }

    public function testGetTableDefault()
    {
        $resolver = app('notifer.resolver.model');
        $table = $resolver->getTable(Notification::class);
        $this->assertEquals('notifications', $table);
    }

    public function testGetTableCustom()
    {
        $resolver = app('notifer.resolver.model');
        $resolver->setTable(Notification::class, 'prefixed_notifications');
        $table = $resolver->getTable(Notification::class);
        $this->assertEquals('prefixed_notifications', $table);
    }

    public function testMakeModel()
    {
        $resolver = app('notifer.resolver.model');
        $model = $resolver->make(Notification::class);
        $this->assertInstanceOf(Notification::class, $model);
    }

    public function testMakeModelFail()
    {
        $this->expectException(ReflectionException::class);

        $resolver = app('notifer.resolver.model');
        $resolver->setModel(Notification::class, 'This\Model\Is\Custom');
        $resolver->make(Notification::class);
    }
}
