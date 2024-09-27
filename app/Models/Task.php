<?php

namespace App\Models;

use App\Traits\BelongsToClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory, BelongsToClient;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $dates = ['completed_at', 'due_date'];

    public static function boot() {
        parent::boot();
        self::deleting(function($task) { // before delete() method call this
            $task->urls()->each(function($url) {
                $url->delete();
            });
            $task->teams()->detach();
            $task->files()->each(function($file) {
                $file->delete();
            });
            $task->actions()->each(function($action) {
               $action->delete();
            });
        });
    }


    // RELATIONSHIPS ==========================================================================================

    /**
     * Get all of the task's urls.
     */
    public function urls()
    {
        return $this->morphMany('App\Models\Url', 'urlable');
    }

    /**
     * A tasks belongs to a project
     * @return $this
     */
    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    /**
     * A tasks belongs to a project
     * @return $this
     */
    public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }

    /**
     * A tasks belongs to a project
     * @return $this
     */
    public function phase()
    {
        return $this->belongsTo('App\Models\Phase');
    }

    /**
     * A task belongs to a user
     * @return $this
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User');
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
     * Get all of the teams for the task.
     */
    public function teams()
    {
        return $this->morphToMany('App\Models\Team', 'teamable');
    }

    /**
     * Get all of the task's files.
     */
    public function files()
    {
        return $this->morphToMany('App\Models\File', 'fileable');
    }

    /**
     * Get the task's actions.
     */
    public function actions()
    {
        return $this->morphMany('App\Models\Action', 'actionable');
    }

    // SCOPES ================================================================================================

    public function scopeCompleted($query) {
        return $query->where('status', 'completed');
    }

    public function scopePending($query) {
        return $query->where('status', 'pending');
    }

    // API ===================================================================================================

    /**
     * Assign a task to a user
     *
     * @return bool
     */
    public function assignToUser($user_id) {
        return $this->users->attach($user_id);
    }

    /**
     * Assign a task to a project
     *
     * @return bool
     */
    public function assignToProject($project_id) {
        return $this->projects->attach($project_id);
    }

    /**
     * remove a task from a user
     *
     * @return bool
     */
    public function removeFromUser($user_id) {
        return $this->users->dettach($user_id);
    }

    /**
     * Mark task as pending
     *
     * @return bool
     */
    public function markAsPending() {
        return $this->update(['status' => 'pending']);
    }

    /**
     * Mark task as completed
     *
     * @return bool
     */
    public function markAsCompleted() {
        return $this->update(['status' => 'completed']);
    }
    /**
     * Check status
     *
     * @param $status
     * @return bool
     */
    public function statusIs($status) {
        if($this->status == $status){
            return true;
        } else {
            return false;
        }
    }

    public function isPending() {
        return $this->statusIs('pending');
    }
    public function isCompleted() {
        return $this->statusIs('completed');
    }
}
