<?php

namespace NextDeveloper\Support\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;
                

/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class TicketsPerspectiveQueryFilter extends AbstractQueryFilter
{
    /**
     * Filter by tags
     *
     * @param  $values
     * @return Builder
     */
    public function tags($values)
    {
        $tags = explode(',', $values);

        $search = '';

        for($i = 0; $i < count($tags); $i++) {
            $search .= "'" . trim($tags[$i]) . "',";
        }

        $search = substr($search, 0, -1);

        return $this->builder->whereRaw('tags @> ARRAY[' . $search . ']');
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

        
    public function fullname($value)
    {
        return $this->builder->where('fullname', 'ilike', '%' . $value . '%');
    }

        
    public function email($value)
    {
        return $this->builder->where('email', 'ilike', '%' . $value . '%');
    }

        
    public function phoneNumber($value)
    {
        return $this->builder->where('phone_number', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of phoneNumber
    public function phone_number($value)
    {
        return $this->phoneNumber($value);
    }
        
    public function pronoun($value)
    {
        return $this->builder->where('pronoun', 'ilike', '%' . $value . '%');
    }

        
    public function name($value)
    {
        return $this->builder->where('name', 'ilike', '%' . $value . '%');
    }

        
    public function supportSeekerName($value)
    {
        return $this->builder->where('support_seeker_name', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of supportSeekerName
    public function support_seeker_name($value)
    {
        return $this->supportSeekerName($value);
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

    public function iamAccountId($value)
    {
            $iamAccount = \NextDeveloper\IAM\Database\Models\Accounts::where('uuid', $value)->first();

        if($iamAccount) {
            return $this->builder->where('iam_account_id', '=', $iamAccount->id);
        }
    }

    
    public function iamUserId($value)
    {
            $iamUser = \NextDeveloper\IAM\Database\Models\Users::where('uuid', $value)->first();

        if($iamUser) {
            return $this->builder->where('iam_user_id', '=', $iamUser->id);
        }
    }

    
    public function supportSeekerAccountId($value)
    {
            $supportSeekerAccount = \NextDeveloper\Support\Database\Models\SeekerAccounts::where('uuid', $value)->first();

        if($supportSeekerAccount) {
            return $this->builder->where('support_seeker_account_id', '=', $supportSeekerAccount->id);
        }
    }

        //  This is an alias function of supportSeekerAccount
    public function support_seeker_account_id($value)
    {
        return $this->supportSeekerAccount($value);
    }
    
    public function iamAccountTypeId($value)
    {
            $iamAccountType = \NextDeveloper\IAM\Database\Models\AccountTypes::where('uuid', $value)->first();

        if($iamAccountType) {
            return $this->builder->where('iam_account_type_id', '=', $iamAccountType->id);
        }
    }

        //  This is an alias function of iamAccountType
    public function iam_account_type_id($value)
    {
        return $this->iamAccountType($value);
    }
    
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}
