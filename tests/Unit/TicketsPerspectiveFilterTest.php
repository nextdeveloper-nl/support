<?php

namespace NextDeveloper\Support\Tests\Unit;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use NextDeveloper\Support\Database\Filters\TicketsPerspectiveQueryFilter;
use NextDeveloper\Support\Database\Filters\TicketsQueryFilter;
use NextDeveloper\Support\Database\Models\TicketsPerspective;
use PHPUnit\Framework\TestCase;

/**
 * The ticket lists read the perspective with the same user filters as the tickets themselves
 * (fixlean #733): `resolved_by_user_id` was ignored there, so a user's resolved tickets listed
 * every resolved ticket of the plant.
 */
class TicketsPerspectiveFilterTest extends TestCase
{
    public function test_resolved_by_user_id_filters_the_perspective(): void
    {
        $builder = new class extends Builder
        {
            public array $raw = [];

            public function __construct() {}

            public function whereRaw($sql, $bindings = [], $boolean = 'and')
            {
                $this->raw[] = $sql;

                return $this;
            }
        };

        (new TicketsPerspectiveQueryFilter(new Request(['resolved_by_user_id' => 'nobody'])))->apply($builder);

        //  A value that names no user matches nothing (no database lookup for a non-uuid).
        $this->assertSame(['false'], $builder->raw);
    }

    public function test_every_user_link_filter_of_the_tickets_is_on_the_perspective(): void
    {
        $userLinks = fn (string $class) => array_values(array_filter(
            get_class_methods($class),
            fn (string $method) => preg_match('/^[a-z_]+_user_id$/', $method) === 1
        ));

        $columns = (new \ReflectionClass(TicketsPerspective::class))->getDefaultProperties()['fillable'];

        foreach ($userLinks(TicketsQueryFilter::class) as $filter) {
            if (! in_array($filter, $columns, true)) {
                continue;
            }

            $this->assertTrue(
                method_exists(TicketsPerspectiveQueryFilter::class, $filter),
                "The perspective ignores {$filter}."
            );
        }
    }
}
