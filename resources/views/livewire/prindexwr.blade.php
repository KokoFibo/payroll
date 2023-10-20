<div>

    <div class="card">
        <div class="card-header">
            <h3>Detail Jam kerja karyawan</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Jumlah Jam Kerja</th>
                        <th>Jumlah Menit Lembu4</th>
                        <th>Jumlah Jam terlambat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($filteredData as $item)
                        <tr>
                            <td>{{ $item->user_id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->jumlah_jam_kerja }}</td>
                            <td>{{ $item->jumlah_menit_lembur }}</td>
                            <td>{{ $item->jumlah_jam_terlambat }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{ $filteredData->links() }}
</div>
