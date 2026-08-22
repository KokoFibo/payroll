<div class="container-fluid py-4">

    <div class="card shadow-sm">

        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        Perubahan Data Gaji
                    </h5>

                    <small>
                        Update Karyawan:
                        {{ sprintf('%02d', $month) }}/{{ $year }}
                        |
                        Dibandingkan Payroll:
                        {{ sprintf('%02d', $previousMonth) }}/{{ $previousYear }}
                    </small>
                </div>

                <span class="badge bg-light text-dark">
                    {{ $data->count() }} Perubahan
                </span>
            </div>
        </div>

        <div class="card-body">

            @if ($data->count() > 0)

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-dark">

                            <tr>
                                <th rowspan="2" class="text-center align-middle">
                                    No
                                </th>

                                <th rowspan="2" class="align-middle">
                                    ID Karyawan
                                </th>

                                <th rowspan="2" class="align-middle">
                                    Nama
                                </th>

                                <th colspan="2" class="text-center">
                                    Gaji
                                </th>

                                <th colspan="2" class="text-center">
                                    Lembur
                                </th>

                                <th colspan="2" class="text-center">
                                    Bonus 1x
                                </th>

                                <th colspan="2" class="text-center">
                                    Total
                                </th>

                            </tr>

                            <tr>

                                <th class="text-center">
                                    Sebelumnya
                                </th>

                                <th class="text-center">
                                    Sekarang
                                </th>

                                <th class="text-center">
                                    Sebelumnya
                                </th>

                                <th class="text-center">
                                    Sekarang
                                </th>

                                <th class="text-center">
                                    Sebelumnya
                                </th>

                                <th class="text-center">
                                    Sekarang
                                </th>

                                <th class="text-center">
                                    Sebelumnya
                                </th>

                                <th class="text-center">
                                    Sekarang
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach ($data as $index => $row)
                                @php

                                    $gajiDiff = $row['gaji_sekarang'] - $row['gaji_sebelumnya'];

                                    $lemburDiff = $row['lembur_sekarang'] - $row['lembur_sebelumnya'];

                                    $bonusDiff = $row['bonus_sekarang'] - $row['bonus_sebelumnya'];

                                    $totalDiff = $row['total_sekarang'] - $row['total_sebelumnya'];

                                @endphp

                                <tr>

                                    {{-- NO --}}
                                    <td class="text-center">
                                        {{ $index + 1 }}
                                    </td>

                                    {{-- ID --}}
                                    <td>
                                        {{ $row['karyawan']->id_karyawan }}
                                    </td>

                                    {{-- NAMA --}}
                                    <td>
                                        <strong>
                                            {{ $row['karyawan']->nama }}
                                        </strong>

                                        <br>

                                        <small class="text-muted">
                                            Update:
                                            {{ \Carbon\Carbon::parse($row['karyawan']->tanggal_update)->format('d-m-Y') }}
                                        </small>
                                    </td>


                                    {{-- GAJI SEBELUMNYA --}}
                                    <td class="text-end">

                                        Rp
                                        {{ number_format($row['gaji_sebelumnya'], 0, ',', '.') }}

                                    </td>


                                    {{-- GAJI SEKARANG --}}
                                    <td
                                        class="text-end
                                        {{ $row['gaji_changed'] ? ($gajiDiff > 0 ? 'table-warning' : 'table-danger') : '' }}">

                                        Rp
                                        {{ number_format($row['gaji_sekarang'], 0, ',', '.') }}

                                        @if ($row['gaji_changed'])
                                            <br>

                                            <small
                                                class="
                                                {{ $gajiDiff > 0 ? 'text-success' : 'text-danger' }}
                                            ">

                                                {{ $gajiDiff > 0 ? '+' : '' }}

                                                Rp
                                                {{ number_format($gajiDiff, 0, ',', '.') }}

                                            </small>
                                        @endif

                                    </td>


                                    {{-- LEMBUR SEBELUMNYA --}}
                                    <td class="text-end">

                                        Rp
                                        {{ number_format($row['lembur_sebelumnya'], 0, ',', '.') }}

                                    </td>


                                    {{-- LEMBUR SEKARANG --}}
                                    <td
                                        class="text-end
                                        {{ $row['lembur_changed'] ? ($lemburDiff > 0 ? 'table-warning' : 'table-danger') : '' }}">

                                        Rp
                                        {{ number_format($row['lembur_sekarang'], 0, ',', '.') }}

                                        @if ($row['lembur_changed'])
                                            <br>

                                            <small
                                                class="
                                                {{ $lemburDiff > 0 ? 'text-success' : 'text-danger' }}
                                            ">

                                                {{ $lemburDiff > 0 ? '+' : '' }}

                                                Rp
                                                {{ number_format($lemburDiff, 0, ',', '.') }}

                                            </small>
                                        @endif

                                    </td>


                                    {{-- BONUS SEBELUMNYA --}}
                                    <td class="text-end">

                                        Rp
                                        {{ number_format($row['bonus_sebelumnya'], 0, ',', '.') }}

                                    </td>


                                    {{-- BONUS SEKARANG --}}
                                    <td
                                        class="text-end
                                        {{ $row['bonus_changed'] ? ($bonusDiff > 0 ? 'table-warning' : 'table-danger') : '' }}">

                                        Rp
                                        {{ number_format($row['bonus_sekarang'], 0, ',', '.') }}

                                        @if ($row['bonus_changed'])
                                            <br>

                                            <small
                                                class="
                                                {{ $bonusDiff > 0 ? 'text-success' : 'text-danger' }}
                                            ">

                                                {{ $bonusDiff > 0 ? '+' : '' }}

                                                Rp
                                                {{ number_format($bonusDiff, 0, ',', '.') }}

                                            </small>
                                        @endif

                                    </td>


                                    {{-- TOTAL SEBELUMNYA --}}
                                    <td class="text-end">

                                        <strong>
                                            Rp
                                            {{ number_format($row['total_sebelumnya'], 0, ',', '.') }}
                                        </strong>

                                    </td>


                                    {{-- TOTAL SEKARANG --}}
                                    <td
                                        class="text-end
                                        {{ $totalDiff > 0 ? 'table-warning' : ($totalDiff < 0 ? 'table-danger' : '') }}">

                                        <strong>
                                            Rp
                                            {{ number_format($row['total_sekarang'], 0, ',', '.') }}
                                        </strong>

                                        @if ($totalDiff != 0)
                                            <br>

                                            <small
                                                class="
                                                {{ $totalDiff > 0 ? 'text-success' : 'text-danger' }}
                                            ">

                                                {{ $totalDiff > 0 ? '+' : '' }}

                                                Rp
                                                {{ number_format($totalDiff, 0, ',', '.') }}

                                            </small>
                                        @endif

                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                </div>
            @else
                <div class="alert alert-success mb-0">

                    <strong>Tidak ada perubahan.</strong>

                    <br>

                    Tidak ditemukan perubahan gaji, lembur, atau bonus
                    pada karyawan yang memiliki
                    <code>tanggal_update</code>
                    pada periode tersebut.

                </div>

            @endif

        </div>

    </div>

</div>
