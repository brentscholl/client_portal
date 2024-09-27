<?php

namespace App\Models;

use App\Traits\BelongsToClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailTemplate extends Model
{
    use HasFactory, BelongsToClient, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $dates = ['send_date', 'end_date'];

    // RELATIONSHIPS =========================================================================================
    /**
     * An Email Template has many sent emails
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function emails()
    {
        return $this->hasMany('App\Models\Email');
    }

    /**
     * An email template is created by a User
     * @return $this
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    // SCOPES ================================================================================================

    // API ===================================================================================================
}
