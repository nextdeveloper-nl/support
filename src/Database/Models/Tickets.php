<?php

namespace NextDeveloper\Support\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use NextDeveloper\Commons\Common\Cache\Traits\CleanCache;
use NextDeveloper\Commons\Database\Traits\Filterable;
use NextDeveloper\Commons\Database\Traits\HasStates;
use NextDeveloper\Commons\Database\Traits\Taggable;
use NextDeveloper\Commons\Database\Traits\UuidId;
use NextDeveloper\Support\Database\Observers\TicketsObserver;
use Illuminate\Notifications\Notifiable;
use NextDeveloper\Commons\Database\Traits\RunAsAdministrator;

/**
 * Tickets model.
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
 * @property integer $level
 * @property integer $priority
 * @property \Carbon\Carbon $response_time
 * @property integer $object_id
 * @property string $object_type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property boolean $is_public
 * @property integer $responsible_user_id
 * @property integer $time_spent
 * @property array $watcher_user_ids
 * @property array $watcher_account_ids
 */
class Tickets extends Model
{
    use Filterable, UuidId, CleanCache, Taggable, HasStates, RunAsAdministrator;
    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'support_tickets';


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
            'level',
            'priority',
            'response_time',
            'object_id',
            'object_type',
            'is_public',
            'responsible_user_id',
            'time_spent',
            'watcher_user_ids',
            'watcher_account_ids',
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
    'level' => 'integer',
    'priority' => 'integer',
    'response_time' => 'datetime',
    'object_id' => 'integer',
    'object_type' => 'string',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'deleted_at' => 'datetime',
    'is_public' => 'boolean',
    'responsible_user_id' => 'integer',
    'time_spent' => 'integer',
    'watcher_user_ids' => 'array:integer',
    'watcher_account_ids' => 'array:integer',
    ];

    /**
     We are casting data fields.
     *
     @var array
     */
    protected $dates = [
    'response_time',
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
        parent::observe(TicketsObserver::class);

        self::registerScopes();
    }

    public static function registerScopes()
    {
        $globalScopes = config('support.scopes.global');
        $modelScopes = config('support.scopes.support_tickets');

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

    public function accounts() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\NextDeveloper\IAM\Database\Models\Accounts::class);
    }
    
    public function users() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\NextDeveloper\IAM\Database\Models\Users::class);
    }
    
    public function tests() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\NextDeveloper\Support\Database\Models\Tests::class);
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE






}
