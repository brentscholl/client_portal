<?php

namespace App\Models;

use App\Traits\BelongsToClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
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
     * An image can belong to a service
     * @return $this
     */
    public function service()
    {
        return $this->belongsTo('App\Models\Service');
    }

    /**
     * An image can belong to a tutorial
     * @return $this
     */
    public function tutorial()
    {
        return $this->belongsTo('App\Models\Tutorial');
    }


    // SCOPES ================================================================================================

    // API ===================================================================================================
}
