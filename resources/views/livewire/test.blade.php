<div>

    <div class="card-body">
        <div class="table-responsive">

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ __('ID') }} <i class="fa-solid fa-sort"></i></th>
                        <th>
                            {{ __('Date') }} <i class="fa-solid fa-sort"></i></th>
                        <th>{{ __('Nama') }} <i class="fa-solid fa-sort"></i></th>
                        <th>{{ __('Status') }} <i class="fa-solid fa-sort"></i>
                        <th>{{ __('Metode Penggajian') }} <i class="fa-solid fa-sort"></i>
                        <th>{{ __('Tanggal resigned') }} <i class="fa-solid fa-sort"></i>
                        <th>{{ __('Lama Bekerja') }} <i class="fa-solid fa-sort"></i>
                        <th>{{ __('Manfaat Libur') }} <i class="fa-solid fa-sort"></i>
                        <th>{{ __('Manfaat Libur Resigned') }} <i class="fa-solid fa-sort"></i>
                        <th>{{ __('Total Gaji') }} <i class="fa-solid fa-sort"></i>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if ($data->isNotEmpty())

                        @php
                            $cx = 0;
                            $tg = 0;
                        @endphp
                        @foreach ($data as $p)
                            {{-- @if (manfaat_libur_resigned('04', '2024', $libur, $p->id_karyawan) < 5) --}}
                            @php
                                $cx++;
                                $tg += $p->total;
                            @endphp
                            <tr>
                                <td>{{ $p->id_karyawan }}</td>
                                <td>{{ $p->date }}</td>
                                <td>{{ $p->nama }}</td>
                                <td>{{ $p->status_karyawan }}</td>
                                <td>{{ $p->metode_penggajian }}</td>
                                <td>{{ $p->tanggal_resigned }}</td>
                                <td>{{ lamaBekerja($p->tanggal_bergabung) }}</td>
                                <td>{{ manfaat_libur('04', '2024', $libur, $p->id_karyawan) }}</td>
                                <td>{{ manfaat_libur_resigned('04', '2024', $libur, $p->id_karyawan) }}</td>
                                <td>{{ number_format($p->total) }}</td>


                            </tr>
                            {{-- @endif --}}
                        @endforeach
                    @else
                        <h4>{{ __('No Data Found') }}</h4>
                    @endif
                </tbody>
            </table>
            {{-- {{ $data->links() }} --}}
            <h1>Total : {{ $cx }}</h1>
            <h1>Jumlah total : {{ $tg }}</h1>
        </div>
    </div>
</div>
