<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phase extends Model
{
    use HasFactory;

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
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('step', 'asc');
        });
        self::deleting(function($phase) { // before delete() method call this
            $phase->urls()->each(function($url) {
                $url->delete();
            });
            $phase->tasks()->each(function($task) {
                $task->delete();
            });
            $phase->assignees()->each(function($assignee){
                $assignee->delete();
            });
            $phase->actions()->each(function($action) {
                $action->delete();
            });
        });
        self::deleted(function($phase) { // After delete() method is called do this...
            // Reorder phase steps
            $phases = Phase::where('project_id', $phase->project_id)->orderBy('step', 'asc')->get();
            if ( $phases ) {
                $count = 1;
                foreach ( $phases as $p ) {
                    $p->update(['step' => $count]);
                    $count++;
                }
            }
        });
    }


    // RELATIONSHIPS ==========================================================================================

    /**
     * Get all of the phase's urls.
     */
    public function urls()
    {
        return $this->morphMany('App\Models\Url', 'urlable');
    }

    /**
     * A Phase has many tasks
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany('App\Models\Task');
    }

    /**
     * A phase belongs to a project
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }

    /**
     * Get all of the assignees for the phase.
     */
    public function assignees()
    {
        return $this->morphMany('App\Models\Assignee', 'assigneeable');
    }

    /**
     * Get the phase's actions.
     */
    public function actions()
    {
        return $this->morphMany('App\Models\Action', 'actionable');
    }


    // SCOPES ================================================================================================

    public function scopeAutoloadShow($query) {
        $query->with([
            //'project:id,client_id,service_id,title',
            //'project.service:id,slug,title',
            //'project.client:id,title,avatar',
            //'tasks:id,client_id,project_id,phase_id,status',
            //'urls'
        ]);
    }



    // API ===================================================================================================
}
