<div>
    <style>
        td,
        th {
            white-space: nowrap;
        }
    </style>
    @section('title', 'Payroll')

    <div class="d-flex justify-content-between">
        <div class="col-4 p-4">
            CX = {{ $cx }}
            Periode: {{ $periode }}
            <div class="input-group">
                <button class="btn btn-primary" type="button"><i class="fa-solid fa-magnifying-glass"></i></button>
                <input type="search" wire:model.live="search" class="form-control" placeholder="Search ...">
            </div>


        </div>
        <div class="col-3 p-4 d-flex justify-content-end gap-3  align-items-center">

            <div wire:loading>
                Building Payroll...
            </div>
            <div>
                <button wire:click="getPayrollConfirmation" class="btn btn-primary">Get Payroll</button>
            </div>
        </div>

    </div>

    <div class="p-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h3>Detail Jam kerja karyawan per {{ monthYear($periode) }}
                    </h3>
                    <div>

                        <select wire:change="periode" class="form-select" wire:model.live="periode">
                            {{-- <option selected>{{ $p->month_name }} {{ $p->year }}</option> --}}
                            @foreach ($periodePayroll as $p)
                                < <option value="{{ $p->year }}-{{ addZeroToMonth($p->month) }}-01">
                                    {{ $p->month_name }}
                                    {{ $p->year }}</option>
                            @endforeach

                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table mb-3">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th class="text-center">Jumlah Jam Kerja</th>
                            <th class="text-center">Jumlah Menit Overtime</th>
                            <th class="text-center">Jumlah Jam Late</th>
                            <th class="text-center">First In Late</th>
                            <th class="text-center">First Out Late</th>
                            <th class="text-center">Second In Late</th>
                            <th class="text-center">Second Out Late</th>
                            <th class="text-center">Overtime In Late</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($filteredData as $item)
                            <tr>
                                <td>{{ $item->user_id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->date }}</td>
                                <td class="text-center">{{ $item->jumlah_jam_kerja }}</td>
                                <td class="text-center">{{ $item->jumlah_menit_lembur }}</td>
                                <td class="text-center">{{ $item->jumlah_jam_terlambat }}</td>
                                <td class="text-center">{{ $item->first_in_late }}</td>
                                <td class="text-center">{{ $item->first_out_late }}</td>
                                <td class="text-center">{{ $item->second_in_late }}</td>
                                <td class="text-center">{{ $item->second_out_late }}</td>
                                <td class="text-center">{{ $item->overtime_in_late }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
    </script>
</div>
