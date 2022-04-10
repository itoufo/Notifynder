<?php

use Itoufo\Notifer\Models\Notification;

class NotificationTest extends NotiferTestCase
{
    public function testFillablesEmpty()
    {
        $notification = new Notification();
        $this->assertInternalType('array', $notification->getFillable());
        $this->assertSame([
            'to_id',
            'to_type',
            'from_id',
            'from_type',
            'category_id',
            'read',
            'url',
            'extra',
            'expires_at',
            'stack_id',
        ], $notification->getFillable());
    }

    public function testFillablesCustomFillables()
    {
        $config = app('notifer.config');
        $config->set('additional_fields.fillable', ['fillable_field']);
        $notification = new Notification();
        $this->assertInternalType('array', $notification->getFillable());
        $this->assertSame([
            'to_id',
            'to_type',
            'from_id',
            'from_type',
            'category_id',
            'read',
            'url',
            'extra',
            'expires_at',
            'stack_id',
            'fillable_field',
        ], $notification->getFillable());
    }

    public function testFillablesCustomRequired()
    {
        $config = app('notifer.config');
        $config->set('additional_fields.required', ['required_field']);
        $notification = new Notification();
        $this->assertInternalType('array', $notification->getFillable());
        $this->assertSame([
            'to_id',
            'to_type',
            'from_id',
            'from_type',
            'category_id',
            'read',
            'url',
            'extra',
            'expires_at',
            'stack_id',
            'required_field',
        ], $notification->getFillable());
    }

    public function testFillablesCustom()
    {
        $config = app('notifer.config');
        $config->set('additional_fields.fillable', ['fillable_field']);
        $config->set('additional_fields.required', ['required_field']);
        $notification = new Notification();
        $this->assertInternalType('array', $notification->getFillable());
        $this->assertSame([
            'to_id',
            'to_type',
            'from_id',
            'from_type',
            'category_id',
            'read',
            'url',
            'extra',
            'expires_at',
            'stack_id',
            'required_field',
            'fillable_field',
        ], $notification->getFillable());
    }
}
