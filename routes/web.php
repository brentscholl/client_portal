<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Auth\Passwords\Confirm;
use App\Http\Livewire\Auth\Passwords\Email;
use App\Http\Livewire\Auth\Passwords\Reset;
use App\Http\Livewire\Auth\Register;
use App\Http\Livewire\Auth\Verify;
    use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/mailable', function () {

    $data = ['message' => 'Hello World'];
    return new App\Mail\NotifyClient($data);
});

Route::get('password/reset', Email::class)->name('password.request');
Route::get('password/reset/{token}', Reset::class)->name('password.reset');

// Auth ===========================================================================
Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)->name('login');
    Route::get('register', Register::class)->name('register');
});
Route::post('logout', LogoutController::class)->name('logout');

// Dashboard ================================================================
Route::middleware('auth')->group(function () {
    // Home
    Route::view('/', 'welcome')->name('home');
    Route::get('/admin', function () {
        return redirect()->route('home');
    });

    // Email Verification
    Route::get('email/verify', Verify::class)->middleware('throttle:6,1')->name('verification.notice');
    Route::get('password/confirm', Confirm::class)->name('password.confirm');
    Route::get('email/verify/{id}/{hash}', EmailVerificationController::class)->middleware('signed')->name('verification.verify');

    // Impersonate
    Route::get('/leave-impersonation', [\App\Http\Controllers\ImpersonationController::class, 'leave'])->name('leave-impersonation');

    // Settings
    Route::group(['prefix' => '/settings'], function () {
        Route::get('/', [\App\Http\Controllers\SettingsController::class, 'general'])->name('settings.general');
        Route::get('/password', [\App\Http\Controllers\SettingsController::class, 'password'])->name('settings.password');
        Route::get('/notifications', [\App\Http\Controllers\SettingsController::class, 'notifications'])->name('settings.notifications');
        Route::get('/team-members', [\App\Http\Controllers\SettingsController::class, 'teamMembers'])->name('settings.team-members');
    });

    Route::view('/team', 'team')->name('team.index');
    Route::view('/team/add-user', 'users.create')->name('users.create');
});

// ADMIN ===========================================================================//
Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [\App\Http\Controllers\UsersController::class, 'adminIndex'])->name('admin.users.index');
        Route::get('{id}',                                      [\App\Http\Controllers\UsersController::class, 'adminShow'])->name('admin.users.show');
    });

    Route::group(['prefix' => 'clients'], function () {
        Route::get('/',                                         [\App\Http\Controllers\ClientsController::class, 'adminIndex'])->name('admin.clients.index');
        Route::get('/email-template/{id}',                      [\App\Http\Controllers\ClientsController::class, 'adminShowEmailTemplate'])->name('admin.emailtemplates.show');
        Route::get('{id}',                                      [\App\Http\Controllers\ClientsController::class, 'adminShow'])->name('admin.clients.show');
    });
    Route::group(['prefix' => 'projects'], function () {
        Route::get('/',                                         [\App\Http\Controllers\ProjectsController::class, 'adminIndex'])->name('admin.projects.index');
        Route::get('{id}',                                      [\App\Http\Controllers\ProjectsController::class, 'adminShow'])->name('admin.projects.show');
    });
    Route::group(['prefix' => 'phase'], function () {
        Route::get('{id}',                                      [\App\Http\Controllers\PhasesController::class, 'adminShow'])->name('admin.phases.show');
    });
    Route::group(['prefix' => 'tasks'], function () {
        Route::get('/',                                         [\App\Http\Controllers\TasksController::class, 'adminIndex'])->name('admin.tasks.index');
        Route::get('{id}',                                      [\App\Http\Controllers\TasksController::class, 'adminShow'])->name('admin.tasks.show');
    });
    Route::group(['prefix' => 'services'], function () {
        Route::get('/',                                         [\App\Http\Controllers\ServicesController::class, 'adminIndex'])->name('admin.services.index');
        Route::get('{id}',                                      [\App\Http\Controllers\ServicesController::class, 'adminShow'])->name('admin.services.show');
        Route::group(['prefix' => 'package'], function () {
            Route::get('{id}',                                  [\App\Http\Controllers\ServicesController::class, 'adminPackageShow'])->name('admin.packages.show');
        });
    });
    Route::group(['prefix' => 'files'], function () {
        Route::get('/',                                         [\App\Http\Controllers\FilesController::class, 'adminIndex'])->name('admin.files.index');
        Route::get('{id}',                                      [\App\Http\Controllers\FilesController::class, 'adminIndex'])->name('admin.files.show');
        Route::get('upload-file/{id}',                             [\App\Http\Controllers\FilesController::class, 'adminGet'])->name('admin.files.get');
    });
    Route::group(['prefix' => 'questions'], function () {
        Route::get('/',                                         [\App\Http\Controllers\QuestionsController::class, 'adminIndex'])->name('admin.questions.index');
        Route::get('{id}',                                      [\App\Http\Controllers\QuestionsController::class, 'adminShow'])->name('admin.questions.show');
    });
    Route::get('answers/{id}',                             [\App\Http\Controllers\QuestionsController::class, 'adminShowAnswer'])->name('admin.answers.show');
    Route::group(['prefix' => 'tutorials'], function () {
        Route::get('/',                                         [\App\Http\Controllers\TutorialsController::class, 'adminIndex'])->name('admin.tutorials.index');
        Route::get('{id}',                                      [\App\Http\Controllers\TutorialsController::class, 'adminShow'])->name('admin.tutorials.show');
    });
    Route::group(['prefix' => 'teams'], function () {
        Route::get('/',                                         [\App\Http\Controllers\TeamsController::class, 'adminIndex'])->name('admin.teams.index');
        Route::get('{id}',                                      [\App\Http\Controllers\TeamsController::class, 'adminShow'])->name('admin.teams.show');
    });
});

