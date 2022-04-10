<?php

namespace Itoufo\Tests\Models;

use Itoufo\Notifynder\Notifable;

class User extends \Illuminate\Database\Eloquent\Model
{
    // Never do this
    protected $fillable = ['id'];
    use Notifable;
}
