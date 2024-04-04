<div>
    <button class="btn btn-primary" wire:click="build">Build</button>
    <div class="card-body">
        <div class="table-responsive">
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
                        <th>{{ __('Hari Kerja') }} <i class="fa-solid fa-sort"></i>
                        <th>{{ __('Manfaat Libur') }} <i class="fa-solid fa-sort"></i>
                        <th>{{ __('Subtotal') }} <i class="fa-solid fa-sort"></i>
                        </th>


                    </tr>
                </thead>
                <tbody>
                    @if ($payroll->isNotEmpty())

                        @foreach ($payroll as $p)
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
                                <td>{{ $p->hari_kerja }}</td>
                                @php
                                    $manfaat_libur = manfaat_libur('03', '2024', $libur, $p->id_karyawan);
                                @endphp
                                <td>{{ $manfaat_libur }}</td>
                                <td>{{ number_format($p->subtotal) }}</td>
                            </tr>
                        @endforeach
                    @else
                        <h4>{{ __('No Data Found') }}</h4>
                    @endif
                </tbody>
            </table>
            {{ $payroll->links() }}
        </div>
    </div>
</div>
