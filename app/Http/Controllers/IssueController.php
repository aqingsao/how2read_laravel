<?php

namespace App\Http\Controllers;
use App\Question;
use App\Issue;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Redirect;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Log;


class IssueController extends Controller
{
    public function __construct()
    {
    }

    public function index(){
        $issues = Issue::where('status', 1)->get();
        return view('issues.index', ['issues'=>$issues]);
    }

    public function show($issue_id){
        try{
            $issue = Issue::findOrFail($issue_id);
            return view('issues.show', [
                'issue' => $issue
            ]);
        } catch(ModelNotFoundException $e) {
            Log::info('issue does not exist');
            return redirect()->action('IssueController@index');
        }
    }

    public function questions($issue_id){
        try{
            $issue = Issue::findOrFail($issue_id);
            return view('issues.questions', [
                'issue' => $issue
            ]);
        } catch(ModelNotFoundException $e) {
            Log::info('issue does not exist');
            return redirect()->action('IssueController@index');
        }
    }
}
