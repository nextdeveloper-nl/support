<?php

namespace NextDeveloper\Support\Tests\Unit;

use NextDeveloper\Support\Helpers\TicketPeople;
use PHPUnit\Framework\TestCase;

/**
 * A served ticket names its creator, responsible and resolver (fixlean #736): a quality lead who
 * cannot read users saw a blank person as the creator of someone else's suggestion.
 */
class TicketPeopleTest extends TestCase
{
    public function test_names_the_people_on_the_ticket_as_the_perspective_does(): void
    {
        $users = [
            7 => ['fullname' => 'Test Admin', 'name' => 'Test', 'surname' => 'Admin'],
            9 => ['fullname' => '', 'name' => 'Müslim', 'surname' => 'Güler'],
        ];

        $this->assertSame(
            ['fullname' => 'Test Admin', 'responsible_name' => 'Müslim Güler', 'resolved_by_name' => null],
            TicketPeople::fields(['iam_user_id' => 7, 'responsible_user_id' => '9', 'resolved_by_user_id' => null], $users)
        );
    }

    public function test_an_unknown_or_nameless_user_is_null(): void
    {
        $this->assertSame(
            ['fullname' => null, 'responsible_name' => null, 'resolved_by_name' => null],
            TicketPeople::fields(['iam_user_id' => 3, 'responsible_user_id' => 4], [4 => ['fullname' => ' ', 'name' => null]])
        );
    }
}
