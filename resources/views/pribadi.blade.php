<div class="card">
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">ID Karyawan <span class="text-danger">*</span></label>
            <input wire:model="id_karyawan" type="number" class="form-control" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Karyawan <span class="text-danger">*</span></label>
            <input wire:model="nama" type="text" class="form-control @error('nama') is-invalid @enderror">
            @error('nama')
                <div class="invalid-feedback">
                    Nama karyawan harus diisi
                </div>
            @enderror


        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input wire:model="email" type="email" class="form-control @error('email') is-invalid @enderror">
            @error('email')
                <div class="invalid-feedback">
                    Format email salah.
                </div>
            @enderror
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Handphone</label>
                    <input wire:model="hp" type="text" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Telepon</label>
                    <input wire:model="telepon" type="text" class="form-control">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Tempat Lahir</label>
                    <input wire:model="tempat_lahir" type="text" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3 form-group">
                    <label class="form-label">Tanggal Lahir (mm/dd/yyyy)</label>
                    <input wire:model="tanggal_lahir" type="date" class="date form-control">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">

                <div class="mb-3">
                    <label class="form-label">Gender</label>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input wire:model="gender" class="form-check-input" type="radio" value="Laki-laki"
                                    name="flexRadioDefault" id="flexRadioDefault1">
                                <label class="form-check-label" for="flexRadioDefault1">
                                    Laki-laki
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input wire:model="gender" class="form-check-input" type="radio" value="Perempuan"
                                    name="flexRadioDefault" id="flexRadioDefault2" checked>
                                <label class="form-check-label" for="flexRadioDefault2">
                                    Perempuan
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Status Pernikahan</label>
                    <select wire:model="status_pernikahan" class="form-select" aria-label="Default select example">
                        <option>Pilih status pernikahan</option>
                        <option value="Belum Kawin">Belum Kawin</option>
                        <option value="Kawin">Kawin</option>
                        <option value="Cerai Hidup">Cerai Hidup</option>
                        <option value="Cerai Mati">Cerai Mati</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Golongan Darah</label>
                    <select wire:model="golongan_darah" class="form-select" aria-label="Default select example">
                        <option>Pilih golongan darah</option>
                        <option value="A+">A+</option>
                        <option value="B+">B+</option>
                        <option value="AB+">AB+</option>
                        <option value="O+">O+</option>
                        <option value="A-">A-</option>
                        <option value="B-">B-</option>
                        <option value="AB-">AB-</option>
                        <option value="O-">O-</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Agama</label>
                    <select wire:model="agama" class="form-select" aria-label="Default select example">
                        <option>Pilih agama</option>
                        <option value="Islam">Islam</option>
                        <option value="Kristen">Kristen Protestan</option>
                        <option value="Hindu">Hindu</option>
                        <option value="Budha">Budha</option>
                        <option value="Katolik">Katolik</option>
                        <option value="Konghucu">Konghucu</option>
                    </select>
                </div>
            </div>
        </div>






    </div>

</div>
