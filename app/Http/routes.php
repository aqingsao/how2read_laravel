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
Route::get('/api/issues/{issue_id}/summary', 'Api\IssueController@summary');

// questions
Route::get('/questions/new', 'QuestionController@new');
Route::get('/questions/add', 'QuestionController@add');
Route::get('/questions/{question_name}', 'QuestionController@show')->name('question_show');
Route::get('/api/questions/{name}', 'Api\QuestionController@query');

// terms
Route::get('/term/{question_name}', 'TermController@show');

// tags
Route::get('/tags', 'TagController@index');
Route::get('/tags/{name}', 'TagController@show');

// admin
Route::get('/admin/questions/add', 'Admin\AdminController@question_add');


Route::group(['prefix' => 'api', 'middleware' => 'simpleAuth'], function () {
  Route::post('/questions/{question_name}/{choice_id}/vote', 'Api\QuestionController@vote');
  Route::post('/issues/{issue_id}/vote_finish', 'Api\IssueController@vote_finish');
  Route::post('/questions', 'Api\QuestionController@create');
  Route::post('/tags', 'Api\TagController@create');
});

