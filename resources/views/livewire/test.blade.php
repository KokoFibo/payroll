<div>
    <livewire:placementreport />
    <br><br><br>
    <button class="btn btn-primary" wire:click='like'>Like</button>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>PLacement</th>
                        <th>Company</th>
                        <th>Department</th>
                        <th>Jabatan</th>
                        <th>Gaji pokok</th>



                    </tr>
                </thead>
                <tbody>

                    @foreach ($datas as $p)
                        <tr>
                            <td>{{ $p->id_karyawan }}</td>
                            <td>{{ $p->nama }}</td>
                            <th>{{ $p->placement }}</th>
                            <th>{{ $p->company->company_name }}</th>
                            <th>{{ $p->department->nama_department }}</th>
                            <th>{{ $p->jabatan->nama_jabatan }}</th>
                            <th>{{ number_format($p->gaji_pokok) }}</th>

                        </tr>
                        {{-- @endif --}}
                    @endforeach

                </tbody>
            </table>
            {{ $datas->links() }}
        </div>
    </div>
</div>
