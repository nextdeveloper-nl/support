<?php

namespace NextDeveloper\Support\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;


/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class CommentsQueryFilter extends AbstractQueryFilter
{
    /**
     * @var Builder
     */
    protected $builder;

    public function comment($value)
    {
        return $this->builder->where('comment', 'ilike', '%' . $value . '%');
    }

    public function createdAtStart($date)
    {
        return $this->builder->where('created_at', '>=', $date);
    }

    public function createdAtEnd($date)
    {
        return $this->builder->where('created_at', '<=', $date);
    }

    public function updatedAtStart($date)
    {
        return $this->builder->where('updated_at', '>=', $date);
    }

    public function updatedAtEnd($date)
    {
        return $this->builder->where('updated_at', '<=', $date);
    }

    public function deletedAtStart($date)
    {
        return $this->builder->where('deleted_at', '>=', $date);
    }

    public function deletedAtEnd($date)
    {
        return $this->builder->where('deleted_at', '<=', $date);
    }

    public function supportTicketId($value)
    {
            $supportTicket = \NextDeveloper\Support\Database\Models\Tickets::where('uuid', $value)->first();

        if($supportTicket) {
            return $this->builder->where('support_ticket_id', '=', $supportTicket->id);
        }
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}
