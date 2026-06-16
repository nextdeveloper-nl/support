<?php

namespace NextDeveloper\Support\Services;

use NextDeveloper\Commons\Helpers\DatabaseHelper;
use NextDeveloper\Support\Services\AbstractServices\AbstractAgentExpertisesService;

/**
 * This class is responsible from managing the data for AgentExpertises
 *
 * Class AgentExpertisesService.
 */
class AgentExpertisesService extends AbstractAgentExpertisesService
{
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

    public static function create(array $data)
    {
        return parent::create(self::normalizeCategory($data));
    }

    public static function update($id, array $data)
    {
        return parent::update($id, self::normalizeCategory($data));
    }

    /**
     * Converts a category uuid from the API to the integer FK the column expects.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private static function normalizeCategory(array $data): array
    {
        if (array_key_exists('common_category_id', $data)
            && $data['common_category_id']
            && ! is_numeric($data['common_category_id'])) {
            $data['common_category_id'] = DatabaseHelper::uuidToId(
                '\NextDeveloper\Commons\Database\Models\Categories',
                $data['common_category_id']
            );
        }

        return $data;
    }
}
