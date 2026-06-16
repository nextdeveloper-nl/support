<?php

namespace NextDeveloper\Support\Authorization\Roles;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use NextDeveloper\IAM\Authorization\Roles\AbstractRole;
use NextDeveloper\IAM\Authorization\Roles\IAuthorizationRole;
use NextDeveloper\IAM\Database\Models\Users;
use NextDeveloper\IAM\Helpers\UserHelper;

class SupportUserRole extends AbstractRole implements IAuthorizationRole
{
    public const NAME = 'support-user';

    public const LEVEL = 150;

    public const DESCRIPTION = 'Support User';

    public const DB_PREFIX = 'support';

    /**
     * Applies basic member role sql for Eloquent
     *
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $table = $model->getTable();
        $accountId = UserHelper::currentAccount()->id;
        $userId = UserHelper::me()->id;

        //  Knowledge base is public self-serve: a customer may read any published article.
        if (in_array($table, ['support_kb_articles', 'support_kb_articles_perspective'], true)) {
            if (Schema::hasColumn($table, 'is_published')) {
                $builder->where('is_published', true);
            }

            return;
        }

        //  Ticket conversation: limit comments to tickets the customer can see.
        if (in_array($table, ['support_ticket_comments', 'support_ticket_comments_perspective'], true)) {
            $builder->whereIn('support_ticket_id', function ($query) use ($accountId, $userId) {
                $query->select('id')
                    ->from('support_tickets')
                    ->where(function ($scope) use ($accountId, $userId) {
                        $scope->where([
                            'iam_account_id' => $accountId,
                            'iam_user_id' => $userId,
                        ])->orWhere('support_seeker_account_id', $accountId);
                    });
            });

            return;
        }

        //  Tickets: own tickets plus tickets where the account is the support seeker.
        if (Schema::hasColumn($table, 'support_seeker_account_id')) {
            $builder->where([
                'iam_account_id' => $accountId,
                'iam_user_id' => $userId,
            ])->orWhere('support_seeker_account_id', $accountId);

            return;
        }

        //  Everything else the customer can read (e.g. their own CSAT): own-account scope.
        $builder->where('iam_account_id', $accountId);
    }

    public function checkPrivileges(?Users $users = null)
    {
        // return UserHelper::hasRole(self::NAME, $users);
    }

    public function getModule()
    {
        return 'support';
    }

    public function allowedOperations(): array
    {
        return [
            'support_tickets:read',
            'support_tickets:create',
            'support_tickets:update',
            'support_tickets:close',
            'support_ticket_comments:read',
            'support_ticket_comments:create',
            'support_ticket_comments:update',
            'support_ticket_comments:delete',
            'support_tests:read',
            'support_tests:create',
            'support_kb_articles:read',
            'support_csats:read',
            'support_csats:create',
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
        if (self::DB_PREFIX === '*') {
            return true;
        }

        if (Str::startsWith($column, self::DB_PREFIX)) {
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
