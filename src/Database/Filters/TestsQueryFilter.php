<?php

namespace NextDeveloper\Support\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;
        

/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class TestsQueryFilter extends AbstractQueryFilter
{

    /**
     * @var Builder
     */
    protected $builder;
    
    public function name($value)
    {
        return $this->builder->where('name', 'ilike', '%' . $value . '%');
    }

        
    public function result($value)
    {
        return $this->builder->where('result', 'ilike', '%' . $value . '%');
    }

    
    public function isPassed($value)
    {
        return $this->builder->where('is_passed', $value);
    }

        //  This is an alias function of isPassed
    public function is_passed($value)
    {
        return $this->isPassed($value);
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

    public function supportTicketId($value)
    {
            $supportTicket = \NextDeveloper\Support\Database\Models\Tickets::where('uuid', $value)->first();

        if($supportTicket) {
            return $this->builder->where('support_ticket_id', '=', $supportTicket->id);
        }
    }

        //  This is an alias function of supportTicket
    public function support_ticket_id($value)
    {
        return $this->supportTicket($value);
    }
    
    public function commonActionId($value)
    {
            $commonAction = \NextDeveloper\Commons\Database\Models\Actions::where('uuid', $value)->first();

        if($commonAction) {
            return $this->builder->where('common_action_id', '=', $commonAction->id);
        }
    }

        //  This is an alias function of commonAction
    public function common_action_id($value)
    {
        return $this->commonAction($value);
    }
    
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE







}
