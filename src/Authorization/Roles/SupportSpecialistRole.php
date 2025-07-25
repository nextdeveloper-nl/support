<?php

namespace NextDeveloper\Support\Authorization\Roles;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use NextDeveloper\IAM\Authorization\Roles\AbstractRole;
use NextDeveloper\IAM\Authorization\Roles\IAuthorizationRole;
use NextDeveloper\IAM\Database\Models\Users;

class SupportSpecialistRole extends AbstractRole implements IAuthorizationRole
{
    public const NAME = 'support-specialist';

    public const LEVEL = 120;

    public const DESCRIPTION = 'Support Specialist';

    public const DB_PREFIX = 'support';

    /**
     * Applies basic member role sql for Eloquent
     *
     * @param Builder $builder
     * @param Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {

    }

    public function checkPrivileges(Users $users = null)
    {
        //return UserHelper::hasRole(self::NAME, $users);
    }

    public function getModule()
    {
        return 'support';
    }

    public function allowedOperations() :array
    {
        return [
            'support_tickets:read',
            'support_tickets:create',
            'support_tickets:update',
            'support_tickets:close',
            'support_comments:read',
            'support_comments:create',
            'support_comments:update',
            'support_comments:delete',
            'support_tests:read',
            'support_tests:create'
        ];
    }

    public function getLevel(): int
    {
        return self::LEVEL;
    }

    public function getDescription(): string
    {
        return self::DESCRIPTION;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function canBeApplied($column)
    {
        if(self::DB_PREFIX === '*') {
            return true;
        }

        if(Str::startsWith($column, self::DB_PREFIX)) {
            return true;
        }

        return false;
    }

    public function getDbPrefix()
    {
        return self::DB_PREFIX;
    }

    public function checkRules(Users $users): bool
    {
        // TODO: Implement checkRules() method.
    }
}
