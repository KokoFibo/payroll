<div class="container mt-4" style="max-width: 420px;">

    <div class="card shadow-sm border-0 rounded-4">

        <div class="card-body">

            <h5 class="fw-bold mb-2">
                ⚠️ Reset Data Presensi
            </h5>

            <p class="text-muted small mb-4">
                Aksi ini akan menghapus <b>No Scan</b> dan <b>No Scan History</b>
                pada tanggal yang dipilih. Tidak bisa dibatalkan.
            </p>

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Pilih Tanggal
                </label>
                <input type="date" wire:model="tanggal" class="form-control rounded-3">
            </div>

            <button wire:click="delete" wire:loading.attr="disabled"
                onclick="confirm('Yakin mau reset data di tanggal ini? Data tidak bisa dikembalikan.') || event.stopImmediatePropagation()"
                class="btn btn-danger w-100 rounded-3 fw-semibold">
                <span wire:loading.remove>🗑️ Reset Data</span>
                <span wire:loading>Processing...</span>
            </button>

        </div>
    </div>

</div>
