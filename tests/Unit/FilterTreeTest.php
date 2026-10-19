<?php

namespace NextDeveloper\Support\Tests\Unit;

use InvalidArgumentException;
use NextDeveloper\Support\Helpers\FilterTree;
use NextDeveloper\Support\Helpers\TicketFilterFields;
use NextDeveloper\Support\Helpers\TurkishFold;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * The advanced filter of the ticket lists (`filter_tree`) and the Turkish-insensitive search.
 */
class FilterTreeTest extends TestCase
{
    private const T = 'support_tickets_perspective';

    private static function compile(mixed $tree): ?array
    {
        return FilterTree::compile($tree, TicketFilterFields::fields(), self::T);
    }

    public function test_folds_turkish_letters_and_both_i(): void
    {
        $this->assertSame('guler', TurkishFold::text('Güler'));
        $this->assertSame('isi kontrolu', TurkishFold::text('Isı Kontrolü'));
        $this->assertSame('isi kontrolu', TurkishFold::text('ısı kontrolü'));
        $this->assertSame('istanbul cagri', TurkishFold::text('İSTANBUL ÇAĞRI'));
        $this->assertSame('ornek oneri', TurkishFold::text('ÖRNEK Öneri'));
        $this->assertSame('panel-err-000442', TurkishFold::text('PANEL-ERR-000442'));
        $this->assertSame('%100\\%\\_x%', TurkishFold::pattern('100%_x'));
        $this->assertSame(
            "translate(lower(translate(title, 'İIÇĞÖŞÜÂÎÛ', 'iicgosuaiu')), 'çğıöşüâîû', 'cgiosuaiu')",
            TurkishFold::sql('title')
        );
    }

    /**
     * #704: a typed Turkish letter is meant. "Isı" finds "Isı" / "ISI" but not "Değişimi" (which
     * folds to "degisimi", containing "isi"); a plain ASCII needle still folds ("guler" finds
     * "Güler", "toplanti odasi" finds "Toplantı Odası").
     */
    public function test_a_needle_with_a_turkish_letter_is_matched_without_folding(): void
    {
        $this->assertFalse(TurkishFold::keepsLetters('toplanti odasi'));
        $this->assertFalse(TurkishFold::keepsLetters('ISI'));
        $this->assertTrue(TurkishFold::keepsLetters('Isı'));
        $this->assertTrue(TurkishFold::keepsLetters('İSTASYON'));

        $this->assertSame('ısı', TurkishFold::needle('Isı'));
        $this->assertSame('ısı', TurkishFold::needle('ISı'));
        $this->assertSame('istasyon çağrı', TurkishFold::needle('İSTASYON ÇAĞRI'));
        $this->assertSame('guler', TurkishFold::needle('guler'));
        $this->assertSame('toplanti odasi', TurkishFold::needle('Toplanti Odasi'));
        $this->assertSame(
            "lower(translate(title, 'IİÇĞÖŞÜÂÎÛ', 'ıiçğöşüâîû'))",
            TurkishFold::sql('title', 'Isı')
        );
        $this->assertSame(TurkishFold::sql('title'), TurkishFold::sql('title', 'isi'));

        // The column side, as PostgreSQL lowers it after the translate (ASCII only in a C locale).
        $column = fn (string $value) => strtolower(strtr($value, ['I' => 'ı', 'İ' => 'i', 'Ç' => 'ç', 'Ğ' => 'ğ', 'Ö' => 'ö', 'Ş' => 'ş', 'Ü' => 'ü']));

        $this->assertStringContainsString(TurkishFold::needle('Isı'), $column('Dolap Isı Kontrolü'));
        $this->assertStringContainsString(TurkishFold::needle('Isı'), $column('DOLAP ISI'));
        $this->assertStringNotContainsString(TurkishFold::needle('Isı'), $column('Merdane Değişimi'));
        $this->assertStringContainsString(TurkishFold::needle('Güler'), $column('Müslim GÜLER'));
        $this->assertStringContainsString(TurkishFold::text('isi'), TurkishFold::text('Merdane Değişimi'));
        $this->assertStringContainsString(TurkishFold::text('guler'), TurkishFold::text('Müslim Güler'));
        $this->assertStringContainsString(TurkishFold::text('toplanti odasi'), TurkishFold::text('Toplantı Odası'));

        [$sql, $bindings] = self::compile(['c' => 'or', 'r' => [
            ['f' => 'title', 'o' => 'contains', 'v' => 'Isı'],
            ['f' => 'title', 'o' => 'contains', 'v' => 'isi'],
            ['f' => 'title', 'o' => 'anyOf', 'v' => ['Isı Kontrolü']],
        ]]);

        $title = "COALESCE(support_tickets_perspective.title, '')";
        $this->assertSame(
            '(' . TurkishFold::sql($title, 'Isı') . ' LIKE ? OR ' . TurkishFold::sql($title) . ' LIKE ? OR '
            . TurkishFold::sql($title) . ' IN (?))',
            $sql
        );
        $this->assertSame(['%ısı%', '%isi%', 'isi kontrolu'], $bindings);
    }

