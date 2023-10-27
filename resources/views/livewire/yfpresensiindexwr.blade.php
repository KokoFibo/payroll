<div>
    @section('title', 'Presensi')

    <div class="d-flex flex-row gap-5 px-4 pt-4">
        <button class="btn btn-info">Total Hadir : {{ $totalHadir }}, Shift Pagi : {{ $totalHadirPagi }}, Shift
            Malam :
            {{ $totalHadir - $totalHadirPagi }}</button>

        <button id="okk" class="btn btn-warning">Total No scan : {{ $totalNoScan }} / {{ $overallNoScan }}, Shift
            Pagi :
            {{ $totalNoScanPagi }},
            Shift
            Malam : {{ $totalNoScan - $totalNoScanPagi }}</button>
        <button class="btn btn-danger">Total Late : {{ $totalLate }}, Shift Pagi : {{ $totalLatePagi }}, Shift
            Malam
            : {{ $totalLate - $totalLatePagi }}</button>
    </div>
    <div class="row col-12 p-4">
        <div class="col-4">
            <div class="input-group">
                <button class="btn btn-primary" type="button"><i class="fa-solid fa-magnifying-glass"></i></button>
                <input type="search" wire:model.live="search" class="form-control" placeholder="Search ...">
            </div>
        </div>
        <div class="col-4">
            <div class="input-group">
                <button class="btn btn-primary" type="button"><i class="fa-solid fa-calendar-days"></i></button>
                <input type="date" wire:model.live="tanggal" class="form-control">
            </div>
        </div>
        <div class="col-4  d-flex gap-3">
            <button wire:click="resetTanggal" class="btn btn-success" type="button">Reset</button>
            <button wire:click="filterNoScan" class="btn btn-warning" type="button">No Scan</button>
            <button wire:click="filterLate" class="btn btn-info" type="button">Late</button>
        </div>
    </div>


    <div class="px-4">
        <div class="card">
            <div class="card-header">
                <h4>Data Presensi {{ format_tgl_hari($tanggal) }}
                    <a href="/yfupload"><button class="btn btn-primary float-end">Upload Presensi</button></a>
                </h4>
            </div>
            <style>
                td {
                    white-space: nowrap;
                }
            </style>
            <div class="card-body ">
                <div class="table-responsive">

                    <table class="table table-sm table-hover mb-4">
                        <thead>
                            <tr>
                                <td>Action</td>
                                <td wire:click="sortColumnName('user_id')">ID <i class=" fa-solid fa-sort"></i></td>
                                <td wire:click="sortColumnName('name')">Nama <i class="fa-solid fa-sort"></i></td>
                                <td wire:click="sortColumnName('department')">Department <i
                                        class="fa-solid fa-sort"></i>
                                </td>
                                <td wire:click="sortColumnName('date')">Working Date <i class="fa-solid fa-sort"></i>
                                </td>
                                <td wire:click="sortColumnName('first_in')">First in <i class="fa-solid fa-sort"></i>
                                </td>
                                <td wire:click="sortColumnName('first_out')">First out <i class="fa-solid fa-sort"></i>
                                </td>
                                <td wire:click="sortColumnName('second_in')">Second in <i class="fa-solid fa-sort"></i>
                                </td>
                                <td wire:click="sortColumnName('second_out')">Second out <i
                                        class="fa-solid fa-sort"></i>
                                </td>
                                <td wire:click="sortColumnName('overtime_in')">Overtime in <i
                                        class="fa-solid fa-sort"></i>
                                </td>
                                <td wire:click="sortColumnName('overtime_out')">Overtime out <i
                                        class="fa-solid fa-sort"></i>
                                </td>
                                <td wire:click="sortColumnName('late')">Late <i class="fa-solid fa-sort"></i></td>
                                <td wire:click="sortColumnName('no_scan')">No scan <i class="fa-solid fa-sort"></i></td>
                                <td wire:click="sortColumnName('shift')">Shift <i class="fa-solid fa-sort"></i></td>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($datas->isNotEmpty())


                                @foreach ($datas as $data)
                                    <tr x-data="{ edit: false }" class="{{ $data->no_scan ? 'table-warning' : '' }}">
                                        <td>

                                            @if ($btnEdit == true)
                                                <button @click="edit = !edit"
                                                    wire:click="update({{ $data->id }})"class="btn btn-success btn-sm"><i
                                                        class="fa-regular fa-pen-to-square"></i></button>
                                            @else
                                                @if ($data->id == $selectedId)
                                                    <button @click="edit = !edit" wire:click="save"
                                                        class="btn btn-primary btn-sm"><i
                                                            class="fa-solid fa-floppy-disk"></i></button>
                                                @else
                                                    <button @click="edit = !edit" disabled wire:click="save"
                                                        class="btn btn-success btn-sm"><i
                                                            class="fa-regular fa-pen-to-square"></i></button>
                                                @endif
                                            @endif


                                            {{-- <button @click="edit = !edit" wire:click="update({{ $data->id }})"
                                                class="btn btn-success btn-sm"><i class="fa-regular fa-pen-to-square">
                                                </i></button> --}}


                                            {{-- <button @click="edit = !edit" wire:click="update({{ $data->id }})"
                                                class="btn btn-success btn-sm"><i class="fa-regular fa-pen-to-square"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#update-form-modal"></i></button> --}}
                                            @if (Auth::user()->role > 3)
                                                <button
                                                    wire:click="$dispatch('swal:confirm', { id: {{ $data->id }} })"
                                                    class="btn btn-danger btn-sm"><i
                                                        class="fa-solid fa-trash-can confirm-delete"></i></button>
                                            @endif
                                        </td>
                                        <td>{{ $data->user_id }}</td>
                                        <td>{{ $data->karyawan->nama }}</td>
                                        <td>{{ $data->karyawan->departemen }}</td>
                                        <td>{{ format_tgl($data->date) }}</td>
                                        <td x-show="!edit"
                                            class="{{ checkFirstInLate($data->first_in, $data->shift, $data->date) ? 'text-danger' : '' }}">
                                            {{ format_jam($data->first_in) }} </td>
                                        <td x-show="edit"><input
                                                style="width:100px; background-color: #ffeeba;; background-color: #ffeeba"
                                                class="form-control @error('first_in') is-invalid @enderror"
                                                id="first_in" type="text" wire:model="first_in">
                                            @error('first_in')
                                                <span>Format jam harus sesuai HH:MM</span>
                                                <div class="invalid-feedback">
                                                    Format jam harus sesuai HH:MM
                                                </div>
                                            @enderror
                                        </td>
                                        <td x-show="!edit"
                                            class="{{ checkFirstOutLate($data->first_out, $data->shift, $data->date) ? 'text-danger' : '' }}">
                                            {{ format_jam($data->first_out) }} </td>
                                        <td x-show="edit"><input style="width:100px; background-color: #ffeeba;"
                                                class="form-control @error('first_out') is-invalid @enderror"
                                                id="first_out" type="text" wire:model="first_out">
                                            @error('first_out')
                                                <div class="invalid-feedback">
                                                    Format jam harus sesuai HH:MM
                                                </div>
                                            @enderror
                                        </td>
                                        <td x-show="!edit"
                                            class="{{ checkSecondInLate($data->second_in, $data->shift, $data->first_out, $data->date) ? 'text-danger' : '' }}">
                                            {{ format_jam($data->second_in) }} </td>
                                        <td x-show="edit"><input style="width:100px; background-color: #ffeeba;"
                                                class="form-control @error('second_in') is-invalid @enderror"
                                                id="second_in" type="text" wire:model="second_in">
                                            @error('second_in')
                                                <div class="invalid-feedback">
                                                    Format jam harus sesuai HH:MM
                                                </div>
                                            @enderror
                                        </td>
                                        <td x-show="!edit"
                                            class="{{ checkSecondOutLate($data->second_out, $data->shift, $data->date) ? 'text-danger' : '' }}">
                                            {{ format_jam($data->second_out) }} </td>
                                        <td x-show="edit"><input style="width:100px; background-color: #ffeeba;"
                                                class="form-control @error('second_out') is-invalid @enderror"
                                                id="second_out" type="text" wire:model="second_out">
                                            @error('second_out')
                                                <div class="invalid-feedback">
                                                    Format jam harus sesuai HH:MM
                                                </div>
                                            @enderror
                                        </td>
                                        <td x-show="!edit"
                                            class="{{ checkOvertimeInLate($data->overtime_in, $data->shift, $data->date) ? 'text-danger' : '' }}">
                                            {{ format_jam($data->overtime_in) }} </td>
                                        <td x-show="edit"><input style="width:100px; background-color: #ffeeba;"
                                                class="form-control @error('overtime_in') is-invalid @enderror"
                                                id="overtime_in" type="text" wire:model="overtime_in">
                                            @error('overtime_in')
                                                <div class="invalid-feedback">
                                                    Format jam harus sesuai HH:MM
                                                </div>
                                            @enderror
                                        </td>
                                        <td x-show="!edit">
                                            {{ format_jam($data->overtime_out) }} </td>
                                        <td x-show="edit"><input style="width:100px; background-color: #ffeeba;"
                                                class="form-control @error('overtime_out') is-invalid @enderror"
                                                id="overtime_out" type="text" wire:model="overtime_out">
                                            @error('overtime_out')
                                                <div class="invalid-feedback">
                                                    Format jam harus sesuai HH:MM
                                                </div>
                                            @enderror
                                        </td>
                                        {{-- <td
                                            class="{{ checkFirstOutLate($data->first_out, $data->shift, $data->date) ? 'text-danger' : '' }}">
                                            {{ format_jam($data->first_out) }}</td> --}}
                                        {{-- <td
                                        class="{{ checkSecondInLate($data->second_in, $data->shift, $data->first_out, $data->date) ? 'text-danger' : '' }}">
                                        {{ format_jam($data->second_in) }}</td> --}}
                                        {{-- <td
                                            class="{{ checkSecondOutLate($data->second_out, $data->shift, $data->date) ? 'text-danger' : '' }}">
                                            {{ format_jam($data->second_out) }}</td> --}}
                                        {{-- <td
                                            class="{{ checkOvertimeInLate($data->overtime_in, $data->shift, $data->date) ? 'text-danger' : '' }}">
                                            {{ format_jam($data->overtime_in) }}</td> --}}
                                        {{-- <td>
                                            {{ format_jam($data->overtime_out) }}</td> --}}
                                        <td>
                                            @if ($data->late_history >= 1 && $data->late >= 1)
                                                <h6><span class="badge bg-info">Late</span>
                                                </h6>
                                            @elseif ($data->late_history >= 1 && $data->late == null)
                                                <h6><span class="badge bg-success"><i class="fa-solid fa-check"></i>
                                                        {{ $data->late_history }}
                                                    </span>
                                                </h6>
                                            @else
                                                {{-- {{ $data->late }} --}}
                                                <span></span>
                                            @endif

                                        </td>
                                        <td>
                                            @if ($data->no_scan_history == 'No Scan' && $data->no_scan == 'No Scan')
                                                <h6><span class="badge bg-warning">No Scan</span></h6>
                                            @elseif ($data->no_scan_history == 'No Scan' && $data->no_scan == null)
                                                <h6><span class="badge bg-success"><i
                                                            class="fa-solid fa-check"></i></span>
                                                </h6>
                                            @else
                                                {{ $data->no_scan }}
                                            @endif
                                            </i>
                                        <td>{{ $data->shift }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <h4>Tidak ada data yang ditemukan</h4>
                            @endif
                        </tbody>
                    </table>
                </div>

                {{ $datas->links() }}
            </div>
        </div>
    </div>
    {{-- Modal --}}
    <div wire:ignore.self class="modal fade" id="update-form-modal" data-bs-backdrop="static"
        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Presensi {{ $user_id }} &
                        {{ $name }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="mb-3 col-6">
                            <label for="first_in" class="form-label">First
                                In</label>
                            <input class="form-control @error('first_in') is-invalid @enderror" id="first_in"
                                type="text" wire:model="first_in">
                            @error('first_in')
                                <div class="invalid-feedback">
                                    Format jam harus sesuai HH:MM
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3 col-6">
                            <label for="first_out" class="form-label">First
                                Out</label>
                            <input class="form-control @error('first_out') is-invalid @enderror" id="first_out"
                                type="text" wire:model="first_out">
                            @error('first_out')
                                <div class="invalid-feedback">
                                    Format jam harus sesuai HH:MM
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">


                        <div class="mb-3 col-6">
                            <label for="second_in" class="form-label">Second
                                In</label>
                            <input class="form-control @error('second_in') is-invalid @enderror" id="second_in"
                                type="text" wire:model="second_in">
                            @error('second_in')
                                <div class="invalid-feedback">
                                    Format jam harus sesuai HH:MM
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3 col-6">
                            <label for="second_out" class="form-label">Second
                                Out</label>
                            <input class="form-control @error('second_out') is-invalid @enderror" id="second_out"
                                type="text" wire:model="second_out">
                            @error('second_out')
                                <div class="invalid-feedback">
                                    Format jam harus sesuai HH:MM
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">

                        <div class="mb-3 col-6">
                            <label for="overtime_in" class="form-label">Overtime
                                In</label>
                            <input class="form-control @error('overtime_in') is-invalid @enderror" id="overtime_in"
                                type="text" wire:model="overtime_in">
                            @error('overtime_in')
                                <div class="invalid-feedback">
                                    Format jam harus sesuai HH:MM
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3 col-6">
                            <label for="overtime_out" class="form-label">Overtime
                                Out</label>
                            <input class="form-control @error('overtime_out') is-invalid @enderror" id="overtime_out"
                                type="text" wire:model="overtime_out">
                            @error('overtime_out')
                                <div class="invalid-feedback">
                                    Format jam harus sesuai HH:MM
                                </div>
                            @enderror
                        </div>
                    </div>

                </div>
                <div class="modal-footer">


                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    @if ($datas->isNotEmpty())
                        <button wire:click="save({{ $data->id }})" type="button"
                            class="btn btn-primary">Update</button>
                    @endif

                </div>
            </div>
        </div>
    </div>
    {{-- End  --}}
    <script>
        window.addEventListener("swal:confirm", (event) => {
            console.log(event);
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
                    @this.dispatch("delete", {
                        id: event.detail.id
                    });
                }
            });
        });
    </script>
</div>
