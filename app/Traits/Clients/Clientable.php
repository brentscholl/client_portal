<?php

    namespace App\Traits\Clients;

    use Illuminate\Support\Facades\Storage;

    trait Clientable
    {
        public $client;

        public $title;

        public $website_url;

        public $monthly_budget;

        public $annual_budget;

        public $upload;

        public $newAvatar;

        public $button_type = 'secondary';

        /**
         * Remove the avatar from the client
         */
        public function removeAvatar() {
            Storage::disk('avatars')->delete($this->client->avatar);
            $this->client->update([
                'avatar' => null,
            ]);
        }
    }
