<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <title>Payroll Excel View</title>
</head>


<body>
    <table class="table">
        <thead>
            <tr>
                <th colspan="5" style="font-size:20px;  text-align:center">
                    <h3>{{ $header_text }}</h3>
                </th>
            </tr>
            <tr>
                <th>ID Karyawan</th>
                <th>Nama Karyawan</th>
                <th>Bank</th>
                <th>No. Rekening</th>
                <th>Jabatan</th>
                <th>Company</th>
                <th>Placement</th>
                <th>Department</th>
                <th>Metode Penggajian</th>
                <th>Total Hari Kerja</th>
                <th>Total Jam Kerja (Bersih)</th>
                <th>Total Jam Lembur</th>
                <th>Jumlah Jam Terlambat</th>
                <th>Tambahan Shift Malam</th>
                <th>Gaji Pokok</th>
                <th>Gaji Lembur</th>
                <th>Gaji Libur</th>
                <th>Gaji BPJS</th>
                <th>Bonus/U.Makan</th>
                <th>Potongan 1X</th>
                <th>Total NoScan</th>
                <th>Denda Lupa Absen</th>
                <th>Denda Resigned</th>
                <th>JHT</th>
                <th>JP</th>
                <th>JKK</th>
                <th>JKM</th>
                <th>Kesehatan</th>
                <th>Tanggungan</th>
                <th>Iuran Air</th>
                <th>Iuran Locker</th>
                <th>Status Karyawan</th>
                <th>JKK Company</th>
                <th>JKM Company</th>
                <th>Kesehatan Company</th>
                <th>Total BPJS</th>
                <th>PTKP</th>
                <th>TER</th>
                <th>Rate</th>
                <th>Pph21</th>
                <th>Total</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $d)
                @php
                    // if ($d->jkk != null) {
                    //     $jkk_company = ($d->gaji_bpjs * 0.24) / 100;
                    // } else {
                    //     $jkk_company = 0;
                    // }

                    // if ($d->jkm != null) {
                    //     $jkm_company = ($d->gaji_bpjs * 0.3) / 100;
                    // } else {
                    //     $jkm_company = 0;
                    // }

                    // if ($d->kesehatan != null) {
                    //     $kesehatan_company = ($d->gaji_bpjs * 0.4) / 100;
                    // } else {
                    //     $kesehatan_company = 0;
                    // }

                    $jkk_company = ($d->gaji_bpjs * 0.24) / 100;
                    $jkm_company = ($d->gaji_bpjs * 0.3) / 100;
                    $kesehatan_company = ($d->gaji_bpjs * 0.4) / 100;
                    $total_bpjs_company = $d->gaji_bpjs + $jkk_company + $jkm_company + $kesehatan_company;
                    $ter = '';
                    switch ($d->ptkp) {
                        case 'TK0':
                            $ter = 'A';
                            break;
                        case 'TK1':
                            $ter = 'A';
                            break;
                        case 'TK2':
                            $ter = 'B';
                            break;
                        case 'TK3':
                            $ter = 'B';
                            break;
                        case 'K0':
                            $ter = 'A';
                            break;
                        case 'K1':
                            $ter = 'B';
                            break;
                        case 'K2':
                            $ter = 'B';
                            break;
                        case 'K3':
                            $ter = 'C';
                            break;
                    }

                    $rate_pph21 = get_rate_ter_pph21($d->ptkp, $total_bpjs_company);
                    $pph21 = ($total_bpjs_company * $rate_pph21) / 100;

                @endphp
                <tr>
                    <td style="text-align: center"> {{ $d->id_karyawan }}</td>
                    <td> {{ $d->nama }}</td>
                    <td style="text-align: center"> {{ $d->nama_bank }}</td>
                    <td style="text-align: center"> {{ strval($d->nomor_rekening) }}</td>
                    <td style="text-align: center"> {{ $d->jabatan }}</td>
                    <td style="text-align: center"> {{ $d->company }}</td>
                    <td style="text-align: center"> {{ $d->placement }}</td>
                    <td style="text-align: center"> {{ $d->departemen }}</td>
                    <td style="text-align: center"> {{ $d->metode_penggajian }}</td>
                    <td> {{ $d->hari_kerja }}</td>
                    <td> {{ $d->jam_kerja }}</td>
                    <td> {{ $d->jam_lembur }}</td>
                    <td> {{ $d->jumlah_jam_terlambat }}</td>
                    <td style="text-align: right"> {{ number_format($d->tambahan_shift_malam) }}</td>
                    <td style="text-align: right"> {{ number_format($d->gaji_pokok) }}</td>
                    <td style="text-align: right"> {{ number_format($d->gaji_lembur) }}</td>
                    <td style="text-align: right"> {{ number_format($d->gaji_libur) }}</td>
                    <td style="text-align: right"> {{ number_format($d->gaji_bpjs) }}</td>
                    <td style="text-align: right"> {{ number_format($d->bonus1x) }}</td>
                    <td style="text-align: right"> {{ number_format($d->potongan1x) }}</td>
                    <td> {{ $d->total_noscan }}</td>
                    <td style="text-align: right"> {{ number_format($d->denda_lupa_absen) }}</td>
                    <td style="text-align: right"> {{ number_format($d->denda_resigned) }}</td>
                    <td style="text-align: right"> {{ number_format($d->jht) }}</td>
                    <td style="text-align: right"> {{ number_format($d->jp) }}</td>
                    <td style="text-align: right"> {{ number_format($d->jkk) }}</td>
                    <td style="text-align: right"> {{ number_format($d->jkm) }}</td>
                    <td style="text-align: right"> {{ number_format($d->kesehatan) }}</td>
                    <td> {{ $d->tanggungan }}</td>
                    <td style="text-align: right"> {{ number_format($d->iuran_air) }}</td>
                    <td style="text-align: right"> {{ number_format($d->iuran_locker) }}</td>
                    <td style="text-align: center"> {{ $d->status_karyawan }}</td>
                    <td style="text-align: right"> {{ number_format($jkk_company) }}</td>
                    <td style="text-align: right"> {{ number_format($jkm_company) }}</td>
                    <td style="text-align: right"> {{ number_format($kesehatan_company) }}</td>
                    <td style="text-align: right"> {{ number_format($total_bpjs_company) }}</td>
                    <td style="text-align: right"> {{ $d->ptkp }}</td>
                    <td style="text-align: right"> {{ $ter }}</td>
                    <td style="text-align: right"> {{ $rate_pph21 }}</td>
                    <td style="text-align: right"> {{ number_format($pph21) }}</td>
                    <td style="text-align: right"> {{ number_format($d->total) }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>
</body>

</html>
