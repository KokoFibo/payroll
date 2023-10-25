<div>
    <div>
        <div class="container">
            <div class="mx-auto  mt-4">
                <button class="mx-auto col-12 btn btn-info btn-large">
                    <h3 class="px-3">Karyawan Settings</h3>
                </button>
                <div class="card mt-5  mx-auto">
                    <div class="card-header">
                        <h5>Rubah Role Karyawan</h5>
                    </div>
                    <div class="card-body">
                        <div class="input-group col-6 ">
                            <button class="btn btn-primary" type="button"><i
                                    class="fa-solid fa-magnifying-glass"></i></button>
                            <input type="search" wire:model.live="search" class="form-control"
                                placeholder="Masukkan Nama/ID Karyawan">
                        </div>
                        @if ($data)
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Handphone</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Branch</th>
                                        <th>Departemen</th>
                                        <th>Jabatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $data->id_karyawan }}</td>
                                        <td>{{ $data->nama }}</td>
                                        <td>{{ $data->email }}</td>
                                        <td>{{ $data->hp }}</td>
                                        <td>{{ $data->tanggal_lahir }}</td>
                                        <td>{{ $data->branch }}</td>
                                        <td>{{ $data->departemen }}</td>
                                        <td>{{ $data->jabatan }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input wire:model="role" class="form-check-input" type="radio"
                                                value="1">
                                            <label class="form-check-label">
                                                <h5>User</h5>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input wire:model="role" class="form-check-input" type="radio"
                                                value="2">
                                            <label class="form-check-label">
                                                <h5>Admin</h5>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input wire:model="role" class="form-check-input" type="radio"
                                                value="3">
                                            <label class="form-check-label">
                                                <h5>Super Admin</h5>
                                            </label>
                                        </div>

                                    </div>

                                </div>
                            </div>

                            <div class="col-3">
                                <button wire:click="save" class="btn btn-primary">Save</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
