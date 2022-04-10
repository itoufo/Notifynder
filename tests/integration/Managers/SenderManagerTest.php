<?php

class SenderManagerTest extends NotiferTestCase
{
    public function testCallWithoutArguments()
    {
        $this->expectException(BadMethodCallException::class);

        $manager = app('notifer.sender');
        $manager->sendSingle();
    }

    public function testCallUndefinedMethod()
    {
        $this->expectException(BadMethodCallException::class);

        $manager = app('notifer.sender');
        $manager->undefinedMethod([]);
    }

    public function testCallFailingSender()
    {
        $this->expectException(BadFunctionCallException::class);

        $manager = app('notifer.sender');
        $manager->extend('sendFail', function () {
        });
        $manager->sendFail([]);
    }
}
