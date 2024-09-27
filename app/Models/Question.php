<?php

namespace App\Models;

use App\Traits\BelongsToClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];



    // RELATIONSHIPS =========================================================================================

    /**
     * Questions have may answers
     * @return $this
     */
    public function answers()
    {
        return $this->hasMany('App\Models\Answer', 'question_id');
    }

    /**
     * Get all of the services that are assigned this question.
     */
    public function services()
    {
        return $this->morphedByMany('App\Models\Service', 'questionable');
    }

    /**
     * Get all of the packages that are assigned this question.
     */
    public function packages()
    {
        return $this->morphedByMany('App\Models\Package', 'questionable');
    }

    /**
     * Get all of the clients that are assigned this question.
     */
    public function clients()
    {
        return $this->morphedByMany('App\Models\Client', 'questionable');
    }

    /**
     * Get all of the projects that are assigned this question.
     */
    public function projects()
    {
        return $this->morphedByMany('App\Models\Project', 'questionable');
    }

    /**
     * Get all of the teams for the question.
     */
    public function teams()
    {
        return $this->morphToMany('App\Models\Team', 'teamable');
    }

    /**
     * Belongs to many files
     * @return $this
     */
    public function files()
    {
        return $this->morphToMany('App\Models\File', 'fileable');

    }

    // SCOPES ================================================================================================
    public function scopeAutoloadIndex($query) {
        $query->with([
            'services:id,slug,title',
            'clients:id,title',
            'teams:id,title',
        ])->with(['packages' => function ($p){
            $p->with(['service:id,title,slug']);
        }]);
    }
    public function scopeAutoloadShow($query) {
        $query->with([
            //'services:id,slug,title',
            //'clients:id,title',
            //'packages:id,title,description,service_id',
            //'answers',
        ]);
    }

    // API ===================================================================================================

    /**
     * Attach to a service
     *
     * @param $service_id
     * @return mixed
     */
    public function attachToService($service_id) {
        return $this->services()->attach($service_id);
    }

    /**
     * Detach from a service
     *
     * @param $service_id
     * @return mixed
     */
    public function detachFromService($service_id) {
        return $this->services()->detach($service_id);
    }
}
