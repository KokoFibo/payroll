<div class="card">

    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Jenis Identitas</label>
            <select class="form-select" aria-label="Default select example" wire:model="jenis_identitas">
                <option>Pilih jenis Identitas</option>
                <option value="KTP">KTP</option>
                <option value="Passport">Passport</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Nomor Identitas</label>
            <input type="number" class="form-control" wire:model="no_identitas">
        </div>
        <div class="mb-3">
            <label class="form-label">Alamat Identitas</label>
            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" wire:model="alamat_identitas"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Alamat Tinggal Sekarang</label>
            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" wire:model="alamat_tinggal"></textarea>
        </div>
    </div>
</div>
