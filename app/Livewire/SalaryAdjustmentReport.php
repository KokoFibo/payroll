<?php

namespace App\Livewire;

use App\Models\Bonuspotongan;
use App\Models\Karyawan;
use App\Models\Payroll;
use Carbon\Carbon;
use Livewire\Component;

class SalaryAdjustmentReport extends Component
{
    public int $month;
    public int $year;

    /**
     * Summary agregat, sudah terurut: directorate -> departemen -> adjustment_type.
     * List (bukan assoc), tiap elemen: [directorate, departemen, adjustment_type,
     * jumlah_karyawan, total_adjustment]
     */
    public array $rows = [];

    /**
     * Detail per karyawan (sebelum difilter dropdown). Tiap elemen berisi nama,
     * directorate, departemen, dan lama/baru/selisih untuk gaji pokok, gaji
     * lembur, dan bonus.
     */
    public array $employeeChanges = [];

    /** Opsi dropdown, hanya yang benar-benar ada datanya di periode ini */
    public array $directorateOptions = [];
    public array $departmentOptions = [];

    public string $filterDirectorate = '';
    public string $filterDepartment = '';

    /** Hasil filter dropdown, ini yang ditampilkan di tabel detail */
    public array $details = [];

    public function mount(): void
    {
        $this->month = (int) now()->month;
        $this->year  = (int) now()->year;

        $this->loadReport();
    }

    public function updatedMonth(): void
    {
        $this->filterDirectorate = '';
        $this->filterDepartment  = '';
        $this->loadReport();
    }

    public function updatedYear(): void
    {
        $this->filterDirectorate = '';
        $this->filterDepartment  = '';
        $this->loadReport();
    }

    public function updatedFilterDirectorate(): void
    {
        $this->filterDepartment = '';
        $this->refreshDepartmentOptions();
        $this->refreshDetails();
    }

    public function updatedFilterDepartment(): void
    {
        $this->refreshDetails();
    }

    public function loadReport(): void
    {
        $period     = Carbon::createFromDate($this->year, $this->month, 1);
        $prevPeriod = $period->copy()->subMonthNoOverflow();

        // key: id_karyawan (int) => baris gabungan gaji pokok / gaji lembur / bonus
        $merged = [];

        $this->collectGajiPokokDanLembur($merged, $prevPeriod);
        $this->collectBonus($merged);

        // Hanya simpan karyawan yang minimal salah satu komponennya > 0
        $employeeChanges = array_values(array_filter($merged, function ($row) {
            return $row['gaji_pokok_diff'] > 0
                || $row['gaji_lembur_diff'] > 0
                || $row['bonus'] > 0;
        }));

        $grouped = [];
        foreach ($employeeChanges as $row) {
            if ($row['gaji_pokok_diff'] > 0) {
                $this->pushToGroup($grouped, $row['directorate'], $row['departemen'], 'Gaji Pokok', $row['gaji_pokok_diff']);
            }
            if ($row['gaji_lembur_diff'] > 0) {
                $this->pushToGroup($grouped, $row['directorate'], $row['departemen'], 'Gaji Lembur', $row['gaji_lembur_diff']);
            }
            if ($row['bonus'] > 0) {
                $this->pushToGroup($grouped, $row['directorate'], $row['departemen'], 'Bonus', $row['bonus']);
            }
        }

        // Urutkan summary: directorate -> departemen -> adjustment_type
        usort($grouped, function ($a, $b) {
            return [$a['directorate'], $a['departemen'], $a['adjustment_type']]
                <=> [$b['directorate'], $b['departemen'], $b['adjustment_type']];
        });

        // Urutkan detail per karyawan berdasarkan nama
        usort($employeeChanges, fn ($a, $b) => strcasecmp($a['nama'], $b['nama']));

        $this->rows            = $grouped;
        $this->employeeChanges = $employeeChanges;

        // Opsi dropdown hanya dari yang benar-benar ada datanya
        $this->directorateOptions = collect($employeeChanges)
            ->pluck('directorate')
            ->unique()
            ->sort(SORT_NATURAL | SORT_FLAG_CASE)
            ->values()
            ->all();

        $this->refreshDepartmentOptions();
        $this->refreshDetails();
    }