    public function test_and_or_groups_nest_with_bound_values(): void
    {
        [$sql, $bindings] = self::compile([
            'c' => 'and',
            'r' => [
                ['f' => 'status', 'o' => 'anyOf', 'v' => ['open']],
                ['c' => 'or', 'r' => [
                    ['f' => 'priority', 'o' => 'anyOf', 'v' => [3]],
                    ['f' => 'description', 'o' => 'contains', 'v' => 'Sasa'],
                ]],
            ],
        ]);

        $folded = TurkishFold::sql("COALESCE(support_tickets_perspective.description, '')");

        $this->assertSame(
            '(support_tickets_perspective.status IN (?) AND (support_tickets_perspective.priority IN (?::numeric) OR '
            . $folded . ' LIKE ?))',
            $sql
        );
        $this->assertSame(['open', '3', '%sasa%'], $bindings);
    }

    public function test_text_operators(): void
    {
        $folded = TurkishFold::sql("COALESCE(support_tickets_perspective.title, '')");

        $this->assertSame([$folded . ' NOT LIKE ?', ['%test%']], self::compile(['f' => 'title', 'o' => 'notContains', 'v' => 'TEST']));
        $this->assertSame([$folded . ' = ?', ['sasa']], self::compile(['f' => 'title', 'o' => 'equals', 'v' => 'Sasa']));
        $this->assertSame([$folded . ' <> ?', ['sasa']], self::compile(['f' => 'title', 'o' => 'neq', 'v' => 'Sasa']));
        $this->assertSame([$folded . ' LIKE ?', ['baski%']], self::compile(['f' => 'title', 'o' => 'startsWith', 'v' => 'Baski']));
        $this->assertSame([$folded . ' LIKE ?', ['%fan']], self::compile(['f' => 'title', 'o' => 'endsWith', 'v' => 'Fan']));
        $this->assertSame(
            ['(support_tickets_perspective.title IS NULL OR btrim(support_tickets_perspective.title) = \'\')', []],
            self::compile(['f' => 'title', 'o' => 'isEmpty'])
        );
    }

    public function test_list_operators_keep_rows_without_a_value_on_the_negative_side(): void
    {
        [$sql, $bindings] = self::compile(['f' => 'status', 'o' => 'noneOf', 'v' => ['resolved', 'closed']]);

        $this->assertSame('(support_tickets_perspective.status IS NULL OR NOT support_tickets_perspective.status IN (?, ?))', $sql);
        $this->assertSame(['resolved', 'closed'], $bindings);
    }

    public function test_links_resolve_uuids_in_sql(): void
    {
        $uuid = '0b9d6c1e-6f43-4a5b-9a39-2a3f0e6d1c11';

        [$sql, $bindings] = self::compile(['f' => 'created_by', 'o' => 'anyOf', 'v' => [$uuid]]);

        $this->assertSame('support_tickets_perspective.iam_user_id IN (SELECT id FROM iam_users WHERE uuid IN (?::uuid))', $sql);
        $this->assertSame([$uuid], $bindings);

        [$sql] = self::compile(['f' => 'category', 'o' => 'noneOf', 'v' => [$uuid]]);

        $this->assertSame(
            '(support_tickets_perspective.common_category_id IS NULL OR NOT support_tickets_perspective.common_category_id'
            . ' IN (SELECT id FROM common_categories WHERE uuid IN (?::uuid)))',
            $sql
        );
    }

