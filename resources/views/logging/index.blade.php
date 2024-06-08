@extends('layouts.app2')
@section('content')
    <h1>test</h1>
    <button class="btn btn-primary">test</button>
    <div>
        <table class='table'>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>log_name</th>
                    <th>description</th>
                    <th>subject_type </th>
                    <th>event</th>
                    <th>subject_id </th>
                    <th>causer_type </th>
                    <th>causer_id </th>
                    <th>properties </th>
                    <th>batch_uuid </th>
                    <th>created_at </th>
                    <th>updated_at </th>
                </tr>
            </thead>
            @foreach ($activity as $key => $a)
                <tr>
                    <td>{{ $a->Id }}</td>
                    <td>{{ $a->log_name }}</td>
                    <td>{{ $a->description }}</td>
                    <td>{{ $a->subject_type }}</td>
                    <td>{{ $a->event }}</td>
                    <td>{{ $a->subject_id }}</td>
                    <td>{{ $a->causer_type }}</td>
                    <td>{{ $a->causer_id }}</td>
                    <td>{{ $a->properties }}</td>
                    <td>{{ $a->batch_uuid }}</td>
                    <td>{{ $a->created_at }}</td>
                    <td>{{ $a->updated_at }}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