// CLIENT =========================================================================== //
    Route::group(['middleware' => 'client'], function () {
        Route::group(['prefix' => 'projects'], function () {
            Route::get('/',                                         [\App\Http\Controllers\ProjectsController::class, 'clientIndex'])->name('client.projects.index');
            Route::get('{id}',                                      [\App\Http\Controllers\ProjectsController::class, 'clientShow'])->name('client.projects.show');
        });
        Route::group(['prefix' => 'phase'], function () {
            Route::get('{id}',                                      [\App\Http\Controllers\PhasesController::class, 'clientShow'])->name('client.phases.show');
        });
        Route::group(['prefix' => 'tasks'], function () {
            Route::get('/',                                         [\App\Http\Controllers\TasksController::class, 'clientIndex'])->name('client.tasks.index');
            Route::get('{id}',                                      [\App\Http\Controllers\TasksController::class, 'clientShow'])->name('client.tasks.show');
        });
        Route::group(['prefix' => 'services'], function () {
            Route::get('/',                                         [\App\Http\Controllers\ServicesController::class, 'clientIndex'])->name('client.services.index');
            Route::get('/show',                                         [\App\Http\Controllers\ServicesController::class, 'clientShow'])->name('client.services.show');
        });
        Route::group(['prefix' => 'files'], function () {
            Route::get('/',                                         [\App\Http\Controllers\FilesController::class, 'clientIndex'])->name('client.files.index');
            Route::get('{id}',                                      [\App\Http\Controllers\FilesController::class, 'clientIndex'])->name('client.files.show');
            Route::get('upload-file/{id}',                             [\App\Http\Controllers\FilesController::class, 'clientGet'])->name('client.files.get');
        });
        Route::group(['prefix' => 'questions'], function () {
            Route::get('/',                                         [\App\Http\Controllers\QuestionsController::class, 'clientIndex'])->name('client.questions.index');
            Route::get('{id}',                                      [\App\Http\Controllers\QuestionsController::class, 'clientShow'])->name('client.questions.show');
        });
        Route::get('answers/{id}',                             [\App\Http\Controllers\QuestionsController::class, 'clientShowAnswer'])->name('client.answers.show');
        Route::group(['prefix' => 'tutorials'], function () {
            Route::get('/',                                         [\App\Http\Controllers\TutorialsController::class, 'clientIndex'])->name('client.tutorials.index');
            Route::get('{id}',                                      [\App\Http\Controllers\TutorialsController::class, 'clientShow'])->name('client.tutorials.show');
        });
        Route::group(['prefix' => 'team'], function () {
            Route::get('/',                                         [\App\Http\Controllers\TeamsController::class, 'clientIndex'])->name('client.teams.index');
        });
        Route::group(['prefix' => 'users'], function () {
            Route::get('{id}',                                      [\App\Http\Controllers\UsersController::class, 'clientShow'])->name('client.users.show');
        });
    });



































