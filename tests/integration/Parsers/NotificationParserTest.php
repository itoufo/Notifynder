<?php

use Itoufo\Notifer\Builder\Notification;
use Itoufo\Notifer\Parsers\NotificationParser;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class NotificationParserTest extends NotiferTestCase
{
    public function testParseThrowsModelNotFoundExceptionIfCategoryIsNull()
    {
        $from = $this->createUser();
        $to = $this->createUser();
        $notification = new Notification();
        $notification->set('category_id', null);
        $notification->set('from_id', $from->getKey());
        $notification->set('to_id', $to->getKey());

        $this->expectException(ModelNotFoundException::class);
        $parser = new NotificationParser();
        $parser->parse($notification);
    }
}
