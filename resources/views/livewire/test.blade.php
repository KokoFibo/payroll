<div>
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-evenly">
                <div>

                    <h4>Data Karyawan Resigned & Blacklist cx : {{ $cx }}</h4>
                </div>
                <div>
                    <select class="form-select" aria-label="Default select example" wire:model.live="year">
                        <option selected>Open this select menu</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                    </select>
                </div>
                <div>
                    <select class="form-select" aria-label="Default select example" wire:model.live="month">
                        <option selected>Open this select menu</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                        <option value="1">January</option>
                    </select>
                </div>
                <div>
                    <select class="form-select" aria-label="Default select example" wire:model.live="status_karyawan">
                        <option selected>Open this select menu</option>
                        <option value="Resigned">Resigned</option>
                        <option value="Blacklist">Blacklist</option>
                    </select>
                </div>
                <div>
                    <button class="btn btn-primary" wire:click="delete">Delete All</button>
                </div>

            </div>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Metode</th>
                        <th>Status Karyawan</th>
                        @if ($status_karyawan == 'Resigned')
                            <th>Resigned</th>
                        @else
                            <th>Blacklist</th>
                        @endif
                        <th>Tanggal Bergabung</th>
                        <th>Lama Bekerja</th>
                        <th>Email</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $d)
                        {{-- @if ($d->email != acakEmail($d->nama)) --}}
                        <tr>
                            <td>{{ $d->id_karyawan }}</td>
                            <td>{{ $d->nama }}</td>
                            <td>{{ $d->metode_penggajian }}</td>
                            <td>{{ $d->status_karyawan }}</td>
                            @if ($status_karyawan == 'Resigned')
                                <td>{{ format_tgl($d->tanggal_resigned) }}</td>
                            @else
                                <td>{{ format_tgl($d->tanggal_blacklist) }}</td>
                            @endif
                            <td>{{ format_tgl($d->tanggal_bergabung) }}</td>
                            <td>{{ lamaBekerja($d->tanggal_bergabung) }}</td>
                            <td>{{ $d->email }}</td>
                        </tr>
                        {{-- @endif --}}
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $data->links() }}
    </div>
</div>
