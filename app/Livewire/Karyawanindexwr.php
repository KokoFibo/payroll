<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Karyawan;
use Livewire\Attributes\On;
use Livewire\WithPagination;
// use Illuminate\Support\Facades\DB;
use App\Exports\KaryawanExport;
use App\Exports\DataPelitaExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Query\Builder;
use App\Exports\KaryawanByDepartmentExport;

class Karyawanindexwr extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $month, $year;
    public $search;
    public $columnName = 'id_karyawan';
    public $direction = 'desc';
    public $id;
    public $selectedAll = [];
    public $selectStatus = 'Aktif';
    public $perpage = 10;
    public $selected_company;
    public $cx = 0;

    // variable untuk filter
    public $search_id_karyawan;
    public $search_nama;
    public $search_company;
    public $search_placement;
    public $search_department;
    public $search_jabatan;
    public $search_status;
    public $search_tanggal_bergabung;
    public $search_gaji_pokok;
    public $search_gaji_overtime;

    public function updatedSearchTanggalBergabung()
    {
        $this->columnName = 'tanggal_bergabung';
        $this->direction = $this->search_tanggal_bergabung;
    }
    public function updatedSearchGajiPokok()
    {
        $this->columnName = 'gaji_pokok';
        $this->direction = $this->search_gaji_pokok;
    }
    public function updatedSearchGajiOvertime()
    {
        $this->columnName = 'gaji_overtime';
        $this->direction = $this->search_gaji_overtime;
    }

    public function excelByDepartment()
    {
        $nama_file = "";
        switch ($this->search_placement) {

            case '1':
                $nama_file = "pabrik_1_" . $this->search_department . "_" . $this->month . "_" . $this->year . ".xlsx";
                break;
            case '2':
                $nama_file = "pabrik_2_" . $this->search_department . "_" . $this->month . "_" . $this->year . ".xlsx";
                break;
            case '3':
                $nama_file = "kantor_" . $this->search_department . "_" . $this->month . "_" . $this->year . ".xlsx";
                break;
        }
        // dd($nama_file);
        return Excel::download(new KaryawanByDepartmentExport($this->search_placement, $this->search_department), $nama_file);
    }
    public function mount()
    {
        $this->year = now()->year;
        $this->month = now()->month;
        $this->selected_company = 0;
        $this->selectStatus = 1;
        // $this->jabatans = Karyawan::select('jabatan')->distinct()->orderBy('jabatan', 'asc')->get();
        // $this->departments = Karyawan::select('departemen')->distinct()->orderBy('departemen', 'asc')->get();
        $this->columnName = 'id_karyawan';
        $this->direction = 'desc';
    }

    public function reset_filter()
    {
        $this->selectStatus = 1;
        $this->search_nama = "";
        $this->search_id_karyawan = "";
        $this->search_company = "";
        $this->search_placement = "";
        $this->search_jabatan = "";
        $this->search_department = "";
        $this->search_tanggal_bergabung = "";
        $this->search_gaji_pokok = "";
        $this->search_gaji_overtime = "";
        $this->columnName = 'id_karyawan';
        $this->direction = 'desc';
    }

    // #[On('delete')]
    public function delete($id)
    {

        $Data_Karyawan = Karyawan::find($id);
        $dataUser = User::where('username', $Data_Karyawan->id_karyawan)->first();
        if ($dataUser->id) {
            $user = User::find($dataUser->id);
            $user->delete();
            $Data_Karyawan->delete();
            $this->dispatch('success', message: 'Data Karyawan Sudah di delete');
        } else {
            $this->dispatch('info', message: 'Data Karyawan Sudah di Delete, User tidak terdelete');
        }
    }

    public function no_edit()
    {
        $this->dispatch('error', message: 'Data Karyawan ini Tidak dapat di Update');
    }

    public function confirmDelete($id)
    {
        $this->id = $id;
        $this->dispatch('swal:confirm', [
            'title' => 'Apakah Anda Yakin',
            'text' => 'isi text dengan apa?',
            'id' => $id,
        ]);
    }

    public function sortColumnName($namaKolom)
    {
        $this->columnName = $namaKolom;
        $this->direction = $this->swapDirection();
    }
    public function swapDirection()
    {
        return $this->direction === 'asc' ? 'desc' : 'asc';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function excel()
    {
        $nama_file = "";
        switch ($this->selected_company) {

            case '0':
                $nama_file = "semua_karyawan.xlsx";
                break;
            case '1':
                $nama_file = "Karyawan_Pabrik-1.xlsx";
                break;
            case '2':
                $nama_file = "Karyawan_Pabrik-2.xlsx";
                break;
            case '3':
                $nama_file = "Karyawan_Kantor.xlsx";
                break;
            case '4':
                $nama_file = "Karyawan_ASB.xlsx";
                break;
            case '5':
                $nama_file = "Karyawan_DPA.xlsx";
                break;
            case '6':
                $nama_file = "Karyawan_YCME.xlsx";
                break;
            case '7':
                $nama_file = "Karyawan_YEV.xlsx";
                break;
            case '8':
                $nama_file = "Karyawan_YIG.xlsx";
                break;
            case '9':
                $nama_file = "Karyawan_YSM.xlsx";
                break;
        }
        return Excel::download(new karyawanExport($this->selected_company, $this->selectStatus), $nama_file);
    }

    public function getPayrollQuery($statuses, $search = null, $placement = null, $company = null)
    {
        return Karyawan::query()
            ->whereIn('status_karyawan', $statuses)
            ->when($search, function ($query) use ($search) {
                $query

                    // ->where('nama', 'LIKE', $search)
                    // ->orWhere('company', 'LIKE', $search)
                    // ->orWhere('id_karyawan', 'LIKE', $search)
                    // ->orWhere('departemen', 'LIKE', $search)
                    // ->orWhere('placement', 'LIKE', $search)
                    // ->orWhere('company', 'LIKE', $search)
                    // ->orWhere('jabatan', 'LIKE', $search);

                    ->where('id_karyawan', 'LIKE', '%' . trim($this->search) . '%')
                    ->orWhere('nama', 'LIKE', '%' . trim($this->search) . '%')
                    ->orWhere('jabatan', 'LIKE', '%' . trim($this->search) . '%')
                    ->orWhere('company', 'LIKE', '%' . trim($this->search) . '%')
                    ->orWhere('metode_penggajian', 'LIKE', '%' . trim($this->search) . '%');
            })
            ->when($placement, function ($query) use ($placement) {
                $query->where('placement', $placement);
            })
            ->when($company, function ($query) use ($company) {
                $query->where('company', $company);
            })

            ->orderBy($this->columnName, $this->direction);
    }


    public function render()
    {
        if ($this->selectStatus == 1) {
            $statuses = ['PKWT', 'PKWTT', 'Dirumahkan'];
        } elseif ($this->selectStatus == 2) {
            $statuses = ['Blacklist', 'Resigned'];
        } else {
            $statuses = ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned', 'Blacklist'];
        }

        $jabatans = Karyawan::select('jabatan')->distinct()->orderBy('jabatan', 'asc')->get();
        $departments = Karyawan::select('departemen')->distinct()->orderBy('departemen', 'asc')->get();


        $datas = Karyawan::query()
            // ->orderBy('nama', $this->direction)
            ->whereIn('status_karyawan', $statuses)
            ->where('nama', 'LIKE', '%' . trim($this->search_nama) . '%')
            ->when($this->search_id_karyawan, function ($query) {
                $query->where('id_karyawan', trim($this->search_id_karyawan));
            })

            ->when($this->search_company, function ($query) {
                $query->where('company', $this->search_company);
            })

            ->when($this->search_placement, function ($query) {
                if ($this->search_placement == 1) {
                    $query->where('placement', 'YCME');
                } elseif ($this->search_placement == 2) {
                    $query->where('placement', 'YEV');
                } else {
                    $query->whereIn('placement', ['YIG', 'YSM']);
                }
            })
            ->when($this->search_jabatan, function ($query) {
                $query->where('jabatan', $this->search_jabatan);
            })
            ->when($this->search_department, function ($query) {
                $query->where('departemen', $this->search_department);
            })
            // ->when($this->search_tanggal_bergabung, function ($query) {
            //     $this->cx++;
            //     $query->orderBy('tanggal_bergabung', $this->search_tanggal_bergabung);
            // })
            // ->when($this->search_gaji_pokok, function ($query) {
            //     $query->orderBy('gaji_pokok', $this->search_gaji_pokok);
            // })
            // ->when($this->search_gaji_overtime, function ($query) {
            //     $query->orderBy('gaji_overtime', $this->search_gaji_overtime);
            // })
            // ->when($this->search_tanggal_bergabung == "", function ($query) {
            //     $query->orderBy('id_karyawan', 'desc');
            // })
            // ->orderBy('id_karyawan', 'desc')
            // ->orderBy('tanggal_bergabung', $this->search_tanggal_bergabung)
            // ->orderBy('gaji_pokok', $this->search_gaji_pokok)
            // ->orderBy('gaji_overtime', $this->search_gaji_overtime)
            ->orderBy($this->columnName, $this->direction)




            ->paginate($this->perpage);
        $this->cx++;
        return view('livewire.karyawanindexwr', compact(['datas', 'departments', 'jabatans']));
    }
}