    public function test_id_arrays_match_when_they_share_an_id(): void
    {
        $uuid = '0b9d6c1e-6f43-4a5b-9a39-2a3f0e6d1c11';
        $fields = ['station' => ['type' => 'links', 'sql' => '{t}.station_card_ids', 'table' => 'fixlean_station_cards']];

        [$sql, $bindings] = FilterTree::compile(['f' => 'station', 'o' => 'anyOf', 'v' => [$uuid, strtoupper($uuid)]], $fields, 'plans');

        $this->assertSame('plans.station_card_ids && ARRAY(SELECT id FROM fixlean_station_cards WHERE uuid IN (?::uuid, ?::uuid))', $sql);
        $this->assertSame([$uuid, $uuid], $bindings);

        [$sql] = FilterTree::compile(['f' => 'station', 'o' => 'noneOf', 'v' => [$uuid]], $fields, 'plans');

        $this->assertSame(
            '(plans.station_card_ids IS NULL OR NOT plans.station_card_ids && ARRAY(SELECT id FROM fixlean_station_cards WHERE uuid IN (?::uuid)))',
            $sql
        );

        $this->assertSame(
            ['(plans.station_card_ids IS NULL OR cardinality(plans.station_card_ids) = 0)', []],
            FilterTree::compile(['f' => 'station', 'o' => 'isEmpty'], $fields, 'plans')
        );

        $this->expectException(InvalidArgumentException::class);

        FilterTree::compile(['f' => 'station', 'o' => 'anyOf', 'v' => [$uuid]], ['station' => ['type' => 'links', 'sql' => '{t}.ids']], 'plans');
    }

    public function test_dates_numbers_and_scores(): void
    {
        [$sql, $bindings] = self::compile(['f' => 'resolved_at', 'o' => 'dateBetween', 'v' => ['from' => '2026-10-01T00:00:00.000Z', 'to' => null]]);

        $this->assertSame('(support_tickets_perspective.resolved_at >= ?::timestamptz)', $sql);
        $this->assertSame(['2026-10-01T00:00:00.000000+00:00'], $bindings);

        [$sql, $bindings] = self::compile(['f' => 'priority', 'o' => 'between', 'v' => ['from' => 2, 'to' => '4']]);

        $this->assertSame('(support_tickets_perspective.priority >= ?::numeric AND support_tickets_perspective.priority <= ?::numeric)', $sql);
        $this->assertSame(['2', '4'], $bindings);

        [$sql] = self::compile(['f' => 'is_scored', 'o' => 'isFalse']);

        $this->assertStringStartsWith('NOT (EXISTS (SELECT 1 FROM support_ticket_audits sta WHERE sta.support_ticket_id = support_tickets_perspective.id', $sql);
    }

    public function test_extensions_add_application_fields(): void
    {
        TicketFilterFields::extend('station_name', ['type' => 'text', 'sql' => '(SELECT name FROM stations WHERE id = {t}.object_id)']);

        [$sql] = self::compile(['f' => 'station_name', 'o' => 'contains', 'v' => 'örnek']);

        $this->assertStringContainsString('stations WHERE id = support_tickets_perspective.object_id', $sql);
    }

    public function test_person_and_category_names_search_as_semi_joins(): void
    {
        [$sql, $bindings] = self::compile(['c' => 'or', 'r' => [
            ['f' => 'created_by_name', 'o' => 'contains', 'v' => 'Güler'],
            ['f' => 'responsible_name', 'o' => 'contains', 'v' => 'guler'],
            ['f' => 'category_name', 'o' => 'equals', 'v' => 'Isıtıcı Arızası'],
        ]]);

        $this->assertSame(
            '(support_tickets_perspective.iam_user_id IN (SELECT u.id FROM iam_users u WHERE '
            . TurkishFold::sql("COALESCE(u.fullname, '')", 'Güler') . ' LIKE ?)'
            . ' OR support_tickets_perspective.responsible_user_id IN (SELECT u.id FROM iam_users u WHERE '
            . TurkishFold::sql("COALESCE(u.fullname, '')") . ' LIKE ?)'
            . ' OR support_tickets_perspective.common_category_id IN (SELECT cc.id FROM common_categories cc WHERE '
            . TurkishFold::sql("COALESCE(cc.name, '')", 'Isıtıcı') . ' = ?))',
            $sql
        );
        $this->assertSame(['%güler%', '%guler%', 'ısıtıcı arızası'], $bindings);
    }

    public function test_negative_and_empty_name_conditions_keep_the_scalar_subquery(): void
    {
        $name = '(SELECT u.fullname FROM iam_users u WHERE u.id = support_tickets_perspective.iam_user_id)';

        $this->assertSame(
            [TurkishFold::sql('COALESCE(' . $name . ", '')") . ' NOT LIKE ?', ['%guler%']],
            self::compile(['f' => 'created_by_name', 'o' => 'notContains', 'v' => 'Guler'])
        );
        $this->assertSame(
            ['(' . $name . ' IS NULL OR btrim(' . $name . ') = \'\')', []],
            self::compile(['f' => 'created_by_name', 'o' => 'isEmpty'])
        );
    }

