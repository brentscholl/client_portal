<?php

namespace App\Models;

use App\Traits\BelongsToClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory, BelongsToClient;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    // RELATIONSHIPS ==========================================================================================

    /**
     * Get the parent resourceable model.
     */
    public function resourceable() {
        return $this->morphTo();
    }

    /**
     * Get all the files for the resource.
     */
    public function files()
    {
        return $this->morphToMany('App\Models\File', 'fileable');
    }

    /**
     * Get all of the resource's urls.
     */
    public function urls()
    {
        return $this->morphMany('App\Models\Url', 'urlable');
    }

    // SCOPES ================================================================================================

    // API ===================================================================================================
}
