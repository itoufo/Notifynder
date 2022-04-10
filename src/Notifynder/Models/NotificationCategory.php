<?php

namespace Itoufo\Notifynder\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class NotificationCategory.
 *
 * @property int id
 * @property string name
 * @property string text
 */
class NotificationCategory extends Model
{
    /**
     * @var string
     */
    protected $table = 'notification_categories';

    /**
     * @var array
     */
    protected $fillable = ['name', 'text'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Relation with the notifications.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications()
    {
        return $this->hasMany('Itoufo\Notifynder\Models\Notification', 'category_id');
    }

    /**
     * Groups Categories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(
            'Itoufo\Notifynder\Models\NotificationGroup',
            'notifications_categories_in_groups',
            'category_id',
            'group_id'
        );
    }
}
