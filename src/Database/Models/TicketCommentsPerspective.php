<?php

namespace NextDeveloper\Support\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use NextDeveloper\Commons\Common\Cache\Traits\CleanCache;
use NextDeveloper\Commons\Database\Traits\Filterable;
use NextDeveloper\Commons\Database\Traits\HasStates;
use NextDeveloper\Commons\Database\Traits\Taggable;
use NextDeveloper\Commons\Database\Traits\UuidId;
use NextDeveloper\Support\Database\Observers\TicketCommentsPerspectiveObserver;
use Illuminate\Notifications\Notifiable;
use NextDeveloper\Commons\Database\Traits\RunAsAdministrator;

/**
 * TicketCommentsPerspective model.
 *
 * @package  NextDeveloper\Support\Database\Models
 * @property integer $id
 * @property string $uuid
 * @property string $comment
 * @property integer $iam_account_id
 * @property integer $iam_user_id
 * @property string $fullname
 * @property string $email
 * @property string $phone_number
 * @property string $pronoun
 * @property string $name
 * @property integer $iam_account_type_id
 * @property integer $support_ticket_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 */
class TicketCommentsPerspective extends Model
{
    use Filterable, UuidId, CleanCache, Taggable, HasStates, RunAsAdministrator;
    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'support_ticket_comments_perspective';


    /**
     @var array
     */
    protected $guarded = [];

    protected $fillable = [
            'comment',
            'iam_account_id',
            'iam_user_id',
            'fullname',
            'email',
            'phone_number',
            'pronoun',
            'name',
            'iam_account_type_id',
            'support_ticket_id',
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
    'comment' => 'string',
    'fullname' => 'string',
    'email' => 'string',
    'phone_number' => 'string',
    'pronoun' => 'string',
    'name' => 'string',
    'iam_account_type_id' => 'integer',
    'support_ticket_id' => 'integer',
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

        //  We create and add Observer even if we wont use it.
        parent::observe(TicketCommentsPerspectiveObserver::class);

        self::registerScopes();
    }

    public static function registerScopes()
    {
        $globalScopes = config('support.scopes.global');
        $modelScopes = config('support.scopes.support_ticket_comments_perspective');

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
