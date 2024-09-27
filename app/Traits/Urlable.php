<?php

    namespace App\Traits;

    trait Urlable
    {
        public $attach_url = false;
        public $urls = [ null, ];
        public $url_labels = [ null, ];

        /**
         * Add new url when add new url button is clicked
         */
        public function addNewURL() {
            array_push($this->urls, null);
            array_push($this->url_labels, null);
        }

        /**
         * Remove url when remove button is clicked
         * @param $i
         */
        public function removeUrl($i) {
            unset($this->urls[$i]);
            unset($this->url_labels[$i]);
            // Rebuild Collections
            $newUrlArray = [];
            $newUrlLabelArray = [];
            foreach ( $this->urls as $url){
                array_push($newUrlArray, $url);
            }
            foreach ( $this->url_labels as $url_label){
                array_push($newUrlArray, $url_label);
            }
            $this->urls = $newUrlArray;
            $this->url_labels = $newUrlLabelArray;
        }

        /**
         * Save urls to model
         * @param $model
         */
        public function saveUrls($model) {
            $count = count($this->urls);
            for ($i = 0; $i < $count; $i++) {
                if ( $this->urls[$i] != null ) {
                    $model->urls()->create([
                        'client_id' => $model->client_id,
                        'label' => $this->url_labels[$i] ? $this->url_labels[$i] : '',
                        'url' => $this->urls[$i],
                    ]);
                }
            }
        }
    }
