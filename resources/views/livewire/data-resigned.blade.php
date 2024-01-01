<div>
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-lg-row justify-content-evenly">
                <h4>Data Karyawan Resigned</h4>
                <div>
                    <select class="form-select" aria-label="Default select example" wire:model.live="month">
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                        <option value="1">Januari</option>
                    </select>
                </div>
                <div>
                    <select class="form-select" aria-label="Default select example" wire:model.live="year">
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="p-3">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Metode</th>
                            <th>Resigned</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $d)
                            <tr>
                                <td>{{ $d->id_karyawan }}</td>
                                <td>{{ $d->nama }}</td>
                                <td>{{ $d->metode_penggajian }}</td>
                                <td>{{ $d->tanggal_resigned }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $data->links() }}
        </div>
    </div>
</div>
