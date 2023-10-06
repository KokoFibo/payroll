{{-- Gaji --}}
<div class="card">
    <div class="card-header bg-secondary ">
        <h5>Gaji</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Metode Penggajian</label>
                    <select class="form-select" aria-label="Default select example">
                        <option selected>Pilih metode penggajian</option>
                        <option value="0">Perjam</option>
                        <option value="1">Perhari</option>
                        <option value="2">Perbulan</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Gaji pokok</label>
                    <input type="number" class="form-control">
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Gaji overtime</label>
                    <input type="number" class="form-control">
                </div>
            </div>
        </div>


    </div>
</div>


{{-- Tunjangan --}}

<div class="card">
    <div class="card-header bg-secondary">
        <h5>Tunjangan</h5>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Uang makan</label>
                    <input type="number" class="form-control">
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Bonus</label>
                    <input type="number" class="form-control">
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Tunjangan Jabatan</label>
                    <input type="number" class="form-control">
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Tunjangan Bahasa</label>
                    <input type="number" class="form-control">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Tunjangan Skill</label>
                    <input type="number" class="form-control">
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Tunjangan Lembur Sabtu</label>
                    <input type="number" class="form-control">
                </div>
            </div>

            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Tunjangan Lama Kerja</label>
                    <input type="number" class="form-control">
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Potongan --}}
<div class="card">
    <div class="card-header bg-secondary">
        <h5>Potongan</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Hutang</label>
                    <input type="number" class="form-control">
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Potongan hutang</label>
                    <input type="number" class="form-control">
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Iuran air minum</label>
                    <input type="number" class="form-control">
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Denda</label>
                    <input type="number" class="form-control">
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Potongan seragam</label>
                    <input type="number" class="form-control">
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Potongan PPh21</label>
                    <input type="number" class="form-control">
                </div>
            </div>

            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Potongan BPJS</label>
                    <input type="number" class="form-control">
                </div>
            </div>
        </div>
    </div>
</div>


<button class="btn btn-primary">Save</button>
