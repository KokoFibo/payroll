<div>
    <div>
        <div class="container">
            <div class="mx-auto  mt-4">
                <button class="mx-auto col-12 btn btn-primary btn-large">
                    <h3 class="px-3">Karyawan Settings</h3>
                </button>
                <div class="card mt-5  mx-auto">
                    <div class="card-header">
                        <h5>Reset Password Karyawan</h5>
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
                            <div class="col-3">
                                <button wire:click="resetPassword" class="btn btn-primary"> Reset Password</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
