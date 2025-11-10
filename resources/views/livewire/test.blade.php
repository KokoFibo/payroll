<div class="max-w-4xl mx-auto p-4">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Total JAM</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $d)
                <tr>
                    <td>{{ $d->user_id }}</td>
                    <td>{{ $d->nama }}</td>
                    {{-- <td>{{ $d->total_jam_kerja }}</td> --}}
                    <td>{{ $d->total_jam_kerja_libur }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
