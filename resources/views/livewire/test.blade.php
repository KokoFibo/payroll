<div>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Company</th>
                <th>Departemen</th>
                <th>Jabatan</th>
                <th>Status</th>
                <th>tanggal_bergabung</th>
                <th>lama_bekerja</th>

                <th>Gaji Pokok</th>

            </tr>
        </thead>
        <tbody>

            @foreach ($data as $d)
                <tr>
                    <td>{{ $d->id_karyawan }}</td>
                    <td>{{ $d->nama }}</td>
                    <th>{{ $d->company }}</th>
                    <th>{{ $d->departemen }}</th>
                    <th>{{ $d->jabatan }}</th>
                    <th>{{ $d->status_karyawan }}</th>
                    <td>{{ $d->tanggal_bergabung }}</td>

                    <td>{{ number_format(lama_bekerja($d->tanggal_bergabung, $today)) }}</td>
                    <td>{{ number_format($d->gaji_pokok) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $data->links() }}
</div>
