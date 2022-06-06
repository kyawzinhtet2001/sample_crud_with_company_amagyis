<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\LanguageMiddleware;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::name('student-')->prefix('student/')->middleware(['middleware'=>LanguageMiddleware::class])->group(function(){
    Route::get('/','Api\StudentController@index');
    Route::post('/','Api\StudentController@store');
    Route::put('/{id}','Api\StudentController@update')->where('id',"\d+");
    Route::delete('/{id}','Api\StudentController@destroy')->where('id',"\d+");
    Route::get('/{id}','Api\StudentController@detail')->where('id',"\d+");
    Route::get('excel/download','Api\StudentController@excelDownload');
    Route::post('excel/upload','Api\StudentController@excelUpload');
});


Route::name('teacher-')->prefix('teacher/')->middleware(['middleware'=>LanguageMiddleware::class])->group(function(){
    Route::get('/','Api\TeachersController@index');
    Route::post('/','Api\TeachersController@store');
    Route::put('/{id}','Api\TeachersController@update')->where('id',"\d+");
    Route::delete('/{id}','Api\TeachersController@destroy')->where('id',"\d+");
    Route::get('/{id}','Api\TeachersController@detail')->where('id',"\d+");
});
Route::name('teacher-')->prefix('classroom/')->middleware(['middleware'=>LanguageMiddleware::class])->group(function(){
    Route::get('/','Api\ClassroomController@index');
    Route::post('/','Api\ClassroomController@store');
    Route::put('/{id}','Api\ClassroomController@update')->where('id',"\d+");
    Route::delete('/{id}','Api\ClassroomController@destroy')->where('id',"\d+");
    Route::get('/{id}','Api\ClassroomController@detail')->where('id',"\d+");
});
