<div>
    @section('title', 'Bonus dan Potongan')
    <div class="col-12 col-xl-8 mx-auto pt-3">
        <div class="card ">
            <div class="card-header bg-secondary">
                <label class="col-sm-2  col-form-label">Bonus dan Potongan</label>
            </div>
            @if ($modal == true)
                <div class="card-body">
                    <p>ID Karyawan : {{ $user_id }}</p>
                    <p>Nama Karyawan : {{ $nama_karyawan }}</p>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Tanggal (mm/dd/yyyy)</label>
                                <input wire:model="tanggal" class="form-control" type="date">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Uang Makan</label>
                                <input wire:model="uang_makan" type="number" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Bonus</label>
                                <input wire:model="bonus" type="number" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Bonus Lain</label>
                                <input wire:model="bonus_lain" type="number" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Baju ESD</label>
                                <input wire:model="baju_esd" type="number" class="form-control">

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Gelas</label>
                                <input wire:model="gelas" type="number" class="form-control">

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Sandal</label>
                                <input wire:model="sandal" type="number" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Seragam</label>
                                <input wire:model="seragam" type="number" class="form-control">
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">Sport Bra</label>
                                <input wire:model="sport_bra" type="number" class="form-control">

                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">Hijab Instan</label>
                                <input wire:model="hijab_instan" type="number" class="form-control">

                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">ID Card Hilang</label>
                                <input wire:model="id_card_hilang" type="number" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">Masker Hijau</label>
                                <input wire:model="masker_hijau" type="number" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">Potongan Lain</label>
                                <input wire:model="potongan_lain" type="number" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-5">
                        <button wire:click="save" class="btn btn-success">Save</button>
                        <button wire:click="cancel" class="btn btn-dark">Cancel</button>
                    </div>
                </div>
            @endif

        </div>
    </div>
    <div class="col-12 col-xl-8 mx-auto pt-3">

        @if ($modal == false)
            <style>
                td,
                th {
                    white-space: nowrap;
                }
            </style>


            <div class="card">
                <div class="card-header">
                    <div class="input-group col-12 col-xl-4">
                        <button class="btn btn-primary" type="button"><i
                                class="fa-solid fa-magnifying-glass"></i></button>
                        <input type="search" wire:model.live="search" class="form-control" placeholder="Search ...">
                    </div>
                </div>
                <div class="col-12">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table  table-sm  table-hover mb-2">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Karyawan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $d)
                                        <tr>
                                            <td>{{ $d->id_karyawan }}</td>
                                            <td>{{ $d->nama }}</td>
                                            <td>{{ $d->id }}</td>
                                            <td>
                                                @if (ada_tambahan($d->id_karyawan))
                                                    <div class="text-center">
                                                        <div class="btn-group" role="group"
                                                            aria-label="Basic mixed styles example">
                                                            <button wire:click="update({{ $d->id_karyawan }})"
                                                                type="button" class="btn btn-warning">Edit</button>
                                                            <button wire:confirm="Yakin mau di delete?"
                                                                wire:click="delete({{ $d->id_karyawan }})"
                                                                type="button" class="btn btn-danger">Delete</button>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="text-center">
                                                        <button wire:click="add({{ $d->id_karyawan }})" type="button"
                                                            class="btn btn-success">Add</button>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $data->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
