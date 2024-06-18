<?php

namespace App\Livewire;

use App\Models\Karyawan;
use App\Models\Requester;
use App\Models\User;
use Livewire\Component;

class PermohonanPersonnel extends Component
{
    public $requester_id, $placement_id, $posisi, $jumlah_dibutuhkan, $level_posisi;
    public $manpower_posisi, $jumlah_manpower_saat_ini, $waktu_masuk_kerja, $job_description, $usia;
    public $pendidikan, $pengalaman_kerja, $kualifikasi_lain, $kisaran_gaji, $gender;
    public $skil_wajib, $alasan_permohonan, $tgl_request;
    public $is_add;
    public $user_id, $is_requester, $is_approval_1, $is_approvel_2;

    public function add()
    {
        $this->is_add = true;
    }

    public function mount()
    {
        $this->is_add = false;
        $this->is_requester = false;
        $this->is_approval_1 = false;
        $this->is_approvel_2 = false;
        // $this->user_id = auth()->user()->username;
        $this->user_id = 4;

        $check_user = Requester::where('request_id', $this->user_id)
            ->where('request_id', $this->user_id)
            ->orWhere('approve_by_1', $this->user_id)
            ->orWhere('approve_by_2', $this->user_id)->first();
        if ($check_user != null) {
            if ($check_user->request_id == $this->user_id) $this->is_requester = true;
            if ($check_user->approve_by_1 == $this->user_id) $this->is_approval_1 = true;
            if ($check_user->approve_by_2 == $this->user_id) $this->is_approvel_2 = true;
        }
    }

    public function render()
    {
        return view('livewire.permohonan-personnel');
    }
}
