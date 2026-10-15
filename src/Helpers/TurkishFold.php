<?php

namespace NextDeveloper\Support\Helpers;

/**
 * Turkish-insensitive text matching: "guler" finds "Güler", "isi" and "ısı" find "Isı".
 *
 * Both sides go through the same folding: uppercase Turkish letters and the dotted/dotless I are
 * mapped to ASCII before lowercasing (PostgreSQL lowercases `I` to `i`, never `ı`, and a database
 * with the C locale lowercases nothing outside ASCII), then the lowercase Turkish letters are mapped
 * to ASCII. The needle is folded here, the column in SQL.
 */
final class TurkishFold
{
    private const UPPER = ['İ' => 'i', 'I' => 'i', 'Ç' => 'c', 'Ğ' => 'g', 'Ö' => 'o', 'Ş' => 's', 'Ü' => 'u', 'Â' => 'a', 'Î' => 'i', 'Û' => 'u'];

    private const LOWER = ['ç' => 'c', 'ğ' => 'g', 'ı' => 'i', 'ö' => 'o', 'ş' => 's', 'ü' => 'u', 'â' => 'a', 'î' => 'i', 'û' => 'u'];

    /**
     * The folded form of an SQL expression. The expression is the caller's own SQL (a column or a
     * subquery), never a value from the request.
     */
    public static function sql(string $expression): string
    {
        return 'translate(lower(translate(' . $expression . ", 'İIÇĞÖŞÜÂÎÛ', 'iicgosuaiu')), 'çğıöşüâîû', 'cgiosuaiu')";
    }

    /**
     * The folded form of a text, as `sql()` folds a column.
     */
    public static function text(?string $value): string
    {
        return strtr(mb_strtolower(strtr((string) $value, self::UPPER), 'UTF-8'), self::LOWER);
    }

    /**
     * A LIKE pattern for the folded text: its wildcards are escaped, `$prefix` / `$suffix` are the
     * pattern's own `%`.
     */
    public static function pattern(?string $value, string $prefix = '%', string $suffix = '%'): string
    {
        return $prefix . addcslashes(self::text($value), '%_\\') . $suffix;
    }

    /**
     * Rows whose column contains the text, Turkish-insensitively.
     *
     * @template T of \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     *
     * @param  T  $builder
     * @return T
     */
    public static function whereContains($builder, string $column, $value)
    {
        return $builder->whereRaw(self::sql($column) . ' LIKE ?', [self::pattern((string) $value)]);
    }
}
