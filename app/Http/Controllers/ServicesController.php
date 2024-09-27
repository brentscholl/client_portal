<?php

    namespace App\Http\Controllers;

    use App\Models\Package;
    use App\Models\Service;
    use Illuminate\Database\Eloquent\Collection;

    class ServicesController extends Controller
    {
        /**
         * Show all services
         * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
         */
        public function adminIndex() {
            // Get all services
            $services = Service::with([
                'projects:id,service_id',
                'clients:id',
                'packages:id,service_id',
            ])->get();

            return view('admin.services.index', compact('services'));
        }

        /**
         * Show single service
         * @param $id
         * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
         */
        public function adminShow($id) {
            // Get the service
            $service = Service::with(['projects', 'clients'])->with(['packages' => function($p){
                $p->with(['questions:id']);
            }])->find($id);

            if(!$service){
                flash('Service does not exist')->error();
                return redirect()->route('admin.services.index');
            }

            return view('admin.services.show', compact('service'));
        }

        /**
         * Show single package
         * @param $id
         * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
         */
        public function adminPackageShow($id) {
            // Get the package
            $package = Package::with(['questions'])->find($id);

            return view('admin.services.show-package', compact('package'));
        }

        /**
         * Show all services
         * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
         */
        public function clientIndex() {
            // Get all services
            $services = Service::with([
                'projects:id,service_id',
                'clients:id',
                'packages:id,service_id',
            ])->get();

            return view('client.services.index', compact('services'));
        }
        /**
         * Show single service
         * @param $id
         * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
         */
        public function clientShow($id) {
            // Get the service
            $service = Service::with(['projects', 'clients'])->with(['packages' => function($p){
                $p->with(['questions:id']);
            }])->find($id);

            if(!$service){
                flash('Service does not exist')->error();
                return redirect()->route('client.services.index');
            }

            return view('client.services.show', compact('service'));
        }
    }
