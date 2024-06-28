<div>
    <div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Karyawan</th>
                        <th>Placement</th>
                        <th>Requester</th>
                        <th>Approve1</th>
                        <th>Approve2</th>
                        <th>Done</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $d)
                        <tr>
                            <td>{{ $d->id }}</td>
                        </tr>
                        <td>{{ $d->karyawan_id }}</td>
                        </tr>
                        <td>{{ $d->placement_id }}</td>
                        </tr>
                        <td>{{ $d->request_type }}</td>
                        </tr>
                        <td>{{ $d->start_date }}</td>
                        <td>{{ $d->end_date }}</td>
                        <td>{{ $d->description }}</td>
                        <td>{{ $d->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
