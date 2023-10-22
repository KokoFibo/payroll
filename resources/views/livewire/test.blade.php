<div>
    CX = {{ $cx }}
    <table class="table">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Nama</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>


            @foreach ($datas as $data)
                <tr>
                    <td>{{ $data->user_id }}</td>
                    <td>{{ $data->name }}</td>
                    <td>{{ $data->date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $datas->links() }}
</div>
