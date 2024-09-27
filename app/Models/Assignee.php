<?php

namespace App\Models;

use App\Traits\BelongsToClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignee extends Model
{
    use HasFactory, BelongsToClient;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $with = ['user'];

    // RELATIONSHIPS =========================================================================================

    /**
     * Get the user that owns the assignment.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function assigneeable() {
        return $this->morphTo();
    }

    // SCOPES ================================================================================================

    // API ===================================================================================================
}
