<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    // RELATIONSHIPS ==========================================================================================

    /**
     * A service has many packages
     * @return $this
     */
    public function packages()
    {
        return $this->hasMany('App\Models\Package');
    }

    /**
     * A service has many projects
     * @return $this
     */
    public function projects()
    {
        return $this->hasMany('App\Models\Project');
    }

    /**
     * A service has an image
     * @return $this
     */
    public function image()
    {
        return $this->hasOne('App\Models\Image');
    }

    /**
     * Belongs to many users who are clients
     * @return $this
     */
    public function clients()
    {
        return $this->belongsToMany('App\Models\Client');
    }

    /**
     * Get all of the tutorials for the service.
     */
    public function tutorials()
    {
        return $this->morphToMany('App\Models\Tutorial', 'tutorialable');
    }

    /**
     * Belongs to many questions
     * @return $this
     */
    public function questions()
    {
        return $this->morphToMany('App\Models\Question', 'questionable');

    }

    // SCOPES ================================================================================================

    // API ===================================================================================================
}
