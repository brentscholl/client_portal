<?php

namespace App\Models;

use App\Traits\BelongsToClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Team extends Model
{
    use HasFactory, BelongsToClient, HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public static function boot() {
        parent::boot();
        self::deleting(function($team) { // before delete() method call this
            $team->users()->detach();
            $team->questions()->detach();
            $team->tasks()->detach();
        });
    }

    // RELATIONSHIPS =========================================================================================

    /**
     * Get all of the users that are assigned this team.
     */
    public function users()
    {
        return $this->morphedByMany('App\Models\User', 'teamable')->orderBy('first_name');
    }

    /**
     * Get all of the questions that are assigned this team.
     */
    public function questions()
    {
        return $this->morphedByMany('App\Models\Question', 'teamable');
    }

    /**
     * Get all of the tasks that are assigned this team.
     */
    public function tasks()
    {
        return $this->morphedByMany('App\Models\Task', 'teamable');
    }

    // SCOPES ================================================================================================

    // API ===================================================================================================
    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions {
        return SlugOptions::create()
            ->generateSlugsFrom(['title'])
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate()
            ->slugsShouldBeNoLongerThan(50);
    }
}
