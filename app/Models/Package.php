<?php

namespace App\Models;

use App\Traits\BelongsToClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory, BelongsToClient;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public static function boot() {
        parent::boot();
        self::deleting(function($package) { // before delete() method call this
            $package->questions()->detach();
            $package->tutorials()->detach();
        });
    }

    // RELATIONSHIPS =========================================================================================

    /**
     * Has many questions
     * @return $this
     */
    public function questions()
    {
        return $this->morphToMany('App\Models\Question', 'questionable');

    }

    /**
     * Has many tutorials
     * @return $this
     */
    public function tutorials()
    {
        return $this->morphToMany('App\Models\Tutorial', 'tutorialable');

    }

    /**
     * Belongs to a service
     * @return $this
     */
    public function service()
    {
        return $this->belongsTo('App\Models\Service');

    }

    /**
     * Belongs to many projects
     * @return $this
     */
    public function projects()
    {
        return $this->belongsToMany('App\Models\Project');
    }

    // SCOPES ================================================================================================

    // API ===================================================================================================
}
