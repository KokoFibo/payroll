<div class="card">

    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Status Karyawan</label>
            <select class="form-select" aria-label="Default select example" wire:model="status_karyawan">
                <option>Pilih status karyawan</option>
                <option value="Karyawan Tetap">Karyawan Tetap</option>
                <option value="PKWT">PKWT</option>
                <option value="Dirumahkan">Dirumahkan</option>
                <option value="Resign">Resign</option>
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
                <option value="All">All</option>
                <option value="YCME">YCME</option>
                <option value="YIG">YIG</option>
                <option value="YSM">YSM</option>
                <option value="YNE">YNE</option>
                <option value="DPA">DPA</option>
                <option value="ASB">ASB</option>
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
                <option value="GD">GD</option>
                <option value="Gudang">Gudang</option>
                <option value="HR">HR</option>
                <option value="Legal">Legal</option>
                <option value="Pabrik">Pabrik</option>
                <option value="PMC">PMC</option>
                <option value="PMC">PMC</option>
                <option value="Procurement">Procurement</option>
                <option value="Production">Production</option>
                <option value="Produksi Packing">Produksi Packing</option>
                <option value="Produksi">Produksi</option>
                <option value="QC">QC</option>
                <option value="Repair">Repair</option>
                <option value="STW">STW</option>
                <option value="Yifang">Yifang</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Jabatan</label>
            <select class="form-select" aria-label="Default select example" wire:model="jabatan">
                <option>Pilih jabatan</option>
                <option value="Manager">Manager</option>
                <option value="Direktur">Direktur</option>
                <option value="Presiden direktur">Presiden direktur</option>
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
