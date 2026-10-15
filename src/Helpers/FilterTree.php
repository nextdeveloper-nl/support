<?php

namespace NextDeveloper\Support\Helpers;

use DateTimeImmutable;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Throwable;

/**
 * An advanced-filter tree (AND / OR groups of field conditions) turned into one SQL condition.
 *
 * The tree is the client's compact form: a group `{"c": "and"|"or", "r": [node, ...]}`, a condition
 * `{"f": field, "o": operator, "v": value}`. Nothing in it reaches the SQL as text: the field picks a
 * definition from the caller's whitelist (its SQL is the caller's own), the operator picks one of the
 * fixed templates below, and every value is bound. Anything else - an unknown field or operator, an
 * operator the field's type does not take, a value of the wrong shape, a tree too deep or too large -
 * is refused with an InvalidArgumentException.
 *
 * A field definition: `['type' => text|enum|number|date|link|links|uuid|bool, 'sql' => expression]`,
 * where `{t}` in the expression stands for the queried table. A `link` field also names the `table`
 * whose uuids the values are (`{sql} IN (SELECT id FROM table WHERE uuid IN (...))`); a `links` field
 * is an array of such ids (`bigint[]`) and matches when it shares one of them (`&&`).
 *
 * Text conditions are Turkish-insensitive (TurkishFold). Negative conditions match the rows with no
 * value too ("does not contain X" holds for an empty description), as the client's own filter does.
 */
final class FilterTree
{
    /** Group nesting, the root group included (the client allows two levels below the root). */
    public const MAX_DEPTH = 4;

    /** Conditions in one tree. */
    public const MAX_RULES = 60;

    /** Values in one list condition. */
    public const MAX_VALUES = 500;

    /** Characters in one text value. */
    public const MAX_TEXT = 200;

    public const TYPES = ['text', 'enum', 'number', 'date', 'link', 'links', 'uuid', 'bool'];

    /** Short and alternative operator names → the client's operator ids. */
    private const ALIASES = [
        'eq' => 'equals',
        'neq' => 'notEquals',
        'in' => 'anyOf',
        'notIn' => 'noneOf',
        'numberEquals' => 'equals',
        'numberNotEquals' => 'notEquals',
        'dateAfter' => 'gt',
        'dateBefore' => 'lt',
        'dateBetween' => 'between',
    ];

    /** Operators each field type takes. */
    private const OPERATORS = [
        'text' => ['contains', 'notContains', 'equals', 'notEquals', 'startsWith', 'endsWith', 'anyOf', 'noneOf', 'isEmpty', 'isNotEmpty'],
        'enum' => ['equals', 'notEquals', 'anyOf', 'noneOf', 'isEmpty', 'isNotEmpty'],
        'number' => ['equals', 'notEquals', 'gt', 'gte', 'lt', 'lte', 'between', 'anyOf', 'noneOf', 'isEmpty', 'isNotEmpty'],
        'date' => ['gt', 'gte', 'lt', 'lte', 'between', 'isEmpty', 'isNotEmpty'],
        'link' => ['equals', 'notEquals', 'anyOf', 'noneOf', 'isEmpty', 'isNotEmpty'],
        'links' => ['equals', 'notEquals', 'anyOf', 'noneOf', 'isEmpty', 'isNotEmpty'],
        'uuid' => ['equals', 'notEquals', 'anyOf', 'noneOf', 'isEmpty', 'isNotEmpty'],
        'bool' => ['isTrue', 'isFalse'],
    ];

    /**
     * The tree as a nested array: a JSON text or an array as the query string parsed it.
     *
     * @return array<string, mixed>
     */
    public static function decode(mixed $tree): array
    {
        if (is_string($tree)) {
            if (strlen($tree) > 65536) {
                throw new InvalidArgumentException('The filter tree is too large.');
            }

            try {
                $tree = json_decode($tree, true, 64, JSON_THROW_ON_ERROR);
            } catch (Throwable) {
                throw new InvalidArgumentException('The filter tree is not valid JSON.');
            }
        }

        if (!is_array($tree)) {
            throw new InvalidArgumentException('The filter tree must be an object.');
        }

        return $tree;
    }

