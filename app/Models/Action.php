<?php

namespace App\Models;

use App\Traits\BelongsToClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use HasFactory, BelongsToClient;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public static function boot() {
        parent::boot();
        self::deleting(function($action) { // before delete() method call this
            $action->reactions()->each(function($reaction) {
                $reaction->delete();
            });
        });
    }

    // RELATIONSHIPS =========================================================================================
    /**
     * Get the activity that owns the action.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     *
     * Actions have many reactions
     *
     * @return $this
     */
    public function reactions()
    {
        return $this->hasMany('App\Models\Reaction');
    }

    /**
     * Get the parent fileable model.
     */
    public function actionable() {
        return $this->morphTo();
    }

    // SCOPES ================================================================================================

    // API ===================================================================================================
}
