<div>
    <livewire:placementreport />
    <br><br><br>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ __('ID') }} <i class="fa-solid fa-sort"></i></th>
                        <th>{{ __('Nama') }} <i class="fa-solid fa-sort"></i></th>
                        <th>Tanggal bergabung</th>
                        <th>Tanggal resign</th>
                        <th>jam lembur</th>
                        <th>gaji lembur</th>
                        <th>
                            {{ __('status') }} <i class="fa-solid fa-sort"></i></th>
                        <th>Gaji pokok</th>
                        <th>hari_kerja</th>
                        <th>Sub Gaji</th>


                    </tr>
                </thead>
                <tbody>

                    @foreach ($datas as $p)
                        <tr>
                            <td>{{ $p->id_karyawan }}</td>
                            <td>{{ $p->nama }}</td>
                            <td>{{ $p->tanggal_bergabung }}</td>
                            <td>{{ $p->tanggal_resigned }}</td>
                            <td>{{ number_format($p->jam_lembur, 1) }}</td>
                            <td>{{ number_format($p->gaji_lembur) }}</td>
                            <td>{{ $p->status_karyawan }}</td>
                            <td>{{ number_format($p->gaji_pokok) }}</td>
                            <td>{{ $p->hari_kerja }}</td>
                            <td>{{ number_format($p->subtotal) }}</td>

                        </tr>
                        {{-- @endif --}}
                    @endforeach

                </tbody>
            </table>
            {{ $datas->links() }}
        </div>
    </div>
</div>
