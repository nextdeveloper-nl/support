<?php

namespace NextDeveloper\Support\Database\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use NextDeveloper\Commons\Database\Traits\HasStates;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use NextDeveloper\Commons\Database\Traits\Filterable;
use NextDeveloper\Support\Database\Observers\SlaPoliciesObserver;
use NextDeveloper\Commons\Database\Traits\UuidId;
use NextDeveloper\Commons\Database\Traits\HasObject;
use NextDeveloper\Commons\Common\Cache\Traits\CleanCache;
use NextDeveloper\Commons\Database\Traits\Taggable;
use NextDeveloper\Commons\Database\Traits\RunAsAdministrator;

/**
 * SlaPolicies model.
 *
 * @package  NextDeveloper\Support\Database\Models
 * @property integer $id
 * @property string $uuid
 * @property string $name
 * @property integer $priority
 * @property integer $response_target_minutes
 * @property integer $resolution_target_minutes
 * @property boolean $is_active
 * @property integer $iam_account_id
 * @property integer $iam_user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 */
class SlaPolicies extends Model
{
    use Filterable, UuidId, CleanCache, Taggable, HasStates, RunAsAdministrator, HasObject;
    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'support_sla_policies';


    /**
     @var array
     */
    protected $guarded = [];

    protected $fillable = [
            'name',
            'priority',
            'response_target_minutes',
            'resolution_target_minutes',
            'is_active',
            'iam_account_id',
            'iam_user_id',
            'kind',
            'conditions',
            'clock',
            'work_days',
            'work_start',
            'work_end',
            'due_soon_percent',
            'escalation',
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
    'name' => 'string',
    'priority' => 'integer',
    'response_target_minutes' => 'integer',
    'resolution_target_minutes' => 'integer',
    'is_active' => 'boolean',
    'kind' => 'string',
    'conditions' => 'array',
    'clock' => 'string',
    'work_days' => \NextDeveloper\Commons\Database\Casts\IntegerArray::class,
    'work_start' => 'string',
    'work_end' => 'string',
    'due_soon_percent' => 'integer',
    'escalation' => 'array',
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

        self::registerScopes();
    }

    /**
     * Registers the observer once the model has finished booting.
     *
     * Registering it inside boot() instantiates the model while it is still booting,
     * which Laravel 12+ rejects with a LogicException.
     *
     * @return void
     */
    protected static function booted()
    {
        parent::booted();

        //  We create and add Observer even if we wont use it.
        static::observe(SlaPoliciesObserver::class);
    }

    public static function registerScopes()
    {
        $globalScopes = config('support.scopes.global');
        $modelScopes = config('support.scopes.support_sla_policies');

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
