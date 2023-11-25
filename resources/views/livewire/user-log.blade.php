<div>
    <div class="col-6 mx-auto pt-5">
        <div class="card">
            <div class="card-header bg-success">
                <h3>Yifang Payroll Activity Logs </h3>
                <h5>Today's Login : {{ $log_activities }} </h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Created At</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $d)
                            <tr>
                                <td>{{ $d->id }}</td>
                                <td>{{ $d->created_at }}</td>
                                <td>{{ $d->description }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $data->links() }}
            </div>
        </div>
    </div>
</div>
