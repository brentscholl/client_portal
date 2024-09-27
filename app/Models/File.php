<?php

    namespace App\Models;

    use App\Traits\BelongsToClient;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\Storage;

    class File extends Model
    {
        use HasFactory, BelongsToClient;

        public $image_types = ['jpg', 'png', 'gif', 'webp', 'tiff', 'psd', 'raw', 'bmp', 'svg'];
        public $video_types = ['webm', 'mpg', 'mp2', 'mpeg', 'mpe', 'mpv', 'ogg', 'mp4', 'm4p', 'm4v', 'avi', 'wmv', 'mov'];
        public $audio_types = ['m4a', 'flac', 'mp3', 'wav', 'wma', 'aac'];

        /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
        protected $guarded = [];

        public static function boot() {
            parent::boot();
            self::deleting(function($file) { // before delete() method call this
                $file->resources()->each(function($resource) {
                    $resource->delete();
                });
                $file->resources()->detach();
                $file->answers()->detach();
                $file->tasks()->detach();
                Storage::disk('files')->delete($file->filename);
            });
        }

        // RELATIONSHIPS ==========================================================================================

        /**
         * The user that uploaded the file
         */
        public function user() {
            return $this->belongsTo('App\Models\User')->withTrashed();
        }

        /*
         * Get all the answers attached to the file.
         */
        public function answers() {
            return $this->morphedByMany('App\Models\Answer', 'fileable');
        }

        /*
         * Get all the resources attached to the file.
         */
        public function resources() {
            return $this->morphedByMany('App\Models\Resource', 'fileable');
        }

        /*
         * Get all the tasks attached to the file.
         */
        public function tasks() {
            return $this->morphedByMany('App\Models\Task', 'fileable');
        }

        // SCOPES ================================================================================================

        public function scopeGetImages($query) {
            return $query->whereIn('extension', $this->image_types);
        }
        public function scopeGetVideos($query) {
            return $query->whereIn('extension', $this->video_types);
        }
        public function scopeGetAudios($query) {
            return $query->whereIn('extension', $this->audio_types);
        }
        public function scopeGetDocuments($query) {
            return $query
                ->whereNotIn('extension', $this->image_types)
                ->whereNotIn('extension', $this->video_types)
                ->whereNotIn('extension', $this->audio_types);
        }

        // API ===================================================================================================
        public function url() {
            return Storage::disk('files')->url($this->file_name);
        }

        public function formatSize() {
            $base = log($this->file_size, 1024);
            $suffixes = ['B', 'KB', 'MB', 'GB', 'TB'];

            return round(pow(1024, $base - floor($base)), 2) . ' ' . $suffixes[floor($base)];
        }

        public function dimensions() {
            list($width, $height) = getimagesize($this->url());
            return $width . ' x ' . $height;
        }

        public function getSimpleFileType() {
            switch ( $this->extension ) {
                case('jpg'):
                case('jpeg'):
                case('png'):
                case('gif'):
                case('tiff'):
                    return 'image';
                    break;

                case('pdf'):
                    return 'pdf';
                    break;

                case('doc'):
                case('docx'):
                    return 'word';
                    break;

                case('csv'):
                    return 'csv';
                    break;

                case('xlsx'):
                case('xls'):
                case('xml'):
                    return 'excel';
                    break;

                case('svg'):
                    return 'svg';
                    break;

                case('sql'):
                case('html'):
                case('php'):
                case('js'):
                case('css'):
                    return 'code';
                    break;

                case('zip'):
                case('rar'):
                case('gzip'):
                    return 'zip';
                    break;

                case('mp3'):
                case('wav'):
                case('aif'):
                case('m4a'):
                    return 'audio';
                    break;

                case('mp4'):
                case('mov'):
                case('wmv'):
                case('avi'):
                case('mkv'):
                case('mpeg-2'):
                    return 'video';
                    break;

                default:
                    return 'file-alt';
            }
        }

    }
