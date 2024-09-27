<?php

    namespace App\Traits;


    trait BelongsToClient
    {
        /**
         * The "booted" method of the model.
         *
         * @return void
         */
        protected static function bootBelongsToClient()
        {

            static::creating(function($model) {
               if(session()->has('client_id')) {
                   $model->client_id = session()->get('client_id');
               }
            });
        }

        public function client() {
            return $this->belongsTo('App\Models\Client');
        }
    }
