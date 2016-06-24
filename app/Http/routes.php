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

// Route::group(['middleware' => 'web'], function () {
// }];

Route::get('/', 'IssueController@index');

// Authentication Routes...
Route::auth();

// issues
Route::get('/issues', 'IssueController@index');
Route::get('/issues/{issue_id}', 'IssueController@show');
Route::get('/issues/{issue_id}/questions', 'IssueController@questions');
Route::get('/api/issues/{issue_id}', 'Api\IssueController@detail');
Route::get('/api/issues/{issue_id}/questions', 'Api\IssueController@questions');
Route::get('/api/issues/{issue_id}/summary', 'Api\IssueController@summary');
Route::post('/api/issues/{issue_id}/finish', 'Api\IssueController@finish');
Route::post('/api/issues/{issue_id}/{question_id}/{choice_id}/vote', 'Api\IssueController@vote');

// questions
Route::get('/questions/add', 'QuestionController@add');
Route::get('/questions/{question_id}', 'QuestionController@show');
Route::post('/api/questions', 'Api\QuestionController@create');
Route::get('/api/questions/find_by_name/{name}', 'Api\QuestionController@find_by_name');

// admin
Route::get('/admin/questions/add', 'Admin\AdminController@question_add');
