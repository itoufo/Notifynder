<?php

$factory('Itoufo\Notifynder\Models\NotificationCategory', [

    'name' => $faker->name,
    'text' => 'test notification',
]);

$factory('Itoufo\Tests\Models\User', [

    'name' => $faker->name,
    'surname' => $faker->lastName,
]);

$factory('Itoufo\Notifynder\Models\Notification', [

    'from_id' => 'factory:Itoufo\Tests\Models\User',
    'from_type' => 'Itoufo\Tests\Models\User',
    'to_id' => 'factory:Itoufo\Tests\Models\User',
    'to_type' => 'Itoufo\Tests\Models\User',
    'category_id' => 'factory:Itoufo\Notifynder\Models\NotificationCategory',
    'url' => $faker->url,
    'extra' => json_encode(['exta.name' => $faker->name]),
    'read' => 0,
    'expire_time' => null,
    'created_at' => $faker->dateTime,
    'updated_at' => $faker->dateTime,
]);

$factory('Itoufo\Notifynder\Models\NotificationGroup', [
    'name' => $faker->name,
]);
