<div>
    <style>
        td,
        th {
            white-space: nowrap;
        }
    </style>
    @section('title', 'Karyawan')

    <div class="d-flex  p-4">
        <div class="col-4 ">
            <div class="input-group">
                <button class="btn btn-primary" type="button"><i class="fa-solid fa-magnifying-glass"></i></button>
                <input type="search" wire:model.live="search" class="form-control" placeholder="Search ...">
            </div>
        </div>
        <div class="col-4 ">
        </div>
        @if (Auth::user()->role == 4 || Auth::user()->role == 3)
            <div class="col-4 text-end">
                {{-- wire loading mengganggu saat query --}}
                {{-- <div wire:loading class="spinner-border text-success" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div> --}}
                <div>
                    <button wire:click="excel" class="btn btn-success">Excel</button>
                </div>
            </div>
        @endif
    </div>




    <div class="px-4">


        <div class="card">
            <div class="card-header">
                <h3>Data Karyawan
                    <a href="/karyawancreate"><button class="btn btn-primary float-end"><i class="fa-solid fa-plus"></i>
                            Karyawan baru</button></a>
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive-md">
                    <table class="table  table-sm  table-hover mb-2">
                        <thead>
                            <tr>
                                <th></th>
                                <th wire:click="sortColumnName('id_karyawan')">Id Karyawan <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('nama')">Nama <i class="fa-solid fa-sort"></i></th>
                                <th class="text-center" wire:click="sortColumnName('branch')">Branch <i
                                        class="fa-solid fa-sort"></i></th>
                                <th class="text-center" wire:click="sortColumnName('departemen')">Departemen <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th class="text-center" wire:click="sortColumnName('jabatan')">Jabatan <i
                                        class="fa-solid fa-sort"></i></th>
                                <th class="text-center" wire:click="sortColumnName('level_jabatan')">Level Jabatan <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th class="text-center" wire:click="sortColumnName('status_karyawan')">Status <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                @if (Auth::user()->role == 3 || Auth::user()->role == 4)
                                    <th class="text-center" wire:click="sortColumnName('metode_penggajian')">Metode
                                        Penggajian <i class="fa-solid fa-sort"></i>
                                    </th>
                                    <th class="text-center" wire:click="sortColumnName('gaji_pokok')">Gaji Pokok <i
                                            class="fa-solid fa-sort"></i>
                                    </th>
                                    <th class="text-center" wire:click="sortColumnName('gaji_overtime')">Overtime <i
                                            class="fa-solid fa-sort"></i>
                                    </th>
                                    <th class="text-center" wire:click="sortColumnName('uang_makan')">Uang Makan <i
                                            class="fa-solid fa-sort"></i>
                                    </th>
                                    <th class="text-center" wire:click="sortColumnName('bonus')">Bonus <i
                                            class="fa-solid fa-sort"></i>
                                    </th>
                                @endif


                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datas as $data)
                                <tr>
                                    <td>
                                        <div class="text-start">
                                            <a href="/karyawanupdate/{{ $data->id }}"><button
                                                    class="btn btn-success btn-sm"><i
                                                        class="fa-regular fa-pen-to-square"></i></button></a>

                                            @if (Auth::user()->role == 3 || Auth::user()->role == 4)
                                                <button wire:click="confirmDelete(`{{ $data->id }}`)"
                                                    class="btn btn-danger btn-sm"><i
                                                        class="fa-solid fa-trash-can"></i></button>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $data->id_karyawan }}</td>
                                    <td>{{ $data->nama }}</td>
                                    <td class="text-center">{{ $data->branch }}</td>
                                    <td class="text-center">{{ $data->departemen }}</td>
                                    <td class="text-center">{{ $data->jabatan }}</td>
                                    <td class="text-center">{{ $data->level_jabatan }}</td>
                                    <td class="text-center">{{ $data->status_karyawan }}</td>
                                    @if (Auth::user()->role == 3 || Auth::user()->role == 4)
                                        <td class="text-center">{{ $data->metode_penggajian }}</td>
                                        <td class="text-center">{{ number_format($data->gaji_pokok) }}</td>
                                        <td class="text-center">{{ number_format($data->gaji_overtime) }}</td>
                                        <td class="text-center">{{ number_format($data->uang_makan) }}</td>
                                        <td class="text-center">{{ number_format($data->bonus) }}</td>
                                    @endif


                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $datas->links() }}
                </div>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener("swal:confirm", (event) => {
            Swal.fire({
                title: "Apakah yakin mau di delete",
                text: "Data yang sudah di delete tidak dapat dikembalikan",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Delete",
            }).then((willDelete) => {
                if (willDelete.isConfirmed) {
                    @this.dispatch("delete", event.detail.id);
                }
            });
        });
    </script>
</div>
