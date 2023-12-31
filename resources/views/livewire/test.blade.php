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
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @foreach ($data as $d)
                        <tr>
                            <td>{{ $d->user_id }}</td>
                            <td>{{ $d->karyawan->nama }}</td>
                            <td>{{ $d->date }}</td>
                        </tr>
                    @endforeach --}}
                </tbody>
            </table>
        </div>
    </div>
</div>
