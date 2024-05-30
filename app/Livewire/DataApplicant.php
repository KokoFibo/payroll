<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Karyawan;
use Livewire\WithPagination;
use App\Models\Applicantdata;
use App\Models\Applicantfile;
use Illuminate\Support\Facades\Hash;

class DataApplicant extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $show_data, $show_table, $personal_data, $personal_files;

    public function diterima($id)
    {

        $dataApplicant = Applicantdata::find($id);
        // ambil ID karyawan
        $id_karyawan_terbaru = getNextIdKaryawan();

        // data yg mau di entry ke karyawan


        Karyawan::create([
            'id_karyawan' => $id_karyawan_terbaru,
            'nama' => $dataApplicant->nama,
            'email' => $dataApplicant->email,
            'hp' => $dataApplicant->hp,
            'telepon' => $dataApplicant->telp,
            'tempat_lahir' => $dataApplicant->tempat_lahir,
            'tanggal_lahir' => $dataApplicant->tgl_lahir,
            'gender' => $dataApplicant->gender,
            'status_pernikahan' => $dataApplicant->status_pernikahan,
            'golongan_darah' => $dataApplicant->golongan_darah,
            'agama' => $dataApplicant->agama,
            'etnis' => $dataApplicant->etnis,
            'kontak_darurat' => $dataApplicant->nama_contact_darurat,
            'hp1' => $dataApplicant->contact_darurat_1,
            'hp2' => $dataApplicant->contact_darurat_2,
            'jenis_identitas' => $dataApplicant->jenis_identitas,
            'no_identitas' => $dataApplicant->no_identitas,
            'alamat_identitas' => $dataApplicant->alamat_identitas,
            'alamat_tinggal' => $dataApplicant->alamat_tinggal_sekarang,
            'id_file_karyawan' => $dataApplicant->applicant_id,
            'status_karyawan' => 'PKWT',
            'tanggal_bergabung' => Carbon::now()->toDateString()
        ]);

        User::create([
            'name' => titleCase($dataApplicant->nama),
            'email' => trim($dataApplicant->email, ' '),
            'username' => $id_karyawan_terbaru,
            'role' => 1,
            'password' => Hash::make($dataApplicant->password),
        ]);

        // hapus data applicant
        $dataApplicant->delete();


        // $dataApplicant->status_karyawan = $this->status_karyawan;
        // $dataApplicant->tanggal_bergabung = $this->tanggal_bergabung;





        $this->dispatch('success', message: 'Data Aplicant sudah berhasil di pindahkan kedalam database karyawan');
    }

    public function mount()
    {
        $this->show_table = true;
        $this->show_data = false;
    }





    public function delete($id)
    {

        $applicant_data = Applicantdata::find($id);
        $applicant_id = $applicant_data->applicant_id;
        $applicant_files = Applicantfile::where('id_karyawan', $applicant_id)->get();
        $applicant_data->delete();
        foreach ($applicant_files as $d) {
            $d->delete();
        }
        $this->dispatch('success', message: 'Data telah di hapus');
    }
    public function show($id)
    {
        $this->personal_data = Applicantdata::find($id);
        $this->personal_files = Applicantfile::where('id_karyawan', $this->personal_data->applicant_id)->get();
        $this->show_data = true;
        $this->show_table = false;
    }

    public function waitingList($id)
    {
        $this->personal_data = Applicantdata::find($id);
        $this->personal_data->status = 'Waiting List';
        $this->personal_data->save();
        $this->dispatch('success', message: 'Status Telah di rubah menjadi "Waiting List"');
    }



    public function kembali()
    {
        $this->show_data = false;
        $this->show_table = true;
    }

    public function render()
    {
        $data = Applicantdata::orderBy('created_at', 'asc')->paginate(10);

        return view('livewire.data-applicant', [
            'data' => $data,
        ]);
    }
}