    /**
     * The tree as one condition: `[sql, bindings]`, or null when it holds no condition.
     *
     * @param  array<string, array{type: string, sql: string, table?: string}>  $fields
     * @return array{0: string, 1: array<int, mixed>}|null
     */
    public static function compile(mixed $tree, array $fields, string $table): ?array
    {
        $count = 0;

        return self::node(self::decode($tree), $fields, $table, 1, $count);
    }

    /**
     * Narrows the query to the tree's rows (one parenthesised condition, ANDed with the rest).
     *
     * @template T of \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     *
     * @param  T  $builder
     * @param  array<string, array{type: string, sql: string, table?: string}>  $fields
     * @return T
     */
    public static function apply($builder, mixed $tree, array $fields)
    {
        $table = method_exists($builder, 'getModel') ? $builder->getModel()->getTable() : $builder->from;
        $compiled = self::compile($tree, $fields, (string) $table);

        if ($compiled === null) {
            return $builder;
        }

        return $builder->whereRaw($compiled[0], $compiled[1]);
    }

    /**
     * @return array{0: string, 1: array<int, mixed>}|null
     */
    private static function node(mixed $node, array $fields, string $table, int $depth, int &$count): ?array
    {
        if (!is_array($node)) {
            throw new InvalidArgumentException('A filter node must be an object.');
        }

        if (array_key_exists('r', $node)) {
            return self::group($node, $fields, $table, $depth, $count);
        }

        if (++$count > self::MAX_RULES) {
            throw new InvalidArgumentException('The filter tree has too many conditions.');
        }

        return self::rule($node, $fields, $table);
    }

    /**
     * @return array{0: string, 1: array<int, mixed>}|null
     */
    private static function group(array $node, array $fields, string $table, int $depth, int &$count): ?array
    {
        if ($depth > self::MAX_DEPTH) {
            throw new InvalidArgumentException('The filter tree is nested too deep.');
        }

        $combinator = strtolower((string) ($node['c'] ?? 'and'));

        if (!in_array($combinator, ['and', 'or'], true)) {
            throw new InvalidArgumentException('A filter group combines with "and" or "or".');
        }

        if (!is_array($node['r']) || !array_is_list($node['r'])) {
            throw new InvalidArgumentException('A filter group holds a list of conditions.');
        }

        $parts = [];
        $bindings = [];

        foreach ($node['r'] as $child) {
            $compiled = self::node($child, $fields, $table, $depth + 1, $count);

            if ($compiled === null) {
                continue;
            }

            $parts[] = $compiled[0];
            array_push($bindings, ...$compiled[1]);
        }

        if (!$parts) {
            return null;
        }

        return ['(' . implode(' ' . strtoupper($combinator) . ' ', $parts) . ')', $bindings];
    }

