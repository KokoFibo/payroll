<div>
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h4 class="mb-0">Laporan Salary Adjustment</h4>

        <a href="{{ route('salary-adjustment.export', ['month' => $month, 'year' => $year]) }}" class="btn btn-success">
            Generate Excel
        </a>
    </div>

    <div class="row g-2 mb-4">
        <div class="col-auto">
            <label class="form-label mb-1">Bulan</label>
            <select wire:model.live="month" class="form-select">
                @foreach (range(1, 12) as $m)
                    <option value="{{ $m }}">
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <label class="form-label mb-1">Tahun</label>
            <select wire:model.live="year" class="form-select">
                @foreach (range(now()->year - 2, now()->year) as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @foreach (['OS', 'Non OS'] as $category)
        <h5 class="mt-4">{{ $category }}</h5>
        <div class="table-responsive mb-4">
            <table class="table table-bordered table-sm align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Directorate</th>
                        <th>Departemen</th>
                        <th>Adjustment Type</th>
                        <th class="text-end">Number of Employees</th>
                        <th class="text-end">Total Adjustment Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php $any = false; @endphp
                    @foreach ($rows as $key => $row)
                        @continue($row['category'] !== $category)
                        @php $any = true; @endphp
                        <tr wire:key="row-{{ $key }}">
                            <td>{{ $row['directorate'] }}</td>
                            <td>{{ $row['departemen'] }}</td>
                            <td>{{ $row['adjustment_type'] }}</td>
                            <td class="text-end">{{ $row['jumlah_karyawan'] }}</td>
                            <td class="text-end">{{ number_format($row['total_adjustment'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    @unless ($any)
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                Tidak ada data kenaikan pada periode ini.
                            </td>
                        </tr>
                    @endunless
                </tbody>
            </table>
        </div>
    @endforeach
</div>
