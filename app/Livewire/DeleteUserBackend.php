<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class DeleteUserBackend extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    /**
     * HALAMAN INI KHUSUS UNTUK MENGHAPUS DATA USER
     * YANG DIGUNAKAN UNTUK LOGIN KE BACKEND.
     *
     * Filter:
     * - Role di bawah 5.
     * - Mengecualikan username tertentu.
     * - Jika hasil filter kosong, tampilkan semua user.
     *
     * PERINGATAN:
     * Delete All dapat menghapus semua user jika fallback aktif.
     */

    private function employeeQuery()
    {
        $query = User::where('role', '<', 5)
            ->whereNotIn('username', [
                '10000',
                '20000',
                '30000',
                '40000',
                '50000',
                '60000',
                '70000',
                '80000',
                '100000',
            ]);

        if (!$query->exists()) {
            return User::query();
        }

        return $query;
    }

    /**
     * Hapus satu user berdasarkan ID.
     */
    public function delete($id)
    {
        $user = $this->employeeQuery()->findOrFail($id);

        $user->delete();

        $this->resetPage();

        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data Karyawan Sudah di Delete',
        );
    }

    /**
     * Hapus semua user sesuai hasil query.
     */
    public function deleteAll()
    {
        $deleted = $this->employeeQuery()->delete();

        $this->resetPage();

        $this->dispatch(
            'message',
            type: 'success',
            title: $deleted . ' Data Karyawan Berhasil di Delete',
        );
    }

    /**
     * Tampilkan daftar user dengan pagination.
     */
    public function render()
    {

        // $data =  User::query()
        //     ->select('role')
        //     ->distinct()
        //     ->orderBy('role')
        //     ->pluck('role');
        // // ->get();

        // dd($data);

        $data = $this->employeeQuery()
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.delete-user-backend', [

            'data' => $data,
        ]);
    }
}
