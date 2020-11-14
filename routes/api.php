<?php

use App\Models\AssessmentRecord;
use App\Models\Employee;
use App\Models\ScheduleRecord;
use App\Utils\AssessmentUtils;
use App\Utils\TokenGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Token;

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

Route::post('questions', 'QuestionController@store');
Route::get('questions', 'QuestionController@getAllQuestions');
Route::get('question/{id}', 'QuestionController@getSingleQuestion');
Route::put('question/update', 'QuestionController@updateQuestion');
Route::delete('question/{id}', 'QuestionController@deleteQuestion');
Route::put('choice/update', 'QuestionController@updateChoice');
Route::delete('choice', 'QuestionController@deleteChoice');

// Route::apiResource('questions', 'QuestionController');