    /**
     * Gaji Pokok & Gaji Lembur: kandidat = karyawan outsource yang tanggal_update-nya
     * di periode terpilih, dibandingkan dengan payroll bulan sebelumnya.
     */
    protected function collectGajiPokokDanLembur(array &$merged, Carbon $prevPeriod): void
    {
        $karyawans = Karyawan::query()
            ->where('outsource', 1)
            ->whereYear('tanggal_update', $this->year)
            ->whereMonth('tanggal_update', $this->month)
            ->with(['placement', 'department'])
            ->get();

        if ($karyawans->isEmpty()) {
            return;
        }

        $idKaryawanList = $karyawans->pluck('id_karyawan')->filter()->unique()->values();

        $prevPayrolls = Payroll::query()
            ->whereIn('id_karyawan', $idKaryawanList)
            ->whereYear('date', $prevPeriod->year)
            ->whereMonth('date', $prevPeriod->month)
            ->get()
            ->groupBy('id_karyawan')
            ->map(fn ($group) => $group->sortByDesc('date')->first());

        foreach ($karyawans as $k) {
            $prevPayroll = $prevPayrolls->get($k->id_karyawan);

            // Tanpa payroll bulan lalu, tidak ada baseline untuk gaji pokok/lembur
            if (!$prevPayroll) {
                continue;
            }

            $gajiPokokLama  = (int) ($prevPayroll->gaji_pokok ?? 0);
            $gajiPokokBaru  = (int) ($k->gaji_pokok ?? 0);
            $gajiLemburLama = (int) ($prevPayroll->gaji_lembur ?? 0);
            $gajiLemburBaru = (int) ($k->gaji_overtime ?? 0);

            $row = $this->emptyRow($k);
            $row['gaji_pokok_lama']  = $gajiPokokLama;
            $row['gaji_pokok_baru']  = $gajiPokokBaru;
            $row['gaji_pokok_diff']  = $gajiPokokBaru - $gajiPokokLama;
            $row['gaji_lembur_lama'] = $gajiLemburLama;
            $row['gaji_lembur_baru'] = $gajiLemburBaru;
            $row['gaji_lembur_diff'] = $gajiLemburBaru - $gajiLemburLama;

            $merged[$k->id_karyawan] = array_merge($merged[$k->id_karyawan] ?? [], $row);
        }
    }

    /**
     * Bonus: langsung diambil dari bonuspotongans.bonus_lain pada periode terpilih,
     * TIDAK dibandingkan dengan payroll. Dimasukkan begitu saja kalau ada (> 0).
     */
    protected function collectBonus(array &$merged): void
    {
        $bonusRows = Bonuspotongan::query()
            ->whereYear('tanggal', $this->year)
            ->whereMonth('tanggal', $this->month)
            ->whereNotNull('bonus_lain')
            ->where('bonus_lain', '>', 0)
            ->whereHas('karyawan', fn ($q) => $q->where('outsource', 1))
            ->with(['karyawan.placement', 'karyawan.department'])
            ->get();

        foreach ($bonusRows as $bp) {
            $k = $bp->karyawan;

            // Jaga-jaga kalau relasi ternyata null (data tidak konsisten)
            if (!$k) {
                continue;
            }

            $existing = $merged[$k->id_karyawan] ?? $this->emptyRow($k);
            $existing['bonus'] = (int) $bp->bonus_lain;

            $merged[$k->id_karyawan] = $existing;
        }
    }

    protected function emptyRow(Karyawan $k): array
    {
        return [
            'id_karyawan'      => $k->id_karyawan,
            'nama'             => $k->nama,
            'directorate'      => optional($k->placement)->placement_name ?? '-',
            'departemen'       => optional($k->department)->nama_department ?? '-',
            'gaji_pokok_lama'  => 0,
            'gaji_pokok_baru'  => 0,
            'gaji_pokok_diff'  => 0,
            'gaji_lembur_lama' => 0,
            'gaji_lembur_baru' => 0,
            'gaji_lembur_diff' => 0,
            'bonus'            => 0,
        ];
    }

    protected function refreshDepartmentOptions(): void
    {
        $this->departmentOptions = collect($this->employeeChanges)
            ->when($this->filterDirectorate !== '', fn ($c) => $c->where('directorate', $this->filterDirectorate))
            ->pluck('departemen')
            ->unique()
            ->sort(SORT_NATURAL | SORT_FLAG_CASE)
            ->values()
            ->all();

        if ($this->filterDepartment !== '' && !in_array($this->filterDepartment, $this->departmentOptions, true)) {
            $this->filterDepartment = '';
        }
    }

    protected function refreshDetails(): void
    {
        $this->details = collect($this->employeeChanges)
            ->when($this->filterDirectorate !== '', fn ($c) => $c->where('directorate', $this->filterDirectorate))
            ->when($this->filterDepartment !== '', fn ($c) => $c->where('departemen', $this->filterDepartment))
            ->values()
            ->all();
    }

    protected function pushToGroup(
        array &$grouped,
        string $directorate,
        string $departemen,
        string $type,
        int $amount
    ): void {
        $key = "{$directorate}|{$departemen}|{$type}";

        if (!isset($grouped[$key])) {
            $grouped[$key] = [
                'directorate'      => $directorate,
                'departemen'       => $departemen,
                'adjustment_type'  => $type,
                'jumlah_karyawan'  => 0,
                'total_adjustment' => 0,
            ];
        }

        $grouped[$key]['jumlah_karyawan']  += 1;
        $grouped[$key]['total_adjustment'] += $amount;
    }

    public function render()
    {
        return view('livewire.salary-adjustment-report');
    }
}
