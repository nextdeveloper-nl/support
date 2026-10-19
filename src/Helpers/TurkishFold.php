<?php

namespace NextDeveloper\Support\Helpers;

/**
 * Turkish-aware text matching, decided by the needle (#704):
 *
 *  - a needle in plain ASCII is FOLDED with the column: "guler" finds "Güler", "toplanti odasi" finds
 *    "Toplantı Odası", "isi" finds "Isı" (and "Değişimi": "degisimi" contains "isi"). Uppercase
 *    Turkish letters and the dotted/dotless I are mapped to ASCII before lowercasing (PostgreSQL
 *    lowercases `I` to `i`, never `ı`, and a database with the C locale lowercases nothing outside
 *    ASCII), then the lowercase Turkish letters are mapped to ASCII;
 *  - a needle with a Turkish letter (ç ğ ı ö ş ü, İ) was typed on purpose and is matched
 *    case-insensitively WITHOUT folding, the Turkish way (I ↔ ı, İ ↔ i): "Isı" finds "Dolap Isı
 *    Kontrolü" and "ISI", never "Değişimi".
 *
 * The needle is lowered here, the column in SQL (`sql($column, $needle)`), always the same way.
 */
final class TurkishFold
{
    private const UPPER = ['İ' => 'i', 'I' => 'i', 'Ç' => 'c', 'Ğ' => 'g', 'Ö' => 'o', 'Ş' => 's', 'Ü' => 'u', 'Â' => 'a', 'Î' => 'i', 'Û' => 'u'];

    private const LOWER = ['ç' => 'c', 'ğ' => 'g', 'ı' => 'i', 'ö' => 'o', 'ş' => 's', 'ü' => 'u', 'â' => 'a', 'î' => 'i', 'û' => 'u'];

    /** Uppercase letters the Turkish way, before lowercasing the rest (`İ` would lower to `i̇`). */
    private const TURKISH_UPPER = ['I' => 'ı', 'İ' => 'i', 'Ç' => 'ç', 'Ğ' => 'ğ', 'Ö' => 'ö', 'Ş' => 'ş', 'Ü' => 'ü', 'Â' => 'â', 'Î' => 'î', 'Û' => 'û'];

    /**
     * The needle has a Turkish letter, so it is matched without folding (see the class).
     */
    public static function keepsLetters(?string $needle): bool
    {
        return $needle !== null && preg_match('/[çğıöşüÇĞİÖŞÜ]/u', $needle) === 1;
    }

    /**
     * The compared form of an SQL expression for `$needle`: folded (no needle, or a plain ASCII one)
     * or lowered the Turkish way (a needle with a Turkish letter). The expression is the caller's own
     * SQL (a column or a subquery), never a value from the request.
     */
    public static function sql(string $expression, ?string $needle = null): string
    {
        if (self::keepsLetters($needle)) {
            return 'lower(translate(' . $expression . ", 'IİÇĞÖŞÜÂÎÛ', 'ıiçğöşüâîû'))";
        }

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
     * The needle as `sql($column, $needle)` compares the column: folded, or lowered the Turkish way
     * when it has a Turkish letter.
     */
    public static function needle(?string $value): string
    {
        if (!self::keepsLetters($value)) {
            return self::text($value);
        }

        return mb_strtolower(strtr((string) $value, self::TURKISH_UPPER), 'UTF-8');
    }

    /**
     * A LIKE pattern for the needle (`needle()`): its wildcards are escaped, `$prefix` / `$suffix`
     * are the pattern's own `%`.
     */
    public static function pattern(?string $value, string $prefix = '%', string $suffix = '%'): string
    {
        return $prefix . addcslashes(self::needle($value), '%_\\') . $suffix;
    }

    /**
     * Rows whose column contains the text (folded, or the Turkish way for a needle with a Turkish
     * letter).
     *
     * @template T of \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     *
     * @param  T  $builder
     * @return T
     */
    public static function whereContains($builder, string $column, $value)
    {
        return $builder->whereRaw(self::sql($column, (string) $value) . ' LIKE ?', [self::pattern((string) $value)]);
    }
}
