<?php

namespace NextDeveloper\Support\Services;

use NextDeveloper\Support\Services\AbstractServices\AbstractSlaPoliciesService;

/**
 * This class is responsible from managing the data for SlaPolicies
 *
 * Class SlaPoliciesService.
 *
 * @package NextDeveloper\Support\Database\Models
 */
class SlaPoliciesService extends AbstractSlaPoliciesService
{

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

    public static function create(array $data)
    {
        return parent::create(self::normalize($data, true));
    }

    public static function update($id, array $data)
    {
        return parent::update($id, self::normalize($data, false));
    }

    /**
     * A policy for an application's ticket kind (fixlean alert cards) is chosen by its conditions,
     * so the legacy single `priority` column defaults to the lowest priority it lists (or 1). The
     * conditions keep only the lists, without duplicates.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function normalize(array $data, bool $isCreate): array
    {
        if (array_key_exists('conditions', $data)) {
            $conditions = [];

            foreach ((array) $data['conditions'] as $key => $values) {
                if (is_string($key) && is_array($values)) {
                    $conditions[$key] = array_values(array_unique($values, SORT_REGULAR));
                }
            }

            $data['conditions'] = $conditions;
        }

        if ($isCreate && ! array_key_exists('priority', $data)) {
            $priorities = array_map('intval', $data['conditions']['priorities'] ?? []);
            $data['priority'] = $priorities ? min($priorities) : 1;
        }

        if (array_key_exists('work_days', $data)) {
            $data['work_days'] = array_values(array_unique(array_map('intval', (array) $data['work_days'])));
        }

        return $data;
    }
}