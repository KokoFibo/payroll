<?php

namespace App\Livewire;

use App\Exports\BankReportExcel;
use App\Exports\ExcelDetailReport;
use App\Exports\PayrollExportFLexible;
use App\Jobs\rebuildJob;
use App\Models\Department;
use App\Models\Jamkerjaid;
use App\Models\Karyawan;
use App\Models\Lock;
use App\Models\Payroll;
use App\Models\Tambahan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Payrollwr extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // ==================================================
    // PROPERTIES
    // ==================================================

    public $selected_company = 0;
    public $selected_placement = 0;
    public $selected_departemen = 0;

    public $search = '';
    public $perpage = 10;

    public $month;
    public $year;

    public $columnName = 'id_karyawan';
    public $direction = 'asc';
    public $status = 1;

    public $data_payroll;
    public $data_karyawan;

    public $lock_presensi = 0;
    public $lock_slip_gaji = 0;
    public $lock_data = 0;

    public $select_month = [];
    public $select_year = [];

    public $payroll_data;
    public $total_data;
    public $cx = 0;

    public $companies = [];
    public $departments = [];
    public $placements = [];

    // ==================================================
    // INITIALIZATION
    // ==================================================

    public function mount()
    {
        $this->initializePeriod();
        $this->loadSelectMonthYear();
        $this->loadFilters();
        $this->loadInitialPayrollDetail();
        $this->loadLockStatus();
    }

    private function initializePeriod(): void
    {
        $now = now();

        if ($now->day < 5) {
            $period = $now->copy()->subMonth();
        } else {
            $latestPayroll = Payroll::orderByDesc('date')->first();

            $period = $latestPayroll
                ? Carbon::parse($latestPayroll->date)
                : $now;
        }

        $this->year = $period->year;
        $this->month = $period->month;
    }

    private function loadInitialPayrollDetail(): void
    {
        $payroll = Payroll::query()
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->orderByDesc('date')
            ->first();

        if (!$payroll) {
            $this->data_payroll = null;
            $this->data_karyawan = null;

            return;
        }

        $this->showDetail($payroll->id_karyawan);
    }

    private function loadLockStatus(): void
    {
        $lock = Lock::find(1);

        $this->lock_presensi = $lock?->presensi ?? 0;
        $this->lock_slip_gaji = $lock?->slip_gaji ?? 0;
        $this->lock_data = $lock?->data ?? 0;
    }

    // ==================================================
    // FILTER OPTIONS
    // Placement -> Company -> Department
    // ==================================================

    public function loadFilters(): void
    {
        $periodQuery = fn() => Payroll::query()
            ->whereMonth('payrolls.date', $this->month)
            ->whereYear('payrolls.date', $this->year);

        // 1. PLACEMENTS
        $this->placements = $periodQuery()
            ->join(
                'placements',
                'placements.id',
                '=',
                'payrolls.placement_id'
            )
            ->select(
                'placements.id',
                'placements.placement_name'
            )
            ->distinct()
            ->orderBy('placements.placement_name')
            ->get();

        // 2. COMPANIES: mengikuti placement
        $companiesQuery = $periodQuery()
            ->join(
                'companies',
                'companies.id',
                '=',
                'payrolls.company_id'
            )
            ->when(
                $this->selected_placement != 0,
                fn($query) => $query->where(
                    'payrolls.placement_id',
                    $this->selected_placement
                )
            );

        $this->companies = $companiesQuery
            ->select(
                'companies.id',
                'companies.company_name'
            )
            ->distinct()
            ->orderBy('companies.company_name')
            ->get();

        // 3. DEPARTMENTS: mengikuti placement dan company
        $departmentsQuery = $periodQuery()
            ->join(
                'departments',
                'departments.id',
                '=',
                'payrolls.department_id'
            )
            ->when(
                $this->selected_placement != 0,
                fn($query) => $query->where(
                    'payrolls.placement_id',
                    $this->selected_placement
                )
            )
            ->when(
                $this->selected_company != 0,
                fn($query) => $query->where(
                    'payrolls.company_id',
                    $this->selected_company
                )
            );

        $this->departments = $departmentsQuery
            ->select(
                'departments.id',
                'departments.nama_department'
            )
            ->distinct()
            ->orderBy('departments.nama_department')
            ->get();
    }

    public function updatedSelectedPlacement()
    {
        dd('placement');
        $this->selected_company = 0;
        $this->selected_departemen = 0;
        $this->resetPage();
        $this->loadFilters();
    }

    public function updatedSelectedCompany(): void
    {
        $this->selected_departemen = 0;

        $this->resetPage();
        $this->loadFilters();
    }

    public function updatedSelectedDepartemen(): void
    {
        $this->resetPage();
        $this->loadFilters();
    }

    // ==================================================
    // PERIOD / MONTH / YEAR
    // ==================================================

    public function loadSelectMonthYear(): void
    {
        $this->select_year = Payroll::query()
            ->selectRaw('YEAR(date) as year')
            ->whereNotNull('date')
            ->distinct()
            ->pluck('year')
            ->push(now()->year)
            ->unique()
            ->sortDesc()
            ->values()
            ->toArray();

        $year = (int) ($this->year ?: now()->year);

        $months = Payroll::query()
            ->selectRaw('MONTH(date) as month')
            ->whereYear('date', $year)
            ->whereNotNull('date')
            ->distinct()
            ->pluck('month');

        // Tampilkan bulan berjalan sebagai opsi
        // untuk tahun berjalan.
        if ($year === (int) now()->year) {
            $months->push(now()->month);
        }

        $this->select_month = $months
            ->filter(fn($month) => $month >= 1 && $month <= 12)
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        if (
            empty($this->month) ||
            !in_array((int) $this->month, $this->select_month)
        ) {
            $this->month = $this->select_month
                ? (int) end($this->select_month)
                : now()->month;
        }
    }

    public function updatedYear(): void
    {
        $this->month = null;

        $this->loadSelectMonthYear();

        $this->resetDependentFilters();
        $this->resetPage();
        $this->loadFilters();
    }

    public function updatedMonth(): void
    {
        $this->resetDependentFilters();
        $this->resetPage();
        $this->loadFilters();
    }

    private function resetDependentFilters(): void
    {
        $this->selected_placement = 0;
        $this->selected_company = 0;
        $this->selected_departemen = 0;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPerpage(): void
    {
        $this->resetPage();
    }

    // ==================================================
    // PAYROLL QUERY
    // ==================================================

    private function getStatuses(): array
    {
        return match ((int) $this->status) {
            1 => ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'],
            2 => ['Blacklist'],
            default => [
                'PKWT',
                'PKWTT',
                'Dirumahkan',
                'Resigned',
                'Blacklist',
            ],
        };
    }

    private function applyCommonFilters($query)
    {
        return $query
            ->whereIn(
                'payrolls.status_karyawan',
                $this->getStatuses()
            )
            ->whereMonth('payrolls.date', $this->month)
            ->whereYear('payrolls.date', $this->year)
            ->when(
                $this->selected_placement != 0,
                fn($q) => $q->where(
                    'payrolls.placement_id',
                    $this->selected_placement
                )
            )
            ->when(
                $this->selected_company != 0,
                fn($q) => $q->where(
                    'payrolls.company_id',
                    $this->selected_company
                )
            )
            ->when(
                $this->selected_departemen != 0,
                fn($q) => $q->where(
                    'payrolls.department_id',
                    $this->selected_departemen
                )
            );
    }

    public function getPayrollQuery(
        $statuses,
        $search = null,
        $placement_id = null,
        $company_id = null,
        $department_id = null
    ) {
        return Payroll::query()
            ->whereIn('payrolls.status_karyawan', $statuses)
            ->when($search, function ($query) use ($search) {
                $term = trim($search);

                // Kelompokkan OR agar tidak melewati
                // filter tanggal, status, atau lokasi.
                $query->where(function ($q) use ($term) {
                    $q->where(
                        'payrolls.id_karyawan',
                        'LIKE',
                        '%' . $term . '%'
                    )
                        ->orWhere(
                            'payrolls.nama',
                            'LIKE',
                            '%' . $term . '%'
                        )
                        ->orWhere(
                            'payrolls.jabatan_id',
                            'LIKE',
                            '%' . $term . '%'
                        )
                        ->orWhere(
                            'payrolls.company_id',
                            'LIKE',
                            '%' . $term . '%'
                        )
                        ->orWhere(
                            'payrolls.department_id',
                            'LIKE',
                            '%' . $term . '%'
                        )
                        ->orWhere(
                            'payrolls.metode_penggajian',
                            'LIKE',
                            '%' . $term . '%'
                        )
                        ->orWhere(
                            'payrolls.status_karyawan',
                            'LIKE',
                            '%' . $term . '%'
                        );
                });
            })
            ->when(
                $placement_id,
                fn($q) => $q->where(
                    'payrolls.placement_id',
                    $placement_id
                )
            )
            ->when(
                $company_id,
                fn($q) => $q->where(
                    'payrolls.company_id',
                    $company_id
                )
            )
            ->when(
                $department_id,
                fn($q) => $q->where(
                    'payrolls.department_id',
                    $department_id
                )
            )
            ->orderBy($this->columnName, $this->direction);
    }

    // ==================================================
    // SORTING
    // ==================================================

    public function sortColumnName($namaKolom): void
    {
        // Sesuaikan daftar ini jika ada kolom sortable lain.
        $allowedColumns = [
            'id_karyawan',
            'nama',
            'total',
            'company_id',
            'placement_id',
            'department_id',
            'status_karyawan',
            'jabatan_id',
            'metode_penggajian',
            'date',
        ];

        if (!in_array($namaKolom, $allowedColumns, true)) {
            return;
        }

        if ($this->columnName === $namaKolom) {
            $this->direction = $this->direction === 'asc'
                ? 'desc'
                : 'asc';
        } else {
            $this->columnName = $namaKolom;
            $this->direction = 'asc';
        }

        $this->resetPage();
    }

    public function swapDirection(): string
    {
        return $this->direction === 'asc' ? 'desc' : 'asc';
    }

    // ==================================================
    // PAYROLL DETAIL
    // ==================================================

    public function showDetail($id_karyawan): void
    {
        $this->data_payroll = Payroll::with('jamkerjaid')
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->where('id_karyawan', $id_karyawan)
            ->first();

        $this->data_karyawan = Karyawan::where(
            'id_karyawan',
            $id_karyawan
        )->first();
    }

    // ==================================================
    // EXCEL EXPORT
    // ==================================================

    public function excelDetailReport()
    {
        $filename = 'OS Detail Report '
            . nama_bulan($this->month)
            . ' '
            . $this->year
            . '.xlsx';

        return Excel::download(
            new ExcelDetailReport($this->month, $this->year),
            $filename
        );
    }

    public function export()
    {
        $placement = $this->selected_placement != 0
            ? 'directorate-' . nama_placement(
                $this->selected_placement
            ) . '-'
            : '';

        $company = $this->selected_company != 0
            ? 'company-' . nama_company(
                $this->selected_company
            ) . '-'
            : '';

        $department = $this->selected_departemen != 0
            ? 'department-' . nama_department(
                $this->selected_departemen
            ) . '-'
            : '';

        $prefix = $placement . $company . $department;

        if ($prefix === '') {
            $filename = 'Payroll-all-'
                . monthName($this->month)
                . '-'
                . $this->year
                . '.xlsx';
        } else {
            $filename = 'Payroll-'
                . $prefix
                . monthName($this->month)
                . '-'
                . $this->year
                . '.xlsx';
        }

        if (filled($this->search)) {
            $filename = 'Payroll-'
                . $prefix
                . 'search-'
                . $this->search
                . '-'
                . monthName($this->month)
                . '-'
                . $this->year
                . '.xlsx';
        }

        return Excel::download(
            new PayrollExportFLexible(
                $this->columnName,
                $this->direction,
                $this->search,
                $this->selected_company,
                $this->selected_placement,
                $this->selected_departemen,
                $this->status,
                $this->month,
                $this->year
            ),
            $filename
        );
    }

    public function bankexcel()
    {
        if ($this->selected_company != 0) {
            $filename = nama_company($this->selected_company)
                . '_Company_Bank.xlsx';
        } elseif ($this->selected_placement != 0) {
            $filename = nama_placement($this->selected_placement)
                . '_Directorate_Bank.xlsx';
        } elseif ($this->selected_departemen != 0) {
            $filename = sambungKata($this->selected_departemen)
                . '_Department_Bank.xlsx';
        } else {
            $filename = 'semua_karyawan_Bank.xlsx';
        }

        $filename = nama_file_excel(
            $filename,
            $this->month,
            $this->year
        );

        return Excel::download(
            new BankReportExcel(
                $this->status,
                $this->month,
                $this->year,
                $this->selected_company,
                $this->selected_placement,
                $this->selected_departemen
            ),
            $filename
        );
    }

    // ==================================================
    // PAYROLL BUILD / REBUILD
    // ==================================================

    public function rebuildOptimized(): void
    {
        quickRebuildOptimized($this->month, $this->year);

        $this->dispatch('$refresh');

        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data berhasil di build'
        );
    }

    public function newRebuild(): void
    {
        quickRebuild($this->month, $this->year);

        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data berhasil di build'
        );
    }

    public function buat_payroll($queue): void
    {
        if (check_absensi_kosong()) {
            clear_locks();

            $this->dispatch(
                'message',
                type: 'error',
                title: 'Masih ada data kosong di presensi'
            );

            return;
        }

        $lock = Lock::find(1);

        if (!$lock) {
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Data lock belum tersedia'
            );

            return;
        }

        if ($lock->build == 1) {
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Mohon dicoba sebentar lagi'
            );

            return;
        }

        $lock->build = 1;
        $lock->save();

        $startTime = microtime(true);

        try {
            if ($queue === 'noQueue') {
                $result = build_payroll_os(
                    $this->month,
                    $this->year
                );
            } else {
                $lock->rebuild_done = 2;
                $lock->save();

                dispatch(new rebuildJob(
                    $this->month,
                    $this->year
                ));

                $result = 1;
            }

            if ($result == 0) {
                $this->dispatch(
                    'message',
                    type: 'error',
                    title: 'Data Presensi tidak ada'
                );
            } elseif ($queue === 'noQueue') {
                $elapsed = number_format(
                    microtime(true) - $startTime,
                    2
                );

                $this->dispatch(
                    'message',
                    type: 'success',
                    title: "Data Payroll Karyawan Sudah di Build ({$elapsed} seconds)"
                );
            }
        } finally {
            // Jika memakai queue, status build perlu tetap aktif
            // sampai job selesai agar tidak terjadi build bersamaan.
            if ($queue === 'noQueue') {
                $lock->build = 0;
                $lock->save();
            }
        }

        $this->loadFilters();
        $this->loadLockStatus();
    }

    public function close_succesful_rebuilt(): void
    {
        $lock = Lock::find(1);

        if (!$lock) {
            return;
        }

        $lock->rebuild_done = false;
        $lock->save();
    }

    public function clear_lock(): void
    {
        clear_locks();
        delete_failed_jobs();

        $this->loadLockStatus();
    }

    // ==================================================
    // LOCK STATUS
    // ==================================================

    public function updatedLockPresensi(): void
    {
        $this->saveLockValue('presensi', $this->lock_presensi);
    }

    public function updatedLockSlipGaji(): void
    {
        $this->saveLockValue('slip_gaji', $this->lock_slip_gaji);
    }

    public function updatedLockData(): void
    {
        $this->saveLockValue('data', $this->lock_data);
    }

    private function saveLockValue(string $column, $value): void
    {
        $lock = Lock::find(1);

        if (!$lock) {
            return;
        }

        $allowedColumns = [
            'presensi',
            'slip_gaji',
            'data',
        ];

        if (!in_array($column, $allowedColumns, true)) {
            return;
        }

        $lock->{$column} = $value;
        $lock->save();
    }

    // ==================================================
    // BONUS / POTONGAN
    // ==================================================

    public function bonus_potongan(): void
    {
        DB::transaction(function () {
            $tambahan = Tambahan::query()
                ->whereMonth('tanggal', $this->month)
                ->whereYear('tanggal', $this->year)
                ->get();

            foreach ($tambahan as $item) {
                $bonus = (float) $item->uang_makan
                    + (float) $item->bonus_lain;

                $potongan = (float) $item->baju_esd
                    + (float) $item->gelas
                    + (float) $item->sandal
                    + (float) $item->seragam
                    + (float) $item->sport_bra
                    + (float) $item->hijab_instan
                    + (float) $item->id_card_hilang
                    + (float) $item->masker_hijau
                    + (float) $item->potongan_lain;

                $payroll = Payroll::query()
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->where('id_karyawan', $item->user_id)
                    ->first();

                if (!$payroll) {
                    continue;
                }

                $payroll->bonus1x =
                    (float) $payroll->bonus1x + $bonus;

                $payroll->potongan1x =
                    (float) $payroll->potongan1x + $potongan;

                $payroll->total =
                    (float) $payroll->total + $bonus - $potongan;

                $payroll->save();
            }
        });

        $this->dispatch(
            'message',
            type: 'success',
            title: 'Bonus dan Potongan berhasil ditambahkan'
        );
    }

    // ==================================================
    // RENDER
    // ==================================================

    public function render()
    {
        $statuses = $this->getStatuses();

        $baseQuery = $this->applyCommonFilters(
            Payroll::query()
        );

        $total = (clone $baseQuery)->sum('payrolls.total');

        $payroll = $this->applyCommonFilters(
            $this->getPayrollQuery(
                $statuses,
                $this->search
            )
        )
            ->orderBy($this->columnName, $this->direction)
            ->paginate($this->perpage);

        $createdAt = Payroll::query()
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->orderByDesc('created_at')
            ->value('created_at');

        $last_build = $createdAt
            ? Carbon::parse($createdAt)->diffForHumans()
            : 0;

        $data_kosong = Jamkerjaid::count();

        return view('livewire.payrollwr', [
            'payroll' => $payroll,
            'total' => $total,
            'last_build' => $last_build,
            'data_kosong' => $data_kosong,
        ]);
    }
}
