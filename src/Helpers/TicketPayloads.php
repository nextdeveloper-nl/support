<?php

namespace NextDeveloper\Support\Helpers;

use Illuminate\Database\Eloquent\Model;

/**
 * What an application adds to a served ticket (`/support/tickets`, `/support/tickets-perspective`)
 * about the record it is opened on (fixlean: the station's name, code, line and plant). A reader of
 * the ticket may not be allowed to read that record itself, so the lists could only show its id.
 *
 * Applications add a decorator with `extend()` at boot. A decorator returns only the fields it adds;
 * it cannot change or remove the ticket's own fields.
 */
final class TicketPayloads
{
    /**
     * @var array<int, callable(Model): array<string, mixed>>
     */
    private static array $decorators = [];

    /**
     * @param callable(Model): array<string, mixed> $decorator the fields to add for a ticket
     */
    public static function extend(callable $decorator): void
    {
        self::$decorators[] = $decorator;
    }

    /**
     * @param array<string, mixed> $transformed
     * @return array<string, mixed>
     */
    public static function decorate(array $transformed, Model $ticket): array
    {
        foreach (self::$decorators as $decorator) {
            $transformed += (array) $decorator($ticket);
        }

        return $transformed;
    }

    /**
     * Drops every decorator (tests).
     */
    public static function reset(): void
    {
        self::$decorators = [];
    }
}
