<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes...
Route::auth();

// issues
Route::get('/issues', 'IssueController@index');
Route::get('/issues/{issue_id}', 'IssueController@show');
Route::get('/issues/{issue_id}/result', 'IssueController@result');

// questions
Route::get('/questions/add', 'QuestionController@add');
Route::post('/questions', function (Request $request) {
});

// Api
Route::get('/api/issues/{issue_id}/questions', 'Api\IssueController@questions');
Route::post('/api/questions/{question_id}/vote/{choice_id}', 'Api\QuestionController@vote');


Route::auth();
Route::get('/home', 'HomeController@index');
