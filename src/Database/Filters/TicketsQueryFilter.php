<?php

namespace NextDeveloper\Support\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;
use NextDeveloper\Commons\Database\Filters\FilterClauses;
use NextDeveloper\Commons\Database\Models\Categories;
use NextDeveloper\IAM\Database\Models\Accounts;
use NextDeveloper\IAM\Database\Models\Users;
                    

/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class TicketsQueryFilter extends AbstractQueryFilter
{
    /**
     * Filter by tags
     *
     * @param  $values
     * @return Builder
     */
    public function tags($values)
    {
        return FilterClauses::tags($this->builder, $values);
    }

    /**
     * @var Builder
     */
    protected $builder;

    public function title($value)
    {
        return $this->builder->where('title', 'ilike', '%' . $value . '%');
    }


    public function description($value)
    {
        return $this->builder->where('description', 'ilike', '%' . $value . '%');
    }


    public function objectType($value)
    {
        return FilterClauses::objectType($this->builder, $value);
    }

    /**
     * Tickets opened on one of these records (comma separated uuids of the object_type sent along).
     */
    public function objectId($value)
    {
        return FilterClauses::objectId(
            $this->builder,
            $this->request->get('object_type', $this->request->get('objectType')),
            $value
        );
    }

    //  This is an alias function of objectId
    public function object_id($value)
    {
        return $this->objectId($value);
    }

    /**
     * One or more kinds, comma separated (fixlean: error_card, urgent_call, suggestion).
     */
    public function kind($value)
    {
        return $this->builder->whereIn(
            $this->builder->getModel()->qualifyColumn('kind'),
            array_values(array_filter(array_map('trim', explode(',', (string) $value))))
        );
    }

        //  This is an alias function of objectType
    public function object_type($value)
    {
        return $this->objectType($value);
    }

    public function status($value)
    {
        return $this->builder->where('status', 'ilike', '%' . $value . '%');
    }


    public function level($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('level', $operator, $value);
    }


    public function priority($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('priority', $operator, $value);
    }


    public function timeSpent($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('time_spent', $operator, $value);
    }

        //  This is an alias function of timeSpent
    public function time_spent($value)
    {
        return $this->timeSpent($value);
    }

    public function reopenedCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('reopened_count', $operator, $value);
    }

        //  This is an alias function of reopenedCount
    public function reopened_count($value)
    {
        return $this->reopenedCount($value);
    }

    public function isClosed($value)
    {
        return $this->builder->where('is_closed', $value);
    }

        //  This is an alias function of isClosed
    public function is_closed($value)
    {
        return $this->isClosed($value);
    }

    public function isPublic($value)
    {
        return $this->builder->where('is_public', $value);
    }

        //  This is an alias function of isPublic
    public function is_public($value)
    {
        return $this->isPublic($value);
    }

    public function isFirstContactResolution($value)
    {
        return $this->builder->where('is_first_contact_resolution', $value);
    }

        //  This is an alias function of isFirstContactResolution
    public function is_first_contact_resolution($value)
    {
        return $this->isFirstContactResolution($value);
    }

    public function responseTimeStart($date)
    {
        return $this->builder->where('response_time', '>=', $date);
    }

    public function responseTimeEnd($date)
    {
        return $this->builder->where('response_time', '<=', $date);
    }

    //  This is an alias function of responseTime
    public function response_time_start($value)
    {
        return $this->responseTimeStart($value);
    }

    //  This is an alias function of responseTime
    public function response_time_end($value)
    {
        return $this->responseTimeEnd($value);
    }

    public function createdAtStart($date)
    {
        return $this->builder->where('created_at', '>=', $date);
    }

    public function createdAtEnd($date)
    {
        return $this->builder->where('created_at', '<=', $date);
    }

    //  This is an alias function of createdAt
    public function created_at_start($value)
    {
        return $this->createdAtStart($value);
    }

    //  This is an alias function of createdAt
    public function created_at_end($value)
    {
        return $this->createdAtEnd($value);
    }

    public function updatedAtStart($date)
    {
        return $this->builder->where('updated_at', '>=', $date);
    }

    public function updatedAtEnd($date)
    {
        return $this->builder->where('updated_at', '<=', $date);
    }

    //  This is an alias function of updatedAt
    public function updated_at_start($value)
    {
        return $this->updatedAtStart($value);
    }

    //  This is an alias function of updatedAt
    public function updated_at_end($value)
    {
        return $this->updatedAtEnd($value);
    }

    public function deletedAtStart($date)
    {
        return $this->builder->where('deleted_at', '>=', $date);
    }

    public function deletedAtEnd($date)
    {
        return $this->builder->where('deleted_at', '<=', $date);
    }

    //  This is an alias function of deletedAt
    public function deleted_at_start($value)
    {
        return $this->deletedAtStart($value);
    }

    //  This is an alias function of deletedAt
    public function deleted_at_end($value)
    {
        return $this->deletedAtEnd($value);
    }

    public function firstResponseAtStart($date)
    {
        return $this->builder->where('first_response_at', '>=', $date);
    }

    public function firstResponseAtEnd($date)
    {
        return $this->builder->where('first_response_at', '<=', $date);
    }

    //  This is an alias function of firstResponseAt
    public function first_response_at_start($value)
    {
        return $this->firstResponseAtStart($value);
    }

    //  This is an alias function of firstResponseAt
    public function first_response_at_end($value)
    {
        return $this->firstResponseAtEnd($value);
    }

    public function resolvedAtStart($date)
    {
        return $this->builder->where('resolved_at', '>=', $date);
    }

    public function resolvedAtEnd($date)
    {
        return $this->builder->where('resolved_at', '<=', $date);
    }

    //  This is an alias function of resolvedAt
    public function resolved_at_start($value)
    {
        return $this->resolvedAtStart($value);
    }

    //  This is an alias function of resolvedAt
    public function resolved_at_end($value)
    {
        return $this->resolvedAtEnd($value);
    }

    public function slaResolutionDueAtStart($date)
    {
        return $this->builder->where('sla_resolution_due_at', '>=', $date);
    }

    public function slaResolutionDueAtEnd($date)
    {
        return $this->builder->where('sla_resolution_due_at', '<=', $date);
    }

    //  This is an alias function of slaResolutionDueAt
    public function sla_resolution_due_at_start($value)
    {
        return $this->slaResolutionDueAtStart($value);
    }

    //  This is an alias function of slaResolutionDueAt
    public function sla_resolution_due_at_end($value)
    {
        return $this->slaResolutionDueAtEnd($value);
    }

    public function iamAccountId($value)
    {
        return FilterClauses::linkedId($this->builder, 'iam_account_id', \NextDeveloper\IAM\Database\Models\Accounts::class, $value);
    }

    //  This is an alias function of iamAccountId
    public function iam_account_id($value)
    {
        return $this->iamAccountId($value);
    }


    public function iamUserId($value)
    {
        return FilterClauses::linkedId($this->builder, 'iam_user_id', \NextDeveloper\IAM\Database\Models\Users::class, $value);
    }

    //  This is an alias function of iamUserId
    public function iam_user_id($value)
    {
        return $this->iamUserId($value);
    }


    public function responsibleUserId($value)
    {
        return FilterClauses::linkedId($this->builder, 'responsible_user_id', \NextDeveloper\IAM\Database\Models\Users::class, $value);
    }

        //  This is an alias function of responsibleUser
    public function responsible_user_id($value)
    {
        return $this->responsibleUserId($value);
    }

    public function supportSeekerAccountId($value)
    {
        return FilterClauses::linkedId($this->builder, 'support_seeker_account_id', \NextDeveloper\IAM\Database\Models\Accounts::class, $value);
    }

        //  This is an alias function of supportSeekerAccount
    public function support_seeker_account_id($value)
    {
        return $this->supportSeekerAccountId($value);
    }

    public function commonCategoryId($value)
    {
        return FilterClauses::linkedId($this->builder, 'common_category_id', \NextDeveloper\Commons\Database\Models\Categories::class, $value);
    }

        //  This is an alias function of commonCategory
    public function common_category_id($value)
    {
        return $this->commonCategoryId($value);
    }

    public function resolvedByUserId($value)
    {
        return FilterClauses::linkedId($this->builder, 'resolved_by_user_id', \NextDeveloper\IAM\Database\Models\Users::class, $value);
    }

    //  This is an alias function of resolvedByUserId
    public function resolved_by_user_id($value)
    {
        return $this->resolvedByUserId($value);
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}
