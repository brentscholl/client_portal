<?php

    namespace App\Traits;

    trait Impersonateable
    {
        /**
         * Impersonate a user by logging in as them
         * @param $userId
         * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
         */
        public function impersonate($userId) {
            // Make sure this is an admin
            if ( ! is_null(auth()->user()->client_id) ) {
                return;
            }
            // Remember who you were
            $originalId = auth()->user()->id;
            session()->put('impersonate', $originalId);

            // Login as user
            auth()->loginUsingId($userId);

            // Redirect to dashboard
            return redirect('/');
        }
    }
