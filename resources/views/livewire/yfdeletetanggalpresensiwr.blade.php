<div>

    <div class="card col-3 mx-auto">
        <div class="card-header bg-danger text-light">
            <h3>Delete Tanggal Presensi</h3>
        </div>
        <div class="card-body">
            <label class="form-label">Email address</label>
            <input wire:model="tanggal" type="date" class="form-control">
            <div class="form-text">Masukkan tanggal yang akan di delete</div>

            <button class="btn btn-danger mt-3" onclick="return confirm('Yakin data nya akan dihapus ?');"
                wire:click="delete">Delete</button>
            <button class="btn btn-dark mt-3" wire:click="exit">Exit</button>
        </div>
    </div>
    <div class="card col-6 mx-auto">
        <div class="card-header bg-danger text-light text-center">
            <h3>Delete Tanggal Presensi By Lokasi Pabrik</h3>
        </div>
        <div class="card-body">
            <label class="form-label">Pilih Pabrik</label>
            <select wire:model="lokasi" class="form-select form-select-lg mb-3" aria-label="Large select example">
                <option value=" ">Open this select menu</option>
                <option value="0">Kantor</option>
                <option value="1">Pabrik 1</option>
                <option value="2">Pabrik 2</option>
            </select>
            <label class="form-label">Email address</label>
            <input wire:model="tanggal" type="date" class="form-control">
            <div class="form-text">Masukkan tanggal yang akan di delete</div>
            <button class="btn btn-danger mt-3" onclick="return confirm('Yakin data nya akan dihapus ?');"
                wire:click="deleteByPabrik">Delete</button>
            <button class="btn btn-dark mt-3" wire:click="exit">Exit</button>
        </div>

    </div>



    @include('toastr')

</div>
