<?php

namespace spec\Itoufo\Notifynder\Senders;

use Itoufo\Notifynder\Contracts\NotifynderCategory;
use Itoufo\Notifynder\Contracts\NotifynderGroup;
use Itoufo\Notifynder\Senders\SendGroup;
use Itoufo\Notifynder\Senders\SendMultiple;
use Itoufo\Notifynder\Senders\SendOne;
use PhpSpec\ObjectBehavior;

class SenderFactorySpec extends ObjectBehavior
{
    public function let(NotifynderGroup $notifynderGroup, NotifynderCategory $notifynderCategory)
    {
        $this->beConstructedWith($notifynderGroup, $notifynderCategory);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Itoufo\Notifynder\Senders\SenderFactory');
    }

    /** @test */
    public function it_get_a_dynamic_sender_checking_array_type_GET_SINGLE()
    {
        // if the array is multidimensional then
        // send multiple
        $notification = [];
        $category = 1;

        $this->getSender($notification, $category)->shouldReturnAnInstanceOf(SendOne::class);
    }

    /** @test */
    public function it_get_a_dynamic_sender_checking_array_type_GET_MULTIPLE()
    {
        // if the array is multidimensional then
        // send multiple
        $notification = [
            0 => [],
        ];
        $category = 1;

        $this->getSender($notification, $category)->shouldReturnAnInstanceOf(SendMultiple::class);
    }

    /** @test */
    public function it_get_the_send_group_sender()
    {
        $group_name = 'mygroup';
        $info = [];

        $this->sendGroup($group_name, $info)->shouldReturnAnInstanceOf(SendGroup::class);
    }
}
