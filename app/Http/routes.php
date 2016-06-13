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

Route::get('/admin/questions', ['uses' => 'Admin\QuestionController@index', 'as' => 'admin_questions_index']);
Route::delete('/questions/{id}', function (Request $request) {
    $request->delete();
    return redirect('/');
});

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