<?php


use App\Http\Controllers\AdmissionFormController;
use App\Http\Controllers\BooklistController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\imagegalleryController;
use App\Http\Controllers\jobController;
use App\Http\Controllers\newsController;
use App\Http\Controllers\slideController;
use App\Http\Controllers\StudentResultController;
use App\Http\Controllers\TimetableController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::group(['middleware' => 'web'], function () {
    Route::prefix('api/slides')->group(function () {
        Route::get('/', [slideController::class, 'index']);
        Route::post('/', [SlideController::class, 'store']);
        Route::delete('/{id}', [SlideController::class, 'destroy']);
        Route::put('/{id}', [SlideController::class, 'update']);
    });

    Route::prefix('api/news')->group(function () {
        Route::get('/', [newsController::class, 'index']);
        Route::post('/', [newsController::class, 'store']);
        Route::delete('/{id}', [newsController::class, 'destroy']);
        Route::put('/{id}', [newsController::class, 'update']);
    });

    Route::prefix('api/jobs')->group(function () {
        Route::get('/', [jobController::class, 'index']);
        Route::post('/', [jobController::class, 'store']);
        Route::delete('/{id}', [jobController::class, 'destroy']);
        Route::put('/{id}', [jobController::class, 'update']);
    });
    Route::prefix('api/imagegallery')->group(function () {
        Route::get('/', [imagegalleryController::class, 'index']);
        Route::post('/', [imagegalleryController::class, 'store']);
        Route::delete('/{id}', [imagegalleryController::class, 'destroy']);
        Route::put('/{id}', [imagegalleryController::class, 'update']);
    });

    Route::prefix('api/admissionform')->group(function () {
        Route::get('/', [AdmissionFormController::class, 'index']);
        Route::post('/', [AdmissionFormController::class, 'store']);
        Route::get('/{id}', [AdmissionFormController::class, 'show']);
        Route::put('/{id}', [AdmissionFormController::class, 'update']);
        Route::delete('/{id}', [AdmissionFormController::class, 'destroy']);
    });

    Route::prefix('api/contact')->group(function () {
        Route::get('/', [ContactController::class, 'index']);
        Route::post('/', [ContactController::class, 'store']);
        Route::delete('/{id}', [ContactController::class, 'destroy']);
    });
    Route::prefix('api/booklist')->group(function () {
        Route::get('/', [BooklistController::class, 'index']);
        Route::post('/', [BooklistController::class, 'store']);
        Route::delete('/{id}', [CBooklistController::class, 'destroy']);
    });
    Route::prefix('api/timetable')->group(function () {
        Route::get('/', [TimetableController::class, 'getTimetable']);
        Route::post('/', [TimetableController::class, 'store']);
        Route::delete('/{id}', [TimetableController::class, 'destroyTimetableEntry']);
    });
    Route::prefix('api/studentresult')->group(function () {
        Route::get('/', [StudentResultController::class, 'getStudentResults']);
        Route::post('/', [StudentResultController::class, 'create']);
        Route::delete('/{id}', [StudentResultController::class, 'deleteStudentResult']);
    });





    Route::get('/csrf-token', function () {
        return response()->json(['csrf_token' => csrf_token()]);
    });
});
