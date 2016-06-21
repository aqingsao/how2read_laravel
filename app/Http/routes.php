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
Route::get('/issues/{issue_id}/result', 'IssueController@result');
Route::get('/api/issues/{issue_id}', 'Api\IssueController@detail');
Route::get('/api/issues/{issue_id}/summary', 'Api\IssueController@summary');
Route::post('/api/issues/{issue_id}/finish', 'Api\IssueController@finish');

// questions
Route::get('/questions/add', 'QuestionController@add');
Route::post('/api/questions', 'Api\QuestionController@create');
Route::post('/api/questions/{question_id}/vote/{choice_id}', 'Api\QuestionController@vote');
Route::get('/api/questions/find_by_name/{name}', 'Api\QuestionController@find_by_name');

Route::get('/home', 'HomeController@index');
