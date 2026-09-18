<?php

namespace NextDeveloper\Support\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;
use NextDeveloper\Commons\Database\Filters\FilterClauses;
            

/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class TicketCommentsQueryFilter extends AbstractQueryFilter
{

    /**
     * @var Builder
     */
    protected $builder;

    public function comment($value)
    {
        return $this->builder->where('comment', 'ilike', '%' . $value . '%');
    }


    public function isSystemMessage($value)
    {
        return $this->builder->where('is_system_message', $value);
    }

        //  This is an alias function of isSystemMessage
    public function is_system_message($value)
    {
        return $this->isSystemMessage($value);
    }

    public function isInternal($value)
    {
        return $this->builder->where('is_internal', $value);
    }

        //  This is an alias function of isInternal
    public function is_internal($value)
    {
        return $this->isInternal($value);
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


    public function supportTicketId($value)
    {
        return FilterClauses::linkedId($this->builder, 'support_ticket_id', \NextDeveloper\Support\Database\Models\Tickets::class, $value);
    }

        //  This is an alias function of supportTicket
    public function support_ticket_id($value)
    {
        return $this->supportTicketId($value);
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}
