{{-- Gaji --}}
<div class="card mt-2">
    <div class="card-header bg-secondary ">
        <h5 class="text-light">Gaji</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Metode Penggajian</label>
                    <select class="form-select" aria-label="Default select example" wire:model="metode_penggajian">
                        <option>Pilih metode penggajian</option>
                        <option value="Perjam">Perjam</option>
                        <option value="Perbulan">Perbulan</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Gaji pokok</label>
                    <input wire:model="gaji_pokok" type="number" class="form-control">
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Gaji overtime</label>
                    <input wire:model="gaji_overtime" type="number" class="form-control">
                </div>
            </div>
        </div>


    </div>
</div>


{{-- Tunjangan --}}

<div class="card mt-2">
    <div class="card-header bg-secondary">
        <h5 class="text-light">Tunjangan</h5>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Uang makan</label>
                    <input wire:model="uang_makan" type="number" class="form-control">
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Bonus</label>
                    <input wire:model="bonus" type="number" class="form-control">
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Tunjangan Jabatan</label>
                    <input wire:model="tunjangan_jabatan" type="number" class="form-control">
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Tunjangan Bahasa</label>
                    <input wire:model="tunjangan_bahasa" type="number" class="form-control">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Tunjangan Skill</label>
                    <input wire:model="tunjangan_skill" type="number" class="form-control">
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Tunjangan Lembur Sabtu</label>
                    <input wire:model="tunjangan_lembur_sabtu" type="number" class="form-control">
                </div>
            </div>

            <div class="col-md-3">
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
                    <label class="form-label">Iuran air minum</label>
                    <input wire:model="iuran_air" type="number" class="form-control">
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
                    <label class="form-label">Potongan PPh21</label>
                    <input wire:model="potongan_pph21" type="number" class="form-control">
                </div>
            </div>
        </div>

        <div class="row">



            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Potongan BPJS</label>
                    <input wire:model="potongan_bpjs" type="number" class="form-control">
                </div>
            </div>
        </div>
    </div>
</div>
