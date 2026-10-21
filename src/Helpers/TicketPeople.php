<?php

namespace NextDeveloper\Support\Helpers;

use Illuminate\Database\Eloquent\Model;
use NextDeveloper\IAM\Database\Models\Users;

/**
 * The names of the people on a ticket, served with it as the perspective serves them (fixlean #736):
 * who opened it (`fullname`), who works on it (`responsible_name`) and who resolved it
 * (`resolved_by_name`). A reader of the ticket may not be allowed to read users (a quality lead
 * reading suggestions), and the lists showed a blank person where the creator goes. The ticket is
 * already authorized when it is served; nothing else of the users is served.
 */
final class TicketPeople
{
    /** User link column => the name field served for it. */
    public const FIELDS = [
        'iam_user_id' => 'fullname',
        'responsible_user_id' => 'responsible_name',
        'resolved_by_user_id' => 'resolved_by_name',
    ];

    /**
     * @var array<int, array{fullname?: ?string, name?: ?string, surname?: ?string}> users by id, for this process
     */
    private static array $memo = [];

    /**
     * @return array<string, ?string>
     */
    public static function names(Model $ticket): array
    {
        $links = [];

        foreach (array_keys(self::FIELDS) as $column) {
            $links[$column] = $ticket->getAttribute($column);
        }

        $ids = array_map('intval', array_values(array_filter($links, fn ($id) => is_numeric($id))));
        $missing = array_values(array_unique(array_diff($ids, array_keys(self::$memo))));

        if ($missing) {
            $found = Users::withoutGlobalScopes()->whereIn('id', $missing)->get(['id', 'fullname', 'name', 'surname'])
                ->mapWithKeys(fn ($user) => [(int) $user->id => $user->only(['fullname', 'name', 'surname'])])
                ->all();

            foreach ($missing as $id) {
                self::$memo[$id] = $found[$id] ?? [];
            }
        }

        return self::fields($links, self::$memo);
    }

    /**
     * The name fields of a ticket's user links. Pure.
     *
     * @param  array<string, mixed>  $links  user link column => internal user id
     * @param  array<int, array{fullname?: ?string, name?: ?string, surname?: ?string}>  $users  by id
     * @return array<string, ?string>
     */
    public static function fields(array $links, array $users): array
    {
        $fields = [];

        foreach (self::FIELDS as $column => $field) {
            $id = $links[$column] ?? null;
            $fields[$field] = is_numeric($id) && isset($users[(int) $id]) ? self::displayName($users[(int) $id]) : null;
        }

        return $fields;
    }

    /**
     * @param  array{fullname?: ?string, name?: ?string, surname?: ?string}  $user
     */
    private static function displayName(array $user): ?string
    {
        $name = trim((string) ($user['fullname'] ?? ''));

        if ($name === '') {
            $name = trim(trim((string) ($user['name'] ?? '')).' '.trim((string) ($user['surname'] ?? '')));
        }

        return $name === '' ? null : $name;
    }

    /**
     * Forgets the users read (tests, long-running workers between jobs).
     */
    public static function reset(): void
    {
        self::$memo = [];
    }
}
