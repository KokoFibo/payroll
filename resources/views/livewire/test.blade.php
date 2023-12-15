<div>
    <table class="table">
        <thead>
            <tr>
                <th>id</th>
                <th>Nama</th>
                <th>tgl mulai kerja</th>
                <th>tgl resigned</th>
                <th>gaji pokok</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $d)
                <tr>
                    <td>{{ $d->id }}</td>
                    <td>{{ $d->nama }}</td>
                    <td>{{ $d->tanggal_bergabung }}</td>
                    <td>{{ $d->tanggal_resigned }}</td>
                    <td>{{ $d->gaji_pokok }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>

    {{ $data->links() }}


</div>
