<?php

use Itoufo\Notifer\Collections\Config;

class HelpersTest extends NotiferTestCase
{
    public function testNotiferConfig()
    {
        $this->assertInstanceOf(Config::class, notifer_config());
    }

    public function testNotiferConfigGet()
    {
        $this->assertInternalType('bool', notifer_config('polymorphic'));
    }
}
