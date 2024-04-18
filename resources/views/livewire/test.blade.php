<div>

    <div class="card-body">
        <div class="table-responsive">
            <h4>Laporan Keterlambatan {{ $bulan }} {{ $tahun }}</h4>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ __('ID') }} <i class="fa-solid fa-sort"></i></th>
                        <th>
                            {{ __('Date') }} <i class="fa-solid fa-sort"></i></th>
                        <th>{{ __('Nama') }} <i class="fa-solid fa-sort"></i></th>
                        <th>{{ __('Status') }} <i class="fa-solid fa-sort"></i></th>
                        <th>{{ __('Jabatan') }} <i class="fa-solid fa-sort"></i></th>
                        <th>{{ __('Company') }} <i class="fa-solid fa-sort"></i></th>
                        <th>{{ __('Placement') }} <i class="fa-solid fa-sort"></i>
                        <th>{{ __('Metode Penggajian') }} <i class="fa-solid fa-sort"></i>
                        <th>{{ __('Gaji Pokok') }} <i class="fa-solid fa-sort"></i>
                        <th>{{ __('Jumlah jam telambat') }} <i class="fa-solid fa-sort"></i>
                        </th>


                    </tr>
                </thead>
                <tbody>
                    @if ($data->isNotEmpty())

                        @foreach ($data as $p)
                            <tr>
                                <td>{{ $p->id_karyawan }}</td>
                                <td>{{ month_year($p->date) }}</td>
                                <td>{{ $p->nama }}</td>
                                <td>{{ $p->status_karyawan }}</td>
                                <td>{{ $p->jabatan }}</td>
                                <td>{{ $p->company }}</td>
                                <td>{{ $p->placement }}</td>
                                <td>{{ $p->metode_penggajian }}</td>
                                <td>{{ number_format($p->gaji_pokok) }}</td>
                                <td>{{ number_format($p->jumlah_jam_terlambat) }}</td>

                            </tr>
                        @endforeach
                    @else
                        <h4>{{ __('No Data Found') }}</h4>
                    @endif
                </tbody>
            </table>
            {{ $data->links() }}
        </div>
    </div>
</div>
