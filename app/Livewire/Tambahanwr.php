<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Karyawan;
use App\Models\Tambahan;
use Livewire\WithPagination;
use Carbon\Carbon;

class Tambahanwr extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $id, $is_edit, $id_tambahan, $modal, $search, $user_id, $nama_karyawan;
    public $tanggal, $uang_makan, $bonus, $bonus_lain, $baju_esd, $gelas, $sandal;
    public $seragam, $sport_bra, $hijab_instan, $id_card_hilang, $masker_hijau, $potongan_lain;

    public function mount () {
        $this->modal = false;
        $this->is_edit = false;
    }

    public function add ($id_karyawan) {
        $data_karyawan = Karyawan::where('id_karyawan', ($id_karyawan))->first();
        $this->id = $data_karyawan->id ;
        $this->user_id = $id_karyawan;
        $this->nama_karyawan = $data_karyawan->nama;
        $this->tanggal =  date('Y-m-d');
        $this->modal = true;
    }

    public function save () {

        if (
            $this->uang_makan == null &&
            $this->bonus == null &&
            $this->bonus_lain == null &&
            $this->baju_esd == null &&
            $this->gelas == null &&
            $this->sandal == null &&
            $this->seragam == null &&
            $this->sport_bra == null &&
            $this->hijab_instan == null &&
            $this->id_card_hilang == null &&
            $this->masker_hijau == null &&
            $this->potongan_lain == null
        ) {

        $this->dispatch('error', message: 'Data tidak disimpan');
        return;

        }
        if($this->is_edit == false) {
            $karyawan = Karyawan::find($this->id);
            $data = new Tambahan();
            $data->user_id = $karyawan->id_karyawan;
        } else {
            $data = Tambahan::find($this->id_tambahan);
        }
        $data->uang_makan = $this->uang_makan;
        $data->bonus = $this->bonus;
        $data->bonus_lain = $this->bonus_lain;
        $data->baju_esd = $this->baju_esd;
        $data->gelas = $this->gelas;
        $data->sandal = $this->sandal;
        $data->seragam = $this->seragam;
        $data->sport_bra = $this->sport_bra;
        $data->hijab_instan = $this->hijab_instan;
        $data->id_card_hilang = $this->id_card_hilang;
        $data->masker_hijau = $this->masker_hijau;
        $data->potongan_lain = $this->potongan_lain;
        $data->tanggal = date( 'Y-m-d', strtotime( $this->tanggal ) );
        $data->save();
        if($this->is_edit == false) {
        $this->dispatch('success', message: 'Data sudah di Add');

    } else {
            $this->dispatch('success', message: 'Data sudah di Update');
        }
        $this->is_edit = false;
        $this->modal = false;
        $this->clear_data();
    }

    public function cancel () {
        $this->is_edit = false;
        $this->modal = false;
        $this->clear_data();
    }

    public function clear_data () {
        $this->uang_makan = null;
        $this->bonus = null;
        $this->bonus_lain = null;
        $this->baju_esd = null;
        $this->gelas = null;
        $this->sandal = null;
        $this->seragam = null;
        $this->sport_bra = null;
        $this->hijab_instan = null;
        $this->id_card_hilang = null;
        $this->masker_hijau = null;
        $this->potongan_lain = null;
        $this->tanggal =  date( 'd M Y', strtotime( now()->toDateString() ) );
    }

    public function update($id) {
        $data_karyawan = Karyawan::where('id_karyawan', $id)->first();
        $this->user_id = $id;
        $this->nama_karyawan = $data_karyawan->nama;

        $this->is_edit = true;
        $this->modal = true;
        $data_tambahan = Tambahan::where('user_id', $id)->first();
        $this->id_tambahan = $data_tambahan->id ;
        $data_tambahan = Tambahan::find($this->id_tambahan);

        $this->uang_makan = $data_tambahan->uang_makan;
        $this->bonus = $data_tambahan->bonus;
        $this->bonus_lain = $data_tambahan->bonus_lain;
        $this->baju_esd = $data_tambahan->baju_esd;
        $this->gelas = $data_tambahan->gelas;
        $this->sandal = $data_tambahan->sandal;
        $this->seragam = $data_tambahan->seragam;
        $this->sport_bra = $data_tambahan->sport_bra;
        $this->hijab_instan = $data_tambahan->hijab_instan;
        $this->id_card_hilang = $data_tambahan->id_card_hilang;
        $this->masker_hijau = $data_tambahan->masker_hijau;
        $this->potongan_lain = $data_tambahan->potongan_lain;
        $this->tanggal = $data_tambahan->tanggal;
        // $this->tanggal = $data_tambahan->tanggal;
    }



    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function delete  ($id) {

        // dd($id);
       $data_tambahan = Tambahan::where('user_id', $id)->first();
       $data = Tambahan::find($data_tambahan->id);
       $data->delete();
       $this->dispatch('success', message: 'Data sudah di Delete');
    }

    public function render()
    {
        $oneYearAgo = Carbon::now()->subYear();
        $data = Karyawan::query()
        ->select('id', 'id_karyawan', 'nama')
        ->orderBy('id_karyawan', 'desc')
        ->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
        ->where('tanggal_bergabung',  '>', $oneYearAgo)
        ->when($this->search, function ($query) {
            $query
            ->where('nama', 'LIKE', '%' . trim($this->search) . '%')
            ->orWhere('id_karyawan', trim($this->search));
        })
        ->paginate(10);


        return view('livewire.tambahanwr', compact('data'));
    }
}