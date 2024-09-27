<?php

namespace App\Models;

use App\Traits\BelongsToClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory, BelongsToClient;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $dates = ['sent_date'];

    // RELATIONSHIPS =========================================================================================
    /**
     * An email belongs to an email template
     * @return $this
     */
    public function emailTemplate()
    {
        return $this->belongsTo('App\Models\EmailTemplate');
    }

    // SCOPES ================================================================================================

    // API ===================================================================================================
}
