<?php

namespace NextDeveloper\Support\Helpers;

/**
 * The fields a ticket filter tree (`filter_tree`, see FilterTree) may name, on `support_tickets` and
 * on `support_tickets_perspective` alike: every expression reads the ticket's own columns, names and
 * scores come from subqueries, so both lists filter the same way.
 *
 * An application adds the fields of its own records with `extend()` (fixlean: the station a ticket
 * was opened on). The SQL of a field is always the application's, never the request's.
 */
final class TicketFilterFields
{
    /** The latest positive score audit of the ticket (status audits carry point 0). */
    private const SCORE_AUDIT = '(SELECT %s FROM support_ticket_audits sta WHERE sta.support_ticket_id = {t}.id'
        . ' AND sta.deleted_at IS NULL AND sta.point > 0 ORDER BY sta.created_at DESC, sta.id DESC LIMIT 1)';

    private const USER_NAME = '(SELECT u.fullname FROM iam_users u WHERE u.id = {t}.%s)';

    /** The same name as a semi-join for the search box (FilterTree `related`). */
    private const USER_MATCH = '{t}.%s IN (SELECT u.id FROM iam_users u WHERE {match})';

    /**
     * @var array<string, array{type: string, sql: string, table?: string, related?: array{sql: string, text: string}}>
     */
    private static array $extensions = [];

    /**
     * @return array<string, array{type: string, sql: string, table?: string, related?: array{sql: string, text: string}}>
     */
    public static function fields(): array
    {
        return array_merge([
            'uuid' => ['type' => 'uuid', 'sql' => '{t}.uuid'],
            'number' => ['type' => 'text', 'sql' => '{t}.number'],
            'title' => ['type' => 'text', 'sql' => '{t}.title'],
            'description' => ['type' => 'text', 'sql' => '{t}.description'],
            //  What a list shows as the ticket's text: its description, else its title.
            'text' => ['type' => 'text', 'sql' => "COALESCE(NULLIF({t}.description, ''), {t}.title)"],
            'kind' => ['type' => 'enum', 'sql' => '{t}.kind'],
            'status' => ['type' => 'enum', 'sql' => '{t}.status'],
            'priority' => ['type' => 'number', 'sql' => '{t}.priority'],
            'category' => ['type' => 'link', 'sql' => '{t}.common_category_id', 'table' => 'common_categories'],
            'category_name' => [
                'type' => 'text',
                'sql' => '(SELECT cc.name FROM common_categories cc WHERE cc.id = {t}.common_category_id)',
                'related' => ['sql' => '{t}.common_category_id IN (SELECT cc.id FROM common_categories cc WHERE {match})', 'text' => 'cc.name'],
            ],
            'created_by' => ['type' => 'link', 'sql' => '{t}.iam_user_id', 'table' => 'iam_users'],
            'created_by_name' => self::userName('iam_user_id'),
            'responsible' => ['type' => 'link', 'sql' => '{t}.responsible_user_id', 'table' => 'iam_users'],
            'responsible_name' => self::userName('responsible_user_id'),
            'resolved_by' => ['type' => 'link', 'sql' => '{t}.resolved_by_user_id', 'table' => 'iam_users'],
            'resolved_by_name' => self::userName('resolved_by_user_id'),
            'created_at' => ['type' => 'date', 'sql' => '{t}.created_at'],
            'updated_at' => ['type' => 'date', 'sql' => '{t}.updated_at'],
            'first_response_at' => ['type' => 'date', 'sql' => '{t}.first_response_at'],
            'resolved_at' => ['type' => 'date', 'sql' => '{t}.resolved_at'],
            //  The resolution note: the latest comment that is not a system message.
            'note' => [
                'type' => 'text',
                'sql' => '(SELECT stc.comment FROM support_ticket_comments stc WHERE stc.support_ticket_id = {t}.id'
                    . ' AND stc.deleted_at IS NULL AND stc.is_system_message IS NOT TRUE ORDER BY stc.created_at DESC, stc.id DESC LIMIT 1)',
            ],
            'is_scored' => [
                'type' => 'bool',
                'sql' => 'EXISTS (SELECT 1 FROM support_ticket_audits sta WHERE sta.support_ticket_id = {t}.id'
                    . ' AND sta.deleted_at IS NULL AND sta.point > 0)',
            ],
            'score' => ['type' => 'number', 'sql' => sprintf(self::SCORE_AUDIT, 'sta.point')],
            'scored_by' => ['type' => 'link', 'sql' => sprintf(self::SCORE_AUDIT, 'sta.iam_user_id'), 'table' => 'iam_users'],
            'scored_at' => ['type' => 'date', 'sql' => sprintf(self::SCORE_AUDIT, 'sta.created_at')],
        ], self::$extensions);
    }

    /**
     * The full name of the user a ticket column names.
     *
     * @return array{type: string, sql: string, related: array{sql: string, text: string}}
     */
    private static function userName(string $column): array
    {
        return [
            'type' => 'text',
            'sql' => sprintf(self::USER_NAME, $column),
            'related' => ['sql' => sprintf(self::USER_MATCH, $column), 'text' => 'u.fullname'],
        ];
    }

    /**
     * Adds (or replaces) a field. Called by applications at boot.
     *
     * @param  array{type: string, sql: string, table?: string, related?: array{sql: string, text: string}}  $definition
     */
    public static function extend(string $name, array $definition): void
    {
        self::$extensions[$name] = $definition;
    }
}
