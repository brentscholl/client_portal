<?php

    namespace App\Http\Livewire\Admin\Activities;

    use App\Models\Action;
    use App\Models\Notification;
    use App\Models\Reaction;
    use App\Models\User;
    use App\Notifications\Mentioned;
    use App\Notifications\UserReservationConfirmed;
    use App\Traits\HasMenu;
    use DB;
    use Livewire\Component;

    class Comment extends Component
    {
        use HasMenu;

        public Action $action;

        public $comment;

        public $reactions;

        public $auth_reaction_types;

        public $mentionableUsers;

        public $model;

        public $show_reply = false;

        public $update;

        public $listeners = [
          'reactionsCreated' => '$refresh',
          'reactionsDeleted' => '$refresh',
        ];

        public function mount() {
            $this->comment = $this->action->body;
            $this->reactions = Reaction::where('action_id', $this->action->id)->get()->groupBy('type')->toBase();
            $this->auth_reaction_types = Reaction::where('action_id', $this->action->id)->where('user_id', auth()->user()->id)->pluck('type')->toArray();
        }

        public function rules() {
            return [
                'comment' => 'required|max:2000',
            ];
        }

        protected $messages = [
            'comment.require' => 'Please type a comment.',
        ];

        public function refreshComponent() {
            $this->update = !$this->update;
        }

        public function saveComment() {
            $this->validate();

            DB::beginTransaction();

            $this->action->update([
                'body' => $this->comment,
            ]);

            preg_match_all('/data-userid=\"(.*?)\"/', $this->comment, $matches);
            $mentioned_user_ids = [];
            foreach ( $matches[1] as $mentionId ) {
                $user = User::find($mentionId);
                array_push($mentioned_user_ids, $user->id);
                if ( in_array($user->id, json_decode($this->action->mention_ids)) ) {
                    // Dont send user a notification for being mentioned in the comment if they already were mentioned.
                    $action_notifications = Notification::where('data', 'like', '%"comment":{"id":' . $this->action->id . '%')->get()->first();
                    if(! $action_notifications){
                        $user->notify(new Mentioned(auth()->user(), $this->action, $this->model));
                    }
                }
            }

            $this->action->update([
                'mention_ids' => json_encode($mentioned_user_ids),
            ]);

            DB::commit();

            $this->emit('actionUpdated');
            flash('Comment Updated')->success()->livewire($this);
        }

        public function removeComment() {
            if($this->action->user_id != auth()->user()->id){
                flash('You cannot delete this comment.')->error()->livewire($this);
            }else {
                $this->action->delete();
                $this->emit('actionDeleted');
                flash('Comment Deleted')->success()->livewire($this);
            }
        }

        /**
         * todo: add validation
         *
         * @param $reaction
         */
        public function react($reaction) {
            DB::beginTransaction();

            Reaction::create([
                'action_id' => $this->action->id,
                'user_id'   => auth()->user()->id,
                'type'      => $reaction,
            ]);

            DB::commit();

            $this->mount();
            $this->refreshComponent();
        }

        public function unreact($reaction) {
            $r = Reaction::where('user_id', auth()->user()->id)->where('action_id', $this->action->id)->where('type', $reaction)->get()->first();
            $r->delete();
            $this->mount();
            $this->emit('reactionsDeleted');
        }

        /**
         * Override needed to add mount method.
         * for some reason the component returns an array insted of object
         * if you do not mount.
         *
         */
        public function showMenuContent() {
            $this->showMenuContent = true;
            $this->mount();
        }

        public function render() {
            return view('livewire.admin.activities.comment');
        }
    }
