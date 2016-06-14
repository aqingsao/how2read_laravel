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

Route::get('/issues/', 'IssueController@index');
Route::get('/issues/{issue_id}', 'IssueController@show');
Route::get('/issues/{issue_id}/result', 'IssueController@result');

// Api
Route::get('/api/issues/{issue_id}/questions', 'Api\IssueController@questions');

Route::post('/questions', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'name' => 'required|max:255',
    ]);

    if ($validator->fails()) {
        return redirect('/')
            ->withInput()
            ->withErrors($validator);
    }

    $question = new Question;
    $question->name = $request->name;
    $question->save();

    return redirect('/');
});

Route::auth();
Route::get('/home', 'HomeController@index');
