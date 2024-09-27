<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\Storage;
    use Laravel\Scout\Searchable;

    class Client extends Model
    {
        use HasFactory, Searchable;

        /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
        protected $guarded = [];

        public static function boot() {
            parent::boot();
            self::deleting(function($client) { // before delete() method call this
                if($client->avatar){
                    Storage::disk('avatars')->delete($client->avatar);
                }
                $client->questions()->detach();
                $client->answers()->each(function($answer){
                    $answer->delete();
                });
                $client->projects()->each(function($projects) {
                    $projects->delete();
                });
                $client->assignees()->each(function($assignee){
                    $assignee->delete();
                });
                $client->services()->detach();
                $client->tutorials()->detach();
                $client->resources()->each(function($resource){
                    $resource->delete();
                });
                $client->actions()->each(function($action) {
                    $action->delete();
                });
            });
        }

        // RELATIONSHIPS ==========================================================================================

        /**
         * The user who is the primary owner and contact for this client
         *
         * @return $this
         */
        public function primaryContact() {
            return $this->belongsTo('App\Models\User', 'primary_contact');
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
         * Client have many Answers
         *
         * @return $this
         */
        public function answers() {
            return $this->hasMany('App\Models\Answer');
        }

        /**
         * Client has one analytic
         *
         * @return $this
         */
        public function analytic() {
            return $this->hasMany('App\Models\ClientAnalytic');
        }

        /**
         * Client has many projects
         *
         * @return $this
         */
        public function projects() {
            return $this->hasMany('App\Models\Project');
        }

        /**
         * Client has many packages through projects
         *
         * @return $this
         */
        public function packages() {
            return $this->hasManyThrough('App\Models\Package', 'App\Models\Project');
        }


        /**
         * Client belongs to many services
         *
         * @return $this
         */
        public function tasks() {
            return $this->hasMany('App\Models\Task');
        }

        /**
         * Client Belongs to many users
         *
         * @return $this
         */
        public function users() {
            return $this->hasMany('App\Models\User');
        }

        /**
         * Get all of the assignees for the client.
         */
        public function assignees() {
            return $this->morphMany('App\Models\Assignee', 'assigneeable');
        }

        /**
         * Client belongs to many services
         *
         * @return $this
         */
        public function services() {
            return $this->belongsToMany('App\Models\Service');
        }

        /**
         * Get all of the tutorials for the client.
         */
        public function tutorials()
        {
            return $this->morphToMany('App\Models\Tutorial', 'tutorialable');
        }

        /**
         * Get all of the project's files.
         */
        public function files()
        {
            return $this->morphMany('App\Models\File', 'fileable');
        }

        /**
         * Get the client's actions.
         */
        public function actions()
        {
            return $this->morphMany('App\Models\Action', 'actionable');
        }

        /**
         * Get all of the client's resources.
         */
        public function resources()
        {
            return $this->morphMany('App\Models\Resource', 'resourceable');
        }

        // SCOPES ================================================================================================

        public function scopeAutoloadIndex($query) {
            $query->with([
                'primaryContact:id,first_name,last_name,email,phone,position'
            ]);
        }

        public function scopeAutoloadShow($query) {
            $query->with([
                // 'assignees',
                // 'assignees.user:id,first_name,last_name,avatar,position',
                // 'users:id,first_name,last_name,avatar,email,phone,position',
                // 'questions',
                // 'tutorials',
                'projects.phases',
                // 'services'
                'projects.packages'
            ]);
        }

        // API ===================================================================================================

        /**
         * Get the users gravitar image based off email
         * todo: Provide a default image. Add `?d=https%3A%2F%2Fexample.com%2Fimages%2Favatar.jpg` to the end of the url
         *
         * @return string
         */
        public function avatarUrl() {
            return $this->avatar
                ? Storage::disk('avatars')->url($this->avatar)
                : asset('/assets/STEALTH-logo-white.svg');
        }

        /**
         * Does the client have an avatar?
         *
         * @return bool
         */
        public function hasAvatar() {
            return $this->avatar ? true : false;
        }

        /**
         * Detach a service to a client
         *
         * @param $service_id
         * @return mixed
         */
        public function detachService($service_id) {
            return $this->services->detach($service_id);
        }

        /**
         * Set Primary Contact
         *
         * @param $user_id
         * @return mixed
         */
        public function setPrimaryContact($user_id) {
            return $this->update(['primary_contact' => $user_id]);
        }

        /**
         * Remove Primary Contact
         *
         * @param $user_id
         * @return mixed
         */
        public function removePrimaryContact($user_id) {
            return $this->update(['primary_contact' => null]);
        }

        /**
         * Archive Client
         *
         * @return bool
         */
        public function archive() {
            return $this->update(['archive' => '1']);
        }

        /**
         * Unarchive Client
         *
         * @return bool
         */
        public function unarchive() {
            return $this->update(['archive' => '0']);
        }

        // SEARCH ==========================================================================================================

        /**
         * More info: https://www.algolia.com/doc/framework-integration/laravel/indexing/configure-searchable-data/?client=php
         * @return mixed
         */
        public function toSearchableArray() {
            $array = $this->toArray();

            // Applies Scout Extended default transformations:
            $array = $this->transform($array);

            $array['id'] = $this->id;
            $array['title'] = $this->title;
            $array['avatar'] = $this->avatar;

            return $array;
        }

        public function searchableAs() {
            return config('scout.prefix') . 'clients';
        }
    }
