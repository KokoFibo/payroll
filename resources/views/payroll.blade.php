{{-- Gaji --}}
<div wire:ignore.self class="card mt-2">



    @if (auth()->user()->role > 3 || $update == false)
        <div class="card-header bg-secondary ">
            <h5 class="text-light">Gaji</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Metode Penggajian <span class="text-danger">*</span></label>
                        <select class="form-select @error('metode_penggajian') is-invalid @enderror"
                            aria-label="Default select example" wire:model="metode_penggajian">
                            <option value=" ">Pilih metode penggajian</option>
                            <option value="Perjam">Perjam</option>
                            <option value="Perbulan">Perbulan</option>
                        </select>
                        @error('metode_penggajian')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                {{-- ====================================================== --}}
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Gaji pokok <span class="text-danger">*</span></label>
                        <input wire:model="gaji_pokok" type="number"
                            class="form-control @error('gaji_pokok') is-invalid @enderror">
                        @error('gaji_pokok')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Gaji Overtime <span class="text-danger">*</span></label>
                        <input wire:model="gaji_overtime" type="number"
                            class="form-control @error('gaji_overtime') is-invalid @enderror">
                        @error('gaji_overtime')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>



            </div>


        </div>
    @endif

    {{-- </div> --}}


    {{-- Tunjangan --}}

    <div class="card mt-2">
        <div class="card-header bg-secondary">
            <h5 class="text-light">Tunjangan</h5>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Bonus</label>
                        <input wire:model="bonus" type="number" class="form-control">

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Tunjangan Jabatan</label>
                        <input wire:model="tunjangan_jabatan" type="number" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Tunjangan Bahasa</label>
                        <input wire:model="tunjangan_bahasa" type="number" class="form-control">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Tunjangan Skill</label>
                        <input wire:model="tunjangan_skill" type="number" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Tunjangan Lembur Sabtu</label>
                        <input wire:model="tunjangan_lembur_sabtu" type="number" class="form-control">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Tunjangan Lama Kerja</label>
                        <input wire:model="tunjangan_lama_kerja" type="number" class="form-control">
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Potongan --}}
    <div class="card mt-2">
        <div class="card-header bg-secondary">
            <h5 class="text-light">Potongan</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Iuran air minum <span class="text-danger">*</span></label>
                        <input wire:model="iuran_air" type="number"
                            class="form-control @error('iuran_air') is-invalid @enderror">
                        @error('iuran_air')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Denda</label>
                        <input wire:model="denda" type="number" class="form-control">

                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Potongan seragam</label>
                        <input wire:model="potongan_seragam" type="number" class="form-control">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Gaji BPJS</label>
                        <input wire:model="gaji_bpjs" type="number" class="form-control">
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Nomor NPWP</label>
                        <input wire:model="no_npwp" type="number" class="form-control">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">PTKP </label>
                        <select class="form-select @error('ptkp') is-invalid @enderror"
                            aria-label="Default select example" wire:model="ptkp">
                            <option value=" ">Pilih PTKP</option>
                            <option value="TK0">TK/0</option>
                            <option value="TK1">TK/1</option>
                            <option value="TK2">TK/2</option>
                            <option value="TK3">TK/3</option>
                        </select @error('ptkp') <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label">Potongan BPJS</label>
                <div class="mb-3 d-flex gap-4">
                    <div class="form-check mt-2">
                        <input type="checkbox" wire:model="potongan_JHT" class="form-check-input"
                            {{ $potongan_JHT == 1 ? 'checked' : '' }}>
                        <label class="form-check-label">
                            JHT
                        </label>
                    </div>
                    <div class="form-check mt-2">
                        <input type="checkbox" wire:model="potongan_JP" class="form-check-input"
                            {{ $potongan_JP == 1 ? 'checked' : '' }}>
                        <label class="form-check-label">
                            JP
                        </label>
                    </div>
                    <div class="form-check mt-2">
                        <input type="checkbox" wire:model="potongan_JKK"
                            class="form-check-input @error('potongan_JKK') is-invalid @enderror""
                            {{ $potongan_JKK == 1 ? 'checked' : '' }}>
                        <label class="form-check-label">
                            JKK
                        </label>
                        @error('potongan_JKK')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-check mt-2">
                        <input type="checkbox" wire:model="potongan_JKM" class="form-check-input"
                            {{ $potongan_JKM == 1 ? 'checked' : '' }}>
                        <label class="form-check-label">
                            JKM
                        </label>
                    </div>

                    <div class="form-check mt-2">
                        <input type="checkbox" wire:model="potongan_kesehatan" class="form-check-input"
                            {{ $potongan_kesehatan == 1 ? 'checked' : '' }}>
                        <label class="form-check-label">
                            Kesehatan
                        </label>
                    </div>

                </div>
            </div>
        </div>

    </div>


</div>
</div>
