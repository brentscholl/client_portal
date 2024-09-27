<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutorial extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
        'completed_at' => 'date',
    ];

    public static function boot() {
        parent::boot();
        self::deleting(function($tutorial) { // before delete() method call this
            $tutorial->clients()->detach();
            $tutorial->services()->detach();
            $tutorial->packages()->detach();
        });
    }

    // RELATIONSHIPS ==========================================================================================

    /**
     * Get all of the clients that are assigned this tutorial.
     */
    public function clients()
    {
        return $this->morphedByMany('App\Models\Client', 'tutorialable');
    }

    /**
     * Get all of the services that are assigned this tutorial.
     */
    public function services()
    {
        return $this->morphedByMany('App\Models\Service', 'tutorialable');
    }

    /**
     * Get all of the packages that are assigned this question.
     */
    public function packages()
    {
        return $this->morphedByMany('App\Models\Package', 'tutorialable');
    }

    // SCOPES ================================================================================================

    public function scopeAutoloadIndex($query) {
        $query->with([
            'services:id,slug,title',
            'clients:id,title',
        ])->with(['packages' => function ($p){
            $p->with(['service:id,title,slug']);
        }]);
    }

    public function scopeAutoloadShow($query) {
        $query->with([
            'services:id,slug,title',
            'clients:id,title',
        ]);
    }

    // API ===================================================================================================

    /**
     * Attach a tutorial to a service
     *
     * @param $service_id
     * @return mixed
     */
    public function attachToService($service_id) {
        return $this->services()->attach($service_id);
    }
    /**
     * Detach a tutorial to a service
     *
     * @param $service_id
     * @return mixed
     */
    public function detachFromService($service_id) {
        return $this->services()->detach($service_id);
    }

    /**
     * Attach a tutorial to a client
     *
     * @param $client_id
     * @return mixed
     */
    public function attachToClient($client_id) {
        return $this->clients()->attach($client_id);
    }
    /**
     * Detach a tutorial to a client
     *
     * @param $client_id
     * @return mixed
     */
    public function detachFromClient($client_id) {
        return $this->clients()->detach($client_id);
    }
}
