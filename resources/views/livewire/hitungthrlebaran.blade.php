<div>
    @section('title', 'Laporan Hitung THR Lebaran')

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 fw-bold text-dark">Data THR Karyawan Lebaran OS</h4>
                    <small class="text-muted">
                        Tanggal Cut Off : <strong>{{ format_tgl($cutOffDate) }}</strong>
                    </small>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button wire:click="excel" class="btn btn-success d-flex align-items-center gap-2"
                        wire:loading.attr="disabled">
                        <i class="bi bi-file-earmark-excel"></i> Export Excel
                    </button>

                    <div wire:loading wire:target="excel" class="small text-success fw-bold">
                        <div class="spinner-border spinner-border-sm"></div> Mengolah...
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">

            {{-- Informasi Ketentuan --}}
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="alert alert-info border-0 shadow-sm mb-0">
                        <h6 class="fw-bold">
                            <i class="bi bi-info-circle-fill me-2"></i>Ketentuan Perhitungan:
                        </h6>
                        <ul class="mb-0 small">
                            <li>Masa kerja 1-11 bulan: Mengikuti tabel reward berdasarkan genap bulan.</li>
                            <li>Masa kerja 6-11 bulan: Mendapatkan Cash + 1 Unit HP.</li>
                            <li>Masa kerja ≥ 12 bulan: Mendapatkan 1 bulan gaji penuh. (Gaji per Maret 2025)</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-light border-0 shadow-sm h-100">
                        <div class="card-body text-center d-flex flex-column justify-content-center">
                            <span class="text-muted small text-uppercase fw-bold">
                                Total Pembayaran THR
                            </span>
                            <h3 class="fw-bold text-primary mb-0">
                                Rp {{ number_format($total) }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-light text-nowrap">
                        <tr>
                            <th class="text-center">ID</th>
                            <th>Nama Karyawan</th>
                            <th>Informasi Kerja</th>
                            <th class="text-center">Masa Kerja</th>
                            <th class="text-end">Gaji Pokok</th>
                            <th class="text-end">Total THR</th>
                            <th class="text-center">Item</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($karyawans as $k)
                            @php
                                $tanggalMasuk = \Carbon\Carbon::parse($k->tanggal_bergabung);
                                $cutoff = \Carbon\Carbon::parse($cutOffDate);

                                $masaKerja = $tanggalMasuk->diffInMonths($cutoff);
                                $totalHariKerja = $tanggalMasuk->diffInDays($cutoff);

                                $thr = $this->hitungTHR($k->tanggal_bergabung, $k->gaji_pokok, $k->id_karyawan);
                            @endphp

                            <tr>
                                <td class="text-center fw-bold">
                                    {{ $k->id_karyawan }}
                                </td>

                                <td>
                                    <div class="fw-bold">{{ $k->nama }}</div>
                                    <small class="text-muted">
                                        {{ $k->jabatan->nama_jabatan }}
                                    </small>
                                </td>

                                <td>
                                    <div class="small">
                                        <span class="badge bg-secondary mb-1">
                                            {{ $k->company->company_name }}
                                        </span><br>
                                        {{ $k->placement->placement_name }} |
                                        {{ $k->department->nama_department }}
                                    </div>
                                </td>

                                <td class="text-center">
                                    <div class="small fw-bold">
                                        {{ $masaKerja }} Bln
                                    </div>
                                    <small class="text-muted">
                                        {{ $totalHariKerja }} Hari
                                    </small>
                                </td>

                                <td class="text-end">
                                    Rp {{ number_format($k->gaji_pokok) }}
                                </td>

                                <td class="text-end fw-bold text-primary">
                                    Rp {{ number_format($thr) }}
                                </td>

                                <td class="text-center">
                                    @if ($masaKerja >= 6 && $masaKerja <= 11)
                                        <span class="badge bg-success">1 Unit HP</span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $karyawans->onEachSide(0)->links() }}
            </div>

        </div>
    </div>
</div>
