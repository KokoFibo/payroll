<div>
    <div class="card">
        <div class="card-header">
            <h4>Data Absensi Kosong</h4>
            <button class="btn btn-primary" wire:click="delete">Delete All</button>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Metode</th>
                        <th>Resigned</th>
                        <th>Blacklist</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $d)
                        <tr>
                            <td>{{ $d->id_karyawan }}</td>
                            <td>{{ $d->nama }}</td>
                            <td>{{ $d->metode_penggajian }}</td>
                            <td>{{ $d->tanggal_resigned }}</td>
                            <td>{{ $d->tanggal_blacklist }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $data->links() }}
    </div>
</div>
