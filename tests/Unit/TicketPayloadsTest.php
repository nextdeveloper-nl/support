<?php

namespace NextDeveloper\Support\Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use NextDeveloper\Support\Helpers\TicketPayloads;
use PHPUnit\Framework\TestCase;

/**
 * What an application adds to a served ticket about the record it is opened on.
 */
class TicketPayloadsTest extends TestCase
{
    protected function tearDown(): void
    {
        TicketPayloads::reset();

        parent::tearDown();
    }

    private static function ticket(): Model
    {
        $ticket = new class extends Model {
            protected $table = 'support_tickets';

            protected $guarded = [];
        };

        $ticket->setRawAttributes(['object_type' => 'App\\Machine', 'object_id' => 7]);

        return $ticket;
    }

    public function test_without_decorators_the_ticket_is_served_as_it_is(): void
    {
        $this->assertSame(['id' => 'u-1', 'object_id' => 'm-7'], TicketPayloads::decorate(['id' => 'u-1', 'object_id' => 'm-7'], self::ticket()));
    }

    public function test_decorators_add_fields_about_the_record(): void
    {
        TicketPayloads::extend(fn (Model $ticket) => ['machine_name' => 'Press ' . $ticket->object_id]);
        TicketPayloads::extend(fn (Model $ticket) => ['machine_code' => 'P7']);

        $this->assertSame(
            ['id' => 'u-1', 'machine_name' => 'Press 7', 'machine_code' => 'P7'],
            TicketPayloads::decorate(['id' => 'u-1'], self::ticket())
        );
    }

    public function test_a_decorator_cannot_overwrite_the_tickets_own_fields(): void
    {
        TicketPayloads::extend(fn () => ['object_id' => 'forged', 'extra' => 1]);

        $this->assertSame(['object_id' => 'm-7', 'extra' => 1], TicketPayloads::decorate(['object_id' => 'm-7'], self::ticket()));
    }
}
