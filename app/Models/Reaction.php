<?php

namespace App\Models;

use App\Traits\BelongsToClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    use HasFactory, BelongsToClient;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    // RELATIONSHIPS =========================================================================================

    /**
     * Get the activity that owns the action.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the activity that owns the action.
     */
    public function action()
    {
        return $this->belongsTo('App\Models\Action');
    }

    // SCOPES ================================================================================================

    // API ===================================================================================================
}
