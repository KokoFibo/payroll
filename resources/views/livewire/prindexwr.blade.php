<div>
    <style>
        td,
        th {
            white-space: nowrap;
        }
    </style>
    @section('title', 'Presensi Detail')
    <h4 class="text-center text-bold pt-2">Presensi Detail</h4>
    <div class="col-12 d-flex flex-xl-row flex-column justify-content-xl-between">
        <div class="col-xl-8 col-12 p-2 p-xl-4 d-flex flex-xl-row flex-column  gap-xl-3 gap-2">
            <div class="col-xl-6 col-12">
                <div class="input-group">
                    <button class="btn btn-primary" type="button"><i class="fa-solid fa-magnifying-glass"></i></button>
                    <input type="search" wire:model.live="search" class="form-control" placeholder="Search ...">
                </div>
            </div>
            <div class="col-xl-2 col-12 ">
                <select class="form-select" wire:model.live="perpage">
                    {{-- <option selected>Open this select menu</option> --}}
                    <option value="10">10 rows perpage</option>
                    <option value="15">15 rows perpage</option>
                    <option value="20">20 rows perpage</option>
                    <option value="25">25 rows perpage</option>
                </select>
            </div>
            <div class="col-xl-2 col-12  ">
                <select class="form-select" wire:model.live="year">
                    {{-- <option selected>Open this select menu</option> --}}
                    <option value="{{ $year }}">{{ $year }}</option>
                </select>
            </div>
            <div class="col-xl-2 col-12">
                <select class="form-select" wire:model.live="month">
                    {{-- <option selected>Open this select menu</option> --}}
                    <option value="11">{{ monthName(11) }}</option>
                    <option value="12">{{ monthName(12) }}</option>
                </select>
            </div>
        </div>

        <div
            class="col-xl-3 col-12  p-xl-4 d-flex  flex-xl-row flex-column {{ auth()->user()->role < 3 ? 'invisible' : '' }} ">

            <div class=" col-3">
                {{-- <button wire:click.prevent="getPayrollConfirmation" class="btn btn-primary" wire:loading.remove>Build --}}
                <a href="/presensisummaryindex"><button class="btn btn-success text-end mb-2 mr-2">Excel</button></a>
            </div>

            <div class=" col-9" wire:loading>
                <button class="btn btn-primary" type="button" disabled>
                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                    <span role="status">Building Payroll... sedikit lama, jangan tekan apapun.</span>
                </button>
            </div>
            <div class=" col-9">
                {{-- <button wire:click.prevent="getPayrollConfirmation" class="btn btn-primary" wire:loading.remove>Build --}}
                <button wire:click.prevent="getPayroll()" class="btn btn-primary col-12 col-xl-8"
                    wire:loading.remove>Build Jam
                    Kerja</button>
            </div>

        </div>

    </div>

    <div class="p-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h3 class="fw-semibold fs-5 fwfs-3-xl">Detail Jam kerja karyawan per {{ monthYear($periode) }}, Data
                        Terakhir Tanggal
                        {{ format_tgl($lastData) }}
                    </h3>

                </div>
            </div>
            <style>
                td,
                th {
                    white-space: nowrap;
                }
            </style>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-3">
                        <thead>
                            <tr>
                                <th wire:click="sortColumnName('user_id')">User ID <i class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('nama')">Name <i class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('jabatan')">Jabatan <i class="fa-solid fa-sort"></i></th>
                                <th class="text-center" wire:click="sortColumnName('total_hari_kerja')">Total Hari Kerja
                                    <i class="fa-solid fa-sort"></i>
                                <th class="text-center" wire:click="sortColumnName('jumlah_jam_kerja')">Jumlah Jam Kerja
                                    <i class="fa-solid fa-sort"></i>
                                </th>
                                <th class="text-center" wire:click="sortColumnName('jumlah_menit_lembur')">Jumlah Jam
                                    Overtime <i class="fa-solid fa-sort"></i></th>

                                <th class="text-center" wire:click="sortColumnName('jumlah_jam_terlambat')">Jumlah Jam
                                    Terlambat
                                    <i class="fa-solid fa-sort"></i>
                                </th>
                                <th class="text-center" wire:click="sortColumnName('tambahan_jam_shift_malam')">Tambahan
                                    Jam
                                    Overtime Shift Malam <i class="fa-solid fa-sort"></i></th>
                                <th class="text-center" wire:click="sortColumnName('total_noscan')">Total No Scan <i
                                        class="fa-solid fa-sort"></i></th>

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($filteredData as $item)
                                {{-- {{ dd($item) }} --}}
                                <tr>
                                    <td>{{ $item->user_id }}</td>
                                    <td>{{ $item->karyawan->nama }}</td>
                                    <td>{{ $item->karyawan->jabatan }}</td>
                                    <td class="text-center">{{ $item->total_hari_kerja }}</td>
                                    <td class="text-center">{{ number_format($item->jumlah_jam_kerja, 1) }}</td>
                                    <td class="text-center">{{ number_format($item->jumlah_menit_lembur, 1) }}</td>
                                    <td class="text-center">{{ number_format($item->jumlah_jam_terlambat, 1) }}</td>
                                    <td class="text-center">{{ number_format($item->tambahan_jam_shift_malam) }}</td>
                                    <td class="text-center">{{ number_format($item->total_noscan) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $filteredData->onEachSide(0)->links() }}
                </div>
            </div>
            <p class="px-3 text-success">Last update: {{ $last_build }} </p>
        </div>
    </div>
    <script>
        window.addEventListener("confirm", (event) => {
            Swal.fire({
                title: "Apakah yakin mau di Generate Ulang ?",
                // text: "Data yang sudah di delete tidak dapat dikembalikan",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya",
            }).then((willDelete) => {
                if (willDelete.isConfirmed) {
                    @this.dispatch("getPayroll");
                }
            });
        });
        window.addEventListener("foundError", (event) => {
            console.log(event);
            Swal.fire({
                icon: 'error',
                title: event.detail.title,
                text: 'Ada Kesalahan',

            })
        });
    </script>
</div>
