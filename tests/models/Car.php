<?php

namespace Itoufo\Tests\Models;

use Itoufo\Notifer\Traits\Notifable;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use Notifable;

    protected $table = 'cars';

    protected $fillable = [
        'id',
        'brand',
        'model',
    ];
}
