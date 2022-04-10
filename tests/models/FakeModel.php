<?php

namespace Itoufo\Tests\Models;

class FakeModel
{
    public static function query()
    {
        return new static();
    }
}
