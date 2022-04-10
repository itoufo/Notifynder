<?php

namespace Itoufo\Tests\Models;

use Itoufo\Notifer\Traits\Notifable;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use Notifable;

    protected $table = 'users';

    protected $fillable = [
        'id',
        'firstname',
        'lastname',
    ];
}
