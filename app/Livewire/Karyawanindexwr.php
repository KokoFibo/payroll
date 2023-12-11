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

class Karyawanindexwr extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search;
    public $columnName = 'id_karyawan';
    public $direction = 'Asc';
    public $id;
    public $selectedAll = [];
    public $selectStatus = 'Aktif';
    public $perpage = 10;
    public $selected_company;

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
  

    public function mount()
    {
        $this->selected_company = 0;
        $this->selectStatus = 1;
        // $this->jabatans = Karyawan::select('jabatan')->distinct()->orderBy('jabatan', 'asc')->get();
        // $this->departments = Karyawan::select('departemen')->distinct()->orderBy('departemen', 'asc')->get();
    }

    public function reset_filter () {
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
        $this->direction = 'Asc';

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
        switch($this->selected_company) {

            case '0' : 
                $nama_file="semua_karyawan.xlsx";
                 break;
            case '1' : 
                $nama_file="Karyawan_Pabrik-1.xlsx";
                break;
            case '2' : 
                $nama_file="Karyawan_Pabrik-2.xlsx";
                break;
            case '3' : 
                $nama_file="Karyawan_Kantor.xlsx";
                break;
            case '4' : 
                $nama_file="Karyawan_ASB.xlsx";
                break;
            case '5' : 
                $nama_file="Karyawan_DPA.xlsx";
                break;
            case '6' : 
                $nama_file="Karyawan_YCME.xlsx";
                break;
            case '7' : 
                $nama_file="Karyawan_YEV.xlsx";
                break;
            case '8' : 
                $nama_file="Karyawan_YIG.xlsx";
                break;
            case '9' : 
                $nama_file="Karyawan_YSM.xlsx";
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
        

        // switch ($this->selected_company) {
        //     case 0:
        //         $datas = $this->getPayrollQuery($statuses, $this->search)
        //         ->paginate($this->perpage);
        //         break;

        //     case 1:
                
        //             $datas = $this->getPayrollQuery($statuses, $this->search, 'YCME')
        //             ->paginate($this->perpage);
        //         break;

        //     case 2:
        //             $datas = $this->getPayrollQuery($statuses, $this->search, 'YEV')
        //             ->paginate($this->perpage);
        //         break;

        //     case 3:
        //         $datas = Karyawan::query()
        //                 ->whereIn('status_karyawan', $statuses)
        //                 ->when($this->search, function ($query) {
        //                     $query
        //                         ->where('id_karyawan', 'LIKE', '%' . trim($this->search) . '%')
        //                         ->orWhere('nama', 'LIKE', '%' . trim($this->search) . '%')
        //                         ->orWhere('jabatan', 'LIKE', '%' . trim($this->search) . '%')
        //                         ->orWhere('company', 'LIKE', '%' . trim($this->search) . '%')
        //                         ->orWhere('metode_penggajian', 'LIKE', '%' . trim($this->search) . '%');
        //                 })
        //                 ->whereIn('placement', ['YIG', 'YSM'])
        //             ->orderBy($this->columnName, $this->direction)
        //             ->paginate($this->perpage);
        //         break;
                

        //     case 4:
        //         $datas = $this->getPayrollQuery($statuses, $this->search, '', 'ASB')
        //         ->where('company', 'ASB')
        //             ->paginate($this->perpage);
        //         break;

        //     case 5:
        //         $datas = $this->getPayrollQuery($statuses, $this->search, '', 'DPA')
        //         ->where('company', 'DPA')
        //             ->paginate($this->perpage);
        //         break;

        //     case 6:
        //         $datas = $this->getPayrollQuery($statuses, $this->search, '', 'YCME')
        //         ->where('company', 'YCME')
        //             ->paginate($this->perpage);
        //         break;

        //     case 7:
        //         $datas = $this->getPayrollQuery($statuses, $this->search, '', 'YEV')
        //         ->where('company', 'YEV')
        //             ->paginate($this->perpage);
        //         break;

        //     case 8:
        //         $datas = $this->getPayrollQuery($statuses, $this->search, '', 'YIG')
        //         ->where('company', 'YIG')
        //             ->paginate($this->perpage);
        //         break;

        //     case 9:
        //         $datas = $this->getPayrollQuery($statuses, $this->search, '', 'YSM')
        //         ->where('company', 'YSM')
        //             ->paginate($this->perpage);
        //         break;
        // }
        $datas = Karyawan::query()
        // ->orderBy('nama', $this->direction)
        ->whereIn('status_karyawan', $statuses)
        ->where('nama', 'LIKE', '%' . trim($this->search_nama) . '%')
        ->when($this->search_id_karyawan, function ($query) {
            $query->where('id_karyawan', trim($this->search_id_karyawan))
            ->orderBy('nama', 'asc' );
        })
        
        ->when($this->search_company, function ($query) {
            $query->where('company', $this->search_company)->orderBy('nama', 'asc' );
        })
       
        ->when($this->search_placement, function ($query) {
            if($this->search_placement == 1){
                $query->where('placement', 'YCME')
                ->orderBy('nama', 'asc' );
            } elseif($this->search_placement == 2){
                $query->where('placement', 'YEV')
                ->orderBy('nama', 'asc' );
            } else {
                $query->whereIn('placement', ['YIG', 'YSM'])
                ->orderBy('nama', 'asc' );
            }
        })

        


        ->when($this->search_jabatan, function ($query) {
            $query->where('jabatan', $this->search_jabatan)
            ->orderBy('nama', 'asc' );
        })
        ->when($this->search_department, function ($query) {
            $query->where('departemen', $this->search_department)
            ->orderBy('nama', 'asc' );
        })
        ->when($this->search_tanggal_bergabung, function ($query) {
            $query->orderBy('tanggal_bergabung', $this->search_tanggal_bergabung );
        })
        ->when($this->search_gaji_pokok, function ($query) {
            $query->orderBy('gaji_pokok', $this->search_gaji_pokok );
        })
        ->when($this->search_gaji_overtime, function ($query) {
            $query->orderBy('gaji_overtime', $this->search_gaji_overtime );
        })
        ->when($this->search_tanggal_bergabung == "", function ($query) {
            $query->orderBy('id_karyawan', 'desc');
        })
        ->paginate($this->perpage);
        return view('livewire.karyawanindexwr', compact(['datas', 'departments', 'jabatans']));
    }
}
