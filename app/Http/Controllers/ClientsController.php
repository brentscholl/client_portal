<?php

    namespace App\Http\Controllers;

    use App\Models\Client;
    use App\Models\EmailTemplate;
    use App\Models\Project;
    use App\Models\Task;
    use App\Models\User;

    class ClientsController extends Controller
    {
        /**
         * Show all Clients
         * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
         */
        public function adminIndex() {
            return view('admin.clients.index');
        }

        /**
         * Show single client
         * @param $id
         * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
         */
        public function adminShow($id) {
            // Get the client
            $client = Client::autoloadShow()->find($id);

            if(!$client){
                flash('Client does not exist')->error();
                return redirect()->route('admin.clients.index');
            }

            return view('admin.clients.show', compact('client'));
        }

        public function adminShowEmailTemplate($id){
            $email_template = EmailTemplate::find($id);
            return redirect()->route('admin.clients.show', $email_template->client_id);
        }
    }
