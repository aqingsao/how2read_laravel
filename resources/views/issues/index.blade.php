@extends('layouts.app')

@section('content')
@if (count($issues) > 0)
  <div class="panel panel-default">
    <div class="panel-heading">
      Current Tasks
    </div>

    <div class="panel-body">
      <table class="table table-striped task-table">

        <thead>
          <th>Task</th>
          <th>&nbsp;</th>
        </thead>

        <tbody>
          @foreach ($issues as $issue)
            <tr>
              <td class="table-text">
                <div>{{ $issue->name }}</div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endif
@endsection