    /**
     * @return array{0: string, 1: array<int, mixed>}
     */
    private static function rule(array $node, array $fields, string $table): array
    {
        $name = $node['f'] ?? null;

        if (!is_string($name) || !array_key_exists($name, $fields)) {
            throw new InvalidArgumentException('Unknown filter field "' . Str::limit((string) (is_scalar($name) ? $name : ''), 40) . '".');
        }

        $field = $fields[$name];
        $type = $field['type'] ?? null;

        if (!in_array($type, self::TYPES, true) || !is_string($field['sql'] ?? null)) {
            throw new InvalidArgumentException('The filter field "' . $name . '" is not defined correctly.');
        }

        $operator = is_string($node['o'] ?? null) ? $node['o'] : '';
        $operator = self::ALIASES[$operator] ?? $operator;

        if (!in_array($operator, self::OPERATORS[$type], true)) {
            throw new InvalidArgumentException('The filter field "' . $name . '" does not take the operator "' . Str::limit($operator, 20) . '".');
        }

        if (in_array($type, ['link', 'links'], true) && !preg_match('/^[a-z_][a-z0-9_]*$/', (string) ($field['table'] ?? ''))) {
            throw new InvalidArgumentException('The filter field "' . $name . '" is not defined correctly.');
        }

        $sql = str_replace('{t}', $table, $field['sql']);
        $value = $node['v'] ?? null;

        if ($operator === 'isEmpty' || $operator === 'isNotEmpty') {
            $empty = match ($type) {
                'text' => '(' . $sql . ' IS NULL OR btrim(' . $sql . ') = \'\')',
                'links' => '(' . $sql . ' IS NULL OR cardinality(' . $sql . ') = 0)',
                default => '(' . $sql . ' IS NULL)',
            };

            return [$operator === 'isEmpty' ? $empty : 'NOT ' . $empty, []];
        }

        return match ($type) {
            'text' => self::text($sql, $operator, $value, $name),
            'enum' => self::listed($sql, $operator, $value, $name, fn ($v) => self::scalarText($v, $name), '?'),
            'number' => self::number($sql, $operator, $value, $name),
            'date' => self::date($sql, $operator, $value, $name),
            'link' => self::listed(
                $sql . ' IN (SELECT id FROM ' . ($field['table'] ?? '') . ' WHERE uuid IN (%s))',
                $operator,
                $value,
                $name,
                fn ($v) => self::uuid($v, $name),
                '?::uuid',
                $sql
            ),
            'links' => self::listed(
                $sql . ' && ARRAY(SELECT id FROM ' . ($field['table'] ?? '') . ' WHERE uuid IN (%s))',
                $operator,
                $value,
                $name,
                fn ($v) => self::uuid($v, $name),
                '?::uuid',
                $sql
            ),
            'uuid' => self::listed($sql, $operator, $value, $name, fn ($v) => self::uuid($v, $name), '?::uuid'),
            'bool' => [$operator === 'isTrue' ? '(' . $sql . ')' : 'NOT (' . $sql . ')', []],
        };
    }

    /**
     * @return array{0: string, 1: array<int, mixed>}
     */
    private static function text(string $sql, string $operator, mixed $value, string $name): array
    {
        $folded = TurkishFold::sql('COALESCE(' . $sql . ", '')");

        if ($operator === 'anyOf' || $operator === 'noneOf') {
            $values = array_map(fn ($v) => TurkishFold::text(self::scalarText($v, $name)), self::values($value, $name));
            $in = $folded . ' IN (' . implode(', ', array_fill(0, count($values), '?')) . ')';

            return [$operator === 'anyOf' ? $in : 'NOT ' . $in, $values];
        }

        $text = self::scalarText($value, $name);

        if ($text === '') {
            throw new InvalidArgumentException('The filter field "' . $name . '" needs a text.');
        }

        return match ($operator) {
            'contains' => [$folded . ' LIKE ?', [TurkishFold::pattern($text)]],
            'notContains' => [$folded . ' NOT LIKE ?', [TurkishFold::pattern($text)]],
            'startsWith' => [$folded . ' LIKE ?', [TurkishFold::pattern($text, '')]],
            'endsWith' => [$folded . ' LIKE ?', [TurkishFold::pattern($text, '%', '')]],
            'equals' => [$folded . ' = ?', [TurkishFold::text($text)]],
            'notEquals' => [$folded . ' <> ?', [TurkishFold::text($text)]],
        };
    }

    /**
     * Equality and list conditions. `$sql` is either the compared expression or, with `$nullable`
     * given, a template whose `%s` takes the placeholders and `$nullable` is the bare expression.
     *
     * @return array{0: string, 1: array<int, mixed>}
     */
    private static function listed(
        string $sql,
        string $operator,
        mixed $value,
        string $name,
        callable $cast,
        string $placeholder,
        ?string $nullable = null
    ): array {
        $values = in_array($operator, ['equals', 'notEquals'], true)
            ? [$cast($value)]
            : array_map($cast, self::values($value, $name));

        $placeholders = implode(', ', array_fill(0, count($values), $placeholder));
        $in = $nullable === null ? $sql . ' IN (' . $placeholders . ')' : sprintf($sql, $placeholders);
        $bare = $nullable ?? $sql;

        if (in_array($operator, ['equals', 'anyOf'], true)) {
            return [$in, $values];
        }

        return ['(' . $bare . ' IS NULL OR NOT ' . $in . ')', $values];
    }

