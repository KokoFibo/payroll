<div>
    <style>
        td,
        th {
            white-space: nowrap;
        }
    </style>
    @section('title', 'Payroll')

    <div class="d-flex justify-content-between">
        <div class="col-6 p-4 d-flex gap-3 ">
            <div class="col-6">
                <div class="input-group">
                    <button class="btn btn-primary" type="button"><i class="fa-solid fa-magnifying-glass"></i></button>
                    <input type="search" wire:model.live="search" class="form-control" placeholder="Search ...">
                </div>
            </div>
            <div class="col-3 d-flex gap-3 align-items-center">
                <select class="form-select" wire:model.live="perpage">
                    {{-- <option selected>Open this select menu</option> --}}
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                    <option value="25">25</option>
                </select>
                Perpage
            </div>
            <div class="col-2 d-flex align-items-center gap-3">
                <select class="form-select" wire:model.live="year">
                    {{-- <option selected>Open this select menu</option> --}}
                    <option value="{{ $year }}">{{ $year }}</option>
                </select>
            </div>
            <div class="col-2 d-flex align-items-center gap-3">
                <select class="form-select" wire:model.live="month">
                    {{-- <option selected>Open this select menu</option> --}}
                    <option value="{{ $month }}">{{ monthName($month) }}</option>
                </select>
            </div>
        </div>
        <div class="col-5 p-4 d-flex justify-content-end gap-3  align-items-center">

            <div wire:loading>
                <button class="btn btn-primary" type="button" disabled>
                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                    <span role="status">Building Payroll... sedikit lama, jangan tekan apapun.</span>
                </button>
            </div>


            <div>
                {{-- <button wire:click.prevent="getPayrollConfirmation" class="btn btn-primary" wire:loading.remove>Build --}}
                <button wire:click.prevent="getPayroll()" class="btn btn-primary" wire:loading.remove>Build
                    Jam
                    Kerja</button>
            </div>

        </div>

    </div>

    <div class="p-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h3>Detail Jam kerja karyawan per {{ monthYear($periode) }}, Data Terakhir Tanggal
                        {{ format_tgl($lastData) }}
                    </h3>
                    {{-- <div>

                        <select wire:change="periode" class="form-select" wire:model.live="periode">
                            @foreach ($periodePayroll as $p)
                                < <option value="{{ $p->year }}-{{ addZeroToMonth($p->month) }}-01">
                                    {{ $p->month_name }}
                                    {{ $p->year }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                </div>
            </div>
            <style>
                td,
                th {
                    white-space: nowrap;
                }
            </style>
            <div class="card-body">
                <table-responsive>
                    <table class="table mb-3">
                        <thead>
                            <tr>
                                <th wire:click="sortColumnName('user_id')">User ID <i class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('nama')">Name <i class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('jabatan')">Jabatan <i class="fa-solid fa-sort"></i></th>
                                <th class="text-center" wire:click="sortColumnName('jumlah_jam_kerja')">Jumlah Jam Kerja
                                    <i class="fa-solid fa-sort"></i>
                                </th>
                                <th class="text-center" wire:click="sortColumnName('jumlah_menit_lembur')">Jumlah Jam
                                    Overtime <i class="fa-solid fa-sort"></i></th>
                                <th class="text-center" wire:click="sortColumnName('jumlah_jam_terlambat')">Jumlah Jam
                                    Late
                                    <i class="fa-solid fa-sort"></i>
                                </th>
                                <th class="text-center" wire:click="sortColumnName('first_in_late')">First In Late <i
                                        class="fa-solid fa-sort"></i></th>
                                <th class="text-center" wire:click="sortColumnName('first_out_late')">First Out Late <i
                                        class="fa-solid fa-sort"></i></th>
                                <th class="text-center" wire:click="sortColumnName('second_in_late')">Second In Late <i
                                        class="fa-solid fa-sort"></i></th>
                                <th class="text-center" wire:click="sortColumnName('second_out_late')">Second Out Late
                                    <i class="fa-solid fa-sort"></i></th>
                                {{-- <th class="text-center" wire:click="sortColumnName('overtime_in_late')">Overtime In Late <i class="fa-solid fa-sort"></i></th> --}}
                                <th class="text-center" wire:click="sortColumnName('total_noscan')">Total No Scan <i
                                        class="fa-solid fa-sort"></i></th>
                                <th class="text-center" wire:click="sortColumnName('total_hari_kerja')">Total Hari Kerja
                                    <i class="fa-solid fa-sort"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($filteredData as $item)
                                {{-- {{ dd($item) }} --}}
                                <tr>
                                    <td>{{ $item->user_id }}</td>
                                    <td>{{ $item->karyawan->nama }}</td>
                                    <td>{{ $item->karyawan->jabatan }}</td>
                                    <td class="text-center">{{ number_format($item->jumlah_jam_kerja, 1) }}</td>
                                    <td class="text-center">{{ $item->jumlah_menit_lembur / 60 }}</td>
                                    {{-- <td class="text-center">{{ $item->jumlah_menit_lembur }}</td> --}}
                                    <td class="text-center">{{ $item->jumlah_jam_terlambat }}</td>
                                    <td class="text-center">{{ $item->first_in_late }}</td>
                                    <td class="text-center">{{ $item->first_out_late }}</td>
                                    <td class="text-center">{{ $item->second_in_late }}</td>
                                    <td class="text-center">{{ $item->second_out_late }}</td>
                                    {{-- <td class="text-center">{{ $item->overtime_in_late * 30 /60}}</td> --}}
                                    <td class="text-center">{{ $item->total_noscan }}</td>
                                    <td class="text-center">{{ $item->total_hari_kerja }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </table-responsive>
                {{ $filteredData->links() }}
            </div>
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
