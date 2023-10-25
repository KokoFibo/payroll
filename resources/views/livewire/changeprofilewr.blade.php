<div>
    <div class="container">

        <div class="mx-auto col-8 mt-4">
            {{-- <h5>
                Hello, {{ namaDiAside($name) }} selamat datang di menu User Setting.
            </h5> --}}
            <button class="mx-auto col-12 btn btn-success btn-large">
                <h3 class="px-3">User Settings</h3>
            </button>
        </div>

        <div class="card mt-5 col-8 mx-auto">
            <div class="card-header">
                <h5>Rubah Nama Profile</h5>
            </div>
            <div class="card-body">
                <div class="input-group mb-3">
                    <input wire:model="name" type="text" class="form-control" placeholder="Nama Profile">
                </div>
                <button wire:click="SaveName" class="btn btn-outline-success">Simpan</button>
            </div>
        </div>

        <div class="card mt-5 col-8 mx-auto">
            <div class="card-header">
                <h5>Rubah Email</h5>
            </div>
            <div class="card-body">
                <div class="input-group mb-3">
                    <input wire:model="email" type="email" class="form-control @error('email') is-invalid @enderror">

                    @error('email')
                        <div class="invalid-feedback">
                            Format email salah.
                        </div>
                    @enderror
                </div>

                <button wire:click="changeEmail" class="btn btn-outline-success">Simpan</button>
            </div>
        </div>
        <div class="card mt-5 col-8 mx-auto">
            <div class="card-header">
                <h5>Rubah Password</h5>
            </div>
            <div class="card-body">
                <div class="input-group mb-3">
                    <input wire:model="old_password" type="password"
                        class="form-control @error('old_password') is-invalid @enderror" placeholder="Password lama">
                    @error('old_password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="input-group mb-3">
                    <input wire:model="new_password" type="password"
                        class="form-control @error('new_password') is-invalid @enderror" placeholder="Password baru">
                    @error('new_password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="input-group mb-3">
                    <input wire:model="confirm_password" type="password"
                        class="form-control @error('confirm_password') is-invalid @enderror"
                        placeholder="Konfirmasi password">
                    @error('confirm_password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <button wire:click="changePassword" class="btn btn-outline-success">Simpan</button>

            </div>


        </div>
        <div class="card mt-5 col-8 mx-auto">
            <div class="card-header">
                <h5>Rubah Bahasa</h5>
            </div>
            <div class="card-body">
                <div class="form-check mb-2">
                    <input wire:model="language" value="Id" class="form-check-input" type="radio"
                        name="flexRadioDefault" id="flexRadioDefault1">
                    <label class="form-check-label" for="flexRadioDefault1">Indonesia</label>
                </div>
                <div class="form-check mb-4">
                    <input wire:model="language" value="Cn" class="form-check-input" type="radio"
                        name="flexRadioDefault" id="flexRadioDefault1">
                    <label class="form-check-label" for="flexRadioDefault1">Mandarin</label>
                </div>

                <button wire:click="changeLanguage" class="btn btn-outline-success">Simpan</button>

            </div>


        </div>
    </div>
</div>
</div>