    public function test_a_related_field_without_its_match_placeholder_is_refused(): void
    {
        $this->expectException(InvalidArgumentException::class);

        FilterTree::compile(['f' => 'name', 'o' => 'contains', 'v' => 'a'], [
            'name' => ['type' => 'text', 'sql' => '{t}.name', 'related' => ['sql' => '{t}.id IN (SELECT 1)', 'text' => 'x.name']],
        ], self::T);
    }

    public function test_groups_nest_down_to_the_depth_limit(): void
    {
        $tree = ['f' => 'title', 'o' => 'contains', 'v' => 'a'];

        for ($i = 0; $i < FilterTree::MAX_DEPTH; $i++) {
            $tree = ['c' => $i % 2 ? 'and' : 'or', 'r' => [$tree, ['f' => 'title', 'o' => 'contains', 'v' => 'b' . $i]]];
        }

        [$sql, $bindings] = self::compile($tree);

        $this->assertStringStartsWith(str_repeat('(', FilterTree::MAX_DEPTH) . 'translate(', $sql);
        $this->assertCount(FilterTree::MAX_DEPTH + 1, $bindings);
    }

    public function test_empty_groups_add_nothing(): void
    {
        $this->assertNull(self::compile(['c' => 'and', 'r' => []]));
        $this->assertNull(self::compile('{"c":"or","r":[{"c":"and","r":[]}]}'));
    }

    public function test_values_never_reach_the_sql(): void
    {
        $evil = "x' OR 1=1; DROP TABLE support_tickets; --";

        [$sql, $bindings] = self::compile(['c' => 'or', 'r' => [
            ['f' => 'title', 'o' => 'contains', 'v' => $evil],
            ['f' => 'status', 'o' => 'in', 'v' => [$evil]],
        ]]);

        $this->assertStringNotContainsString('DROP', $sql);
        $this->assertStringNotContainsString('1=1', $sql);
        $this->assertCount(2, $bindings);
    }

    public static function refusedTrees(): array
    {
        $deep = ['f' => 'title', 'o' => 'contains', 'v' => 'a'];

        for ($i = 0; $i < FilterTree::MAX_DEPTH + 1; $i++) {
            $deep = ['c' => 'and', 'r' => [$deep]];
        }

        return [
            'unknown field' => [['f' => 'password', 'o' => 'equals', 'v' => 'x']],
            'sql as field' => [['f' => 'title) OR (1=1', 'o' => 'contains', 'v' => 'x']],
            'unknown operator' => [['f' => 'title', 'o' => 'regex', 'v' => '.*']],
            'operator of another type' => [['f' => 'title', 'o' => 'gt', 'v' => '1']],
            'raw combinator' => [['c' => 'and 1=1 or', 'r' => [['f' => 'title', 'o' => 'contains', 'v' => 'a']]]],
            'too deep' => [$deep],
            'too many conditions' => [['c' => 'or', 'r' => array_fill(0, FilterTree::MAX_RULES + 1, ['f' => 'title', 'o' => 'contains', 'v' => 'a'])]],
            'array as text' => [['f' => 'title', 'o' => 'contains', 'v' => ['a']]],
            'empty text' => [['f' => 'title', 'o' => 'contains', 'v' => '  ']],
            'text too long' => [['f' => 'title', 'o' => 'contains', 'v' => str_repeat('a', FilterTree::MAX_TEXT + 1)]],
            'not a uuid' => [['f' => 'created_by', 'o' => 'anyOf', 'v' => ['1 OR 1=1']]],
            'empty list' => [['f' => 'status', 'o' => 'anyOf', 'v' => []]],
            'not a number' => [['f' => 'priority', 'o' => 'gt', 'v' => '1; DROP']],
            'not a date' => [['f' => 'created_at', 'o' => 'gt', 'v' => "2026-01-01' OR '1'='1"]],
            'open range' => [['f' => 'created_at', 'o' => 'between', 'v' => ['from' => null, 'to' => '']]],
            'bad json' => ['{"c":"and","r":['],
            'scalar' => ['42'],
            'node not an object' => [['c' => 'and', 'r' => ['title']]],
        ];
    }

    #[DataProvider('refusedTrees')]
    public function test_refuses_trees_outside_the_whitelist(mixed $tree): void
    {
        $this->expectException(InvalidArgumentException::class);

        self::compile($tree);
    }
}
