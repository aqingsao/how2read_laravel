@extends('layouts.app')

@section('content')
  <div class="panel panel-default">
    <div class="panel-heading">
      Current Tasks
    </div>

    <div class="panel-body">
      <table class="table table-striped task-table">

        <!-- Table Headings -->
        <thead>
          <th>Task</th>
          <th>&nbsp;</th>
        </thead>

        <!-- Table Body -->
        <tbody>
          @foreach ($questions as $questions)
            <tr>
              <td class="table-text">
                <div>{{ $question->name }}</div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection