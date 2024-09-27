<?php

namespace App\Models;

use App\Traits\BelongsToClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
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
     * Get the parent urlable model.
     */
    public function urlable()
    {
        return $this->morphTo();
    }

    // SCOPES ================================================================================================

    // API ===================================================================================================
}
