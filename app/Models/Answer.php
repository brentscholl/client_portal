<?php

namespace App\Models;

use App\Traits\BelongsToClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
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
        self::deleting(function($answer) { // before delete() method call this
            $answer->files()->each(function($file){
                $file->delete();
            });
        });
    }

    // RELATIONSHIPS =========================================================================================

    /**
     * An answer belongs to a quesrtion
     * @return $this
     */
    public function question()
    {
        return $this->belongsTo('App\Models\Question');
    }

    /**
     * An answer was written by a User
     * @return $this
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }
    /**
     * Belongs to many questions
     * @return $this
     */
    public function questions()
    {
        return $this->morphedByMany('App\Models\Resource', 'fileable');

    }

    /**
     * Get all of the answer's files.
     */
    public function files()
    {
        return $this->morphToMany('App\Models\File', 'fileable');
    }

    // SCOPES ================================================================================================

    // API ===================================================================================================
}
