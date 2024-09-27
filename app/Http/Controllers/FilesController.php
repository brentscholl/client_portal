<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{
    /**
     * Show all Files
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function adminIndex($id = null) {
        $selected_id = $id;
        return view('admin.files.index', compact('selected_id'));
    }

    public function adminGet($id) {
        $file = File::find($id);
        return Storage::disk('files')->get($file->file_name);
    }

    /**
     * Show all Files
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function clientIndex($id = null) {
        $selected_id = $id;
        return view('client.files.index', compact('selected_id'));
    }

    public function clientGet($id) {
        $file = File::find($id);
        return Storage::disk('files')->get($file->file_name);
    }
}
