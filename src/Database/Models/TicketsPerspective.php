<?php

namespace NextDeveloper\Support\Database\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use NextDeveloper\Commons\Database\Traits\HasStates;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use NextDeveloper\Commons\Database\Traits\Filterable;
use NextDeveloper\Support\Database\Observers\TicketsPerspectiveObserver;
use NextDeveloper\Commons\Database\Traits\UuidId;
use NextDeveloper\Commons\Common\Cache\Traits\CleanCache;
use NextDeveloper\Commons\Database\Traits\Taggable;
use NextDeveloper\Commons\Database\Traits\RunAsAdministrator;
use NextDeveloper\Commons\Database\Traits\HasObject;

/**
 * TicketsPerspective model.
 *
 * @package  NextDeveloper\Support\Database\Models
 * @property integer $id
 * @property string $uuid
 * @property string $title
 * @property string $description
 * @property integer $iam_account_id
 * @property integer $iam_user_id
 * @property array $tags
 * @property boolean $is_closed
 * @property boolean $is_public
 * @property integer $level
 * @property integer $priority
 * @property string $status
 * @property integer $common_category_id
 * @property \Carbon\Carbon $response_time
 * @property \Carbon\Carbon $first_response_at
 * @property \Carbon\Carbon $resolved_at
 * @property \Carbon\Carbon $sla_resolution_due_at
 * @property boolean $sla_response_breached
 * @property boolean $sla_resolution_breached
 * @property integer $reopened_count
 * @property boolean $is_first_contact_resolution
 * @property integer $object_id
 * @property string $object_type
 * @property integer $responsible_user_id
 * @property integer $time_spent
 * @property array $watcher_user_ids
 * @property array $watcher_account_ids
 * @property integer $support_seeker_account_id
 * @property string $fullname
 * @property string $email
 * @property string $phone_number
 * @property string $pronoun
 * @property string $name
 * @property integer $iam_account_type_id
 * @property string $support_seeker_name
 * @property string $responsible_name
 * @property string $category_name
 * @property integer $csat_score
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 */
class TicketsPerspective extends Model
{
    use Filterable, UuidId, CleanCache, Taggable, HasStates, RunAsAdministrator, HasObject;
    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'support_tickets_perspective';


    /**
     @var array
     */
    protected $guarded = [];

    protected $fillable = [
            'title',
            'description',
            'iam_account_id',
            'iam_user_id',
            'tags',
            'is_closed',
            'is_public',
            'level',
            'priority',
            'status',
            'common_category_id',
            'response_time',
            'first_response_at',
            'resolved_at',
            'sla_resolution_due_at',
            'sla_response_breached',
            'sla_resolution_breached',
            'reopened_count',
            'is_first_contact_resolution',
            'object_id',
            'object_type',
            'responsible_user_id',
            'time_spent',
            'watcher_user_ids',
            'watcher_account_ids',
            'support_seeker_account_id',
            'fullname',
            'email',
            'phone_number',
            'pronoun',
            'name',
            'iam_account_type_id',
            'support_seeker_name',
            'responsible_name',
            'category_name',
            'csat_score',
    ];

    /**
      Here we have the fulltext fields. We can use these for fulltext search if enabled.
     */
    protected $fullTextFields = [

    ];

    /**
     @var array
     */
    protected $appends = [

    ];

    /**
     We are casting fields to objects so that we can work on them better
     *
     @var array
     */
    protected $casts = [
    'id' => 'integer',
    'title' => 'string',
    'description' => 'string',
    'tags' => \NextDeveloper\Commons\Database\Casts\TextArray::class,
    'is_closed' => 'boolean',
    'is_public' => 'boolean',
    'level' => 'integer',
    'priority' => 'integer',
    'status' => 'string',
    'common_category_id' => 'integer',
    'response_time' => 'datetime',
    'first_response_at' => 'datetime',
    'resolved_at' => 'datetime',
    'sla_resolution_due_at' => 'datetime',
    'sla_response_breached' => 'boolean',
    'sla_resolution_breached' => 'boolean',
    'reopened_count' => 'integer',
    'is_first_contact_resolution' => 'boolean',
    'object_id' => 'integer',
    'object_type' => 'string',
    'responsible_user_id' => 'integer',
    'time_spent' => 'integer',
    'watcher_user_ids' => 'array:integer',
    'watcher_account_ids' => 'array:integer',
    'support_seeker_account_id' => 'integer',
    'fullname' => 'string',
    'email' => 'string',
    'phone_number' => 'string',
    'pronoun' => 'string',
    'name' => 'string',
    'iam_account_type_id' => 'integer',
    'support_seeker_name' => 'string',
    'responsible_name' => 'string',
    'category_name' => 'string',
    'csat_score' => 'integer',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'deleted_at' => 'datetime',
    ];

    /**
     We are casting data fields.
     *
     @var array
     */
    protected $dates = [
    'response_time',
    'first_response_at',
    'resolved_at',
    'sla_resolution_due_at',
    'created_at',
    'updated_at',
    'deleted_at',
    ];

    /**
     @var array
     */
    protected $with = [

    ];

    /**
     @var int
     */
    protected $perPage = 20;

    /**
     @return void
     */
    public static function boot()
    {
        parent::boot();

        //  We create and add Observer even if we wont use it.
        parent::observe(TicketsPerspectiveObserver::class);

        self::registerScopes();
    }

    public static function registerScopes()
    {
        $globalScopes = config('support.scopes.global');
        $modelScopes = config('support.scopes.support_tickets_perspective');

        if(!$modelScopes) { $modelScopes = [];
        }
        if (!$globalScopes) { $globalScopes = [];
        }

        $scopes = array_merge(
            $globalScopes,
            $modelScopes
        );

        if($scopes) {
            foreach ($scopes as $scope) {
                static::addGlobalScope(app($scope));
            }
        }
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
