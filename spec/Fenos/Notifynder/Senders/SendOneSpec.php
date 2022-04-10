<?php

namespace spec\Itoufo\Notifynder\Senders;

use Itoufo\Notifynder\Contracts\StoreNotification;
use Itoufo\Notifynder\Exceptions\CategoryNotFoundException;
use Itoufo\Notifynder\Models\Notification;
use PhpSpec\ObjectBehavior;

class SendOneSpec extends ObjectBehavior
{
    public function let()
    {
        $infoNotification = [];
        $this->beConstructedWith($infoNotification);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Itoufo\Notifynder\Senders\SendOne');
    }

    /** @test */
    public function it_send_a_single_notification_having_the_category_(StoreNotification $storeNotification)
    {
        $infoNotification = [
            'category_id' => 1,
        ];

        $this->beConstructedWith($infoNotification);

        $storeNotification->storeSingle($infoNotification)->shouldBeCalled()
                ->willReturn(new Notification());

        $this->send($storeNotification)->shouldReturnAnInstanceOf(Notification::class);
    }

    /** @test */
    public function it_try_to_send_a_notification_without_category_id(StoreNotification $storeNotification)
    {
        // throw exception because I didn't specified a category_id
        $this->shouldThrow(CategoryNotFoundException::class)
             ->during('send', [$storeNotification]);
    }
}
