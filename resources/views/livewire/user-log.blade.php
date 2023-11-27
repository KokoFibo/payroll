<div>
    <div class="col-12 col-xl-6 pt-5 mx-auto">
        <div class="card">
            <div class="card-header bg-success">
                <h3>Yifang Payroll Activity Logs </h3>
                <h5>Today's Login : {{ $today_logs }} </h5>
                <h5>Yesterday's Login : {{ $yesterday_log }} </h5>
                <h5>Total Login : {{ $total_logs }} </h5>
                <h5>Total Created Log : {{ $total_created_logs }} </h5>
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
            </div>
        </div>
        {{ $data->onEachSide(0)->links() }}
    </div>
</div>
