<div>
    <style>
        td,
        th {
            white-space: nowrap;
        }
    </style>
    @section('title', 'Payroll')

    <div class="col-4 p-4">

        <div class="input-group">
            <button class="btn btn-primary" type="button"><i class="fa-solid fa-magnifying-glass"></i></button>
            <input type="search" wire:model.live="search" class="form-control" placeholder="Search ...">
        </div>

    </div>

    <div class="card">
        <div class="card-header">
            <h3>Detail Jam kerja karyawan
                <button wire:click="getPayroll" class="btn btn-primary">Get Payroll</button>
            </h3>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Jumlah Jam Kerja</th>
                        <th>Jumlah Menit Overtime</th>
                        <th>Jumlah Jam Late</th>
                        <th>First In Late</th>
                        <th>First Out Late</th>
                        <th>Second In Late</th>
                        <th>Second Out Late</th>
                        <th>Overtime In Late</th>
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
                            <td>{{ $item->first_in_late }}</td>
                            <td>{{ $item->first_out_late }}</td>
                            <td>{{ $item->second_in_late }}</td>
                            <td>{{ $item->second_out_late }}</td>
                            <td>{{ $item->overtime_in_late }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{ $filteredData->links() }}
</div>
