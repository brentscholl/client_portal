<?php

namespace App\Models;

use App\Traits\BelongsToClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Project extends Model
{
    use HasFactory, BelongsToClient, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'date',
    ];

    public static function boot() {
        parent::boot();
        self::deleting(function($project) { // before delete() method call this
            $project->urls()->each(function($url) {
                $url->delete();
            });
            $project->assignees()->each(function($assignee) {
                $assignee->delete();
            });
            $project->resources()->each(function($resource) {
                $resource->delete();
            });
            $project->phases()->each(function($phase) {
                $phase->delete();
            });
            $project->packages()->detach();
            $project->questions()->each(function($question) {
                $question->delete();
            });
            $project->actions()->each(function($action) {
                $action->delete();
            });
        });
    }


    // RELATIONSHIPS ==========================================================================================

    /**
     * Get all of the project's urls.
     */
    public function urls()
    {
        return $this->morphMany('App\Models\Url', 'urlable');
    }

    /**
     * Get all of the assignees for the Project.
     */
    public function assignees()
    {
        return $this->morphMany('App\Models\Assignee', 'assigneeable');
    }

    /**
     * A project belongs to a client
     *
     * @return $this
     */
    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    /**
     * A project has many phases
     *
     * @return $this
     */
    public function phases()
    {
        return $this->hasMany('App\Models\Phase', 'project_id');
    }

    /**
     * A project has many tasks
     *
     * @return $this
     */
    public function tasks()
    {
        return $this->hasMany('App\Models\Task');
    }

    /**
     * A project belongs to a service
     *
     * @return $this
     */
    public function service()
    {
        return $this->belongsTo('App\Models\Service');
    }

    /**
     * Client belongs to many packages
     *
     * @return $this
     */
    public function packages() {
        return $this->belongsToMany('App\Models\Package');
    }

    /**
     * Has many questions
     * @return $this
     */
    public function questions()
    {
        return $this->morphToMany('App\Models\Question', 'questionable');

    }

    /**
     * Get all of the project's files.
     */
    public function files()
    {
        return $this->morphMany('App\Models\File', 'fileable');
    }

    /**
     * Get the project's actions.
     */
    public function actions()
    {
        return $this->morphMany('App\Models\Action', 'actionable');
    }

    /**
     * Get all of the project's resources.
     */
    public function resources()
    {
        return $this->morphMany('App\Models\Resource', 'resourceable');
    }

    // SCOPES ================================================================================================

    public function scopeAutoloadIndex($query) {
        $query->with([
            'service:id,slug,title',
            'phases:id,project_id,step,status',
            'client:id,title',
            'tasks:id,project_id,status'
        ])->select('id', 'client_id', 'title', 'status', 'service_id', 'due_date', 'visible');
    }

    public function scopeAutoloadShow($query) {
        $query->with([
            //'service:id,slug,title',
            //'client:id,title,avatar',
        ]);
    }

    // API ===================================================================================================

    /**
     * Mark project as pending
     *
     * @return bool
     */
    public function markAsPending() {
        return $this->update(['status' => 'pending']);
    }

    /**
     * Mark project as completed
     *
     * @return bool
     */
    public function markAsCompleted() {
        return $this->update(['status' => 'completed']);
    }

    /**
     * Mark project as in progress
     *
     * @return bool
     */
    public function markAsInProgress() {
        return $this->update(['status' => 'in-progress']);
    }

    /**
     * Mark project as in progress
     *
     * @return bool
     */
    public function markAsOnHold() {
        return $this->update(['status' => 'on-hold']);
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
    public function isInProgress() {
        return $this->statusIs('in-progress');
    }
    public function isOnHold() {
        return $this->statusIs('on-hold');
    }
}
