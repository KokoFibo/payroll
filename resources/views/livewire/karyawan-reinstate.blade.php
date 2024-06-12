<div>
    <div class='col-4 mx-auto mt-5'>
        <div class="card">
            <div class="card-header bg-primary text-light">
                <h3>Reinstate Karyawan</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="id_karyawan" class="form-label">Id Karyawan</label>
                    <input wire:model='id_karyawan' type="text" class="form-control" id="id_karyawan" disabled>
                </div>
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input wire:model='nama' type="text" class="form-control" id="nama" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status Karyawan</label>
                    <select wire:model='status_karyawan' class="form-select" aria-label="Default select example">
                        <option selected>Pilih Status Karyawan</option>
                        <option value="PKWT">PKWT</option>
                        <option value="PKWTT">PKWTT</option>
                        <option value="Dirumahkan">Dirumahkan</option>
                    </select>
                    @error('status_karyawan')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <button wire:click="reinstate" class="btn btn-primary">Reinstate</button>
            </div>
        </div>
    </div>
</div>
