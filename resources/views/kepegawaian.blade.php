<div class="card">

    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Status Karyawan</label>
            <select class="form-select" aria-label="Default select example" wire:model="status_karyawan">
                <option>Pilih status karyawan</option>
                <option value="Karyawan Tetap">Karyawan Tetap</option>
                <option value="PKWT">PKWT</option>
                <option value="PKWTT">PKWTT</option>
                <option value="Dirumahkan">Dirumahkan</option>
                <option value="Resigned">Resigned</option>
                <option value="Kabur">Kabur</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal Bergabung</label>
            <input type="date" class="date form-control" placeholder="dd-mm-yyyy" wire:model="tanggal_bergabung">
        </div>
        <div class="mb-3">
            <label class="form-label">Branch</label>
            <select class="form-select" aria-label="Default select example" wire:model="branch">
                <option>Pilih branch</option>
                <option value="ASB">ASB</option>
                <option value="DPA">DPA</option>
                <option value="YCME">YCME</option>
                <option value="YIG">YIG</option>
                <option value="YSM">YSM</option>

            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Departemen</label>
            <select class="form-select" aria-label="Default select example" wire:model="departemen">
                <option>Pilih departemen</option>
                <option value="BD">BD</option>
                <option value="Engineering">Engineering</option>
                <option value="EXIM">EXIM</option>
                <option value="Finance Accounting">Finance Accounting</option>
                <option value="GA">GA</option>
                <option value="Gudang">Gudang</option>
                <option value="HR">HR</option>
                <option value="Legal">Legal</option>
                <option value="Procurement">Procurement</option>
                <option value="Produksi">Produksi</option>
                <option value="Quality Control">Quality Control</option>
                <option value="Yifang">Yifang</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Jabatan</label>
            <select class="form-select" aria-label="Default select example" wire:model="jabatan">
                <option>Pilih jabatan</option>
                <option value="Admin">Admin</option>
                <option value="Asisten Direktur">Asisten Direktur</option>
                <option value="Asisten Kepala">Asisten Kepala</option>
                <option value="Asisten Manager">Asisten Manager</option>
                <option value="Asisten Pengawas">Asisten Pengawas</option>
                <option value="Asisten Wakil Presiden">Asisten Wakil Presiden</option>
                <option value="Design Grafis">Design Grafis</option>
                <option value="Director">Director</option>
                <option value="Kepala">Kepala</option>
                <option value="Manager">Manager</option>
                <option value="Pengawas">Pengawas</option>
                <option value="President">President</option>
                <option value="Senior Staff">Senior Staff</option>
                <option value="Staff">Staff</option>
                <option value="Supervisor">Supervisor</option>
                <option value="Vice President">Vice President</option>

            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Level Jabatan</label>
            <select class="form-select" aria-label="Default select example" wire:model="level_jabatan">
                <option>Pilih level jabatan</option>
                <option value="M1">M1</option>
                <option value="M2">M2</option>
                <option value="M3">M3</option>
                <option value="M4">M4</option>
                <option value="M5">M5</option>
                <option value="M6">M6</option>
                <option value="M7">M7</option>
                <option value="M8">M8</option>
                <option value="M9">M9</option>
                <option value="M10">M10</option>
                <option value="M11">M11</option>
                <option value="M12">M12</option>
            </select>
        </div>
    </div>
</div>