    /**
     * @return array{0: string, 1: array<int, mixed>}
     */
    private static function number(string $sql, string $operator, mixed $value, string $name): array
    {
        $cast = fn ($v) => self::numeric($v, $name);

        return match ($operator) {
            'equals', 'notEquals', 'anyOf', 'noneOf' => self::listed($sql, $operator, $value, $name, $cast, '?::numeric'),
            'between' => self::between($sql, $value, $name, $cast, '?::numeric'),
            default => self::compare($sql, $operator, $cast($value), '?::numeric'),
        };
    }

    /**
     * @return array{0: string, 1: array<int, mixed>}
     */
    private static function date(string $sql, string $operator, mixed $value, string $name): array
    {
        $cast = fn ($v) => self::timestamp($v, $name);

        return $operator === 'between'
            ? self::between($sql, $value, $name, $cast, '?::timestamptz')
            : self::compare($sql, $operator, $cast($value), '?::timestamptz');
    }

    /**
     * @return array{0: string, 1: array<int, mixed>}
     */
    private static function compare(string $sql, string $operator, mixed $value, string $placeholder): array
    {
        $comparison = ['gt' => '>', 'gte' => '>=', 'lt' => '<', 'lte' => '<='][$operator];

        return [$sql . ' ' . $comparison . ' ' . $placeholder, [$value]];
    }

    /**
     * A range with either end open (`{"from": .., "to": ..}`); both ends missing is refused.
     *
     * @return array{0: string, 1: array<int, mixed>}
     */
    private static function between(string $sql, mixed $value, string $name, callable $cast, string $placeholder): array
    {
        if (!is_array($value)) {
            throw new InvalidArgumentException('The filter field "' . $name . '" needs a range.');
        }

        $parts = [];
        $bindings = [];

        foreach (['from' => '>=', 'to' => '<='] as $end => $comparison) {
            if (($value[$end] ?? null) === null || $value[$end] === '') {
                continue;
            }

            $parts[] = $sql . ' ' . $comparison . ' ' . $placeholder;
            $bindings[] = $cast($value[$end]);
        }

        if (!$parts) {
            throw new InvalidArgumentException('The filter field "' . $name . '" needs a range.');
        }

        return ['(' . implode(' AND ', $parts) . ')', $bindings];
    }

    /**
     * @return array<int, mixed>
     */
    private static function values(mixed $value, string $name): array
    {
        if (!is_array($value) || !array_is_list($value) || !$value) {
            throw new InvalidArgumentException('The filter field "' . $name . '" needs a list of values.');
        }

        if (count($value) > self::MAX_VALUES) {
            throw new InvalidArgumentException('The filter field "' . $name . '" has too many values.');
        }

        return $value;
    }

    private static function scalarText(mixed $value, string $name): string
    {
        if (!is_string($value) && !is_int($value) && !is_float($value)) {
            throw new InvalidArgumentException('The filter field "' . $name . '" needs a text.');
        }

        $text = trim((string) $value);

        if (mb_strlen($text) > self::MAX_TEXT) {
            throw new InvalidArgumentException('The filter field "' . $name . '" has a text that is too long.');
        }

        return $text;
    }

    private static function numeric(mixed $value, string $name): string
    {
        if ((!is_int($value) && !is_float($value) && !is_string($value)) || !is_numeric($value) || strlen((string) $value) > 32) {
            throw new InvalidArgumentException('The filter field "' . $name . '" needs a number.');
        }

        return (string) (0 + $value);
    }

    private static function timestamp(mixed $value, string $name): string
    {
        if (!is_string($value) || $value === '' || strlen($value) > 40) {
            throw new InvalidArgumentException('The filter field "' . $name . '" needs a date.');
        }

        try {
            return (new DateTimeImmutable($value))->format('Y-m-d\TH:i:s.uP');
        } catch (Throwable) {
            throw new InvalidArgumentException('The filter field "' . $name . '" needs a date.');
        }
    }

    private static function uuid(mixed $value, string $name): string
    {
        if (!is_string($value) || !Str::isUuid($value)) {
            throw new InvalidArgumentException('The filter field "' . $name . '" needs record ids.');
        }

        return strtolower($value);
    }
}
