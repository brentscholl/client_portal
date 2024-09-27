<?php

namespace App\Models;

use App\Traits\BelongsToClient;
use Glorand\Model\Settings\Traits\HasSettingsTable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, BelongsToClient, HasSettingsTable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Eager loads
     * @var array
     */
    // public $with = ['roles'];

    public $defaultSettings = [
        'is_subscribed_to_news' => true,
    ];

    public static function boot() {
        parent::boot();
        self::deleting(function($user) { // before delete() method call this
            $user->assignments()->each(function($assignment){
                $assignment->delete();
            });
            $clients = Client::where('primary_contact', $user->id)->get();
            if($clients) {
                foreach ($clients as $client) {
                    $client->update(['primary_contact', null]);
                }
            }

            $user->teams()->detach();
        });
    }


    // RELATIONSHIPS ==========================================================================================

    /**
     * Users have written many answers
     *
     * @return $this
     */
    public function answers()
    {
        return $this->hasMany('App\Models\Answer');
    }

    /**
     * Users have written many actions
     *
     * @return $this
     */
    public function actions()
    {
        return $this->hasMany('App\Models\Action');
    }

    /**
     * Users have given many reactions
     *
     * @return $this
     */
    public function reactions()
    {
        return $this->hasMany('App\Models\Reaction');
    }

    /**
     * @return $this
     */
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role');
    }

    /**
     * Get the assignees for the user.
     */
    public function assignments()
    {
        return $this->hasMany('App\Models\Assignee', 'user_id');
    }

    /**
     * A user who is a client
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function client() {
        return $this->belongsTo('App\Models\Client');
    }

    /**
     * Get all of the teams for the user.
     */
    public function teams()
    {
        return $this->morphToMany('App\Models\Team', 'teamable');
    }

    /**
     * A user has many email tempaltes
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function emailTemplates()
    {
        return $this->hasMany('App\Models\EmailTemplate');
    }

    // SCOPES ================================================================================================

    public function scopeAdmin($query) {
        return $query->where('client_id', null);
    }

    public function scopeAutoloadShow($query) {
        $query->with([
            'teams',
            'client',
        ]);
    }

    // API ===================================================================================================

    /**
     * Get the users gravitar image based off email
     * todo: Provide a default image. Add `?d=https%3A%2F%2Fexample.com%2Fimages%2Favatar.jpg` to the end of the url
     * @return string
     */
    public function avatarUrl()
    {
        return $this->avatar
            ? Storage::disk('avatars')->url($this->avatar)
            : 'https://www.gravatar.com/avatar/'.md5(strtolower(trim($this->email)));
    }

    public static function search($query)
    {
        return empty($query) ? static::query()
            : static::where('first_name', 'like', '%'.$query.'%')
                ->orWhere('last_name', 'like', '%'.$query.'%')
                ->orWhere('email', 'like', '%'.$query.'%');
    }

    /**
     * Checks if user is subscribed to a role
     *
     * @param $name
     * @return bool
     */
    public function hasRole($name)
    {
        foreach ($this->roles as $role) {
            if ($role->name == $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if user is an admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return is_null($this->client_id);
    }

    /**
     * Assign a role to a user
     *
     * @param $role
     * @return mixed
     */
    public function assignRole($role)
    {
        return $this->roles()->attach($role);
    }

    public function getFullNameAttribute() {
        $deleted_tag = $this->trashed() ? ' (deleted)' : '';
        return $this->attributes['first_name'] . ' ' . $this->attributes['last_name'] . $deleted_tag;
    }

    /**
     * Assign a user to client
     *
     * @param $client_id
     * @return mixed
     */
    public function assignToClient($client_id) {
        return $this->update(['client_id' => $client_id]);
    }

    /**
     * Unassign a user to client
     *
     * @param $client_id
     * @return mixed
     */
    public function unassignFromClient($client_id) {
        return $this->update(['client_id' => $client_id]);
    }
}
