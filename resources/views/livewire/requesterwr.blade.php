<div>
    <div class="col-4 mx-auto mt-5">
        <div class="card">
            <div class="card-header bg-primary">
                <h3>Requester</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="requester" class="form-label">Requester User Id</label>
                    <input wire:model.live='requestId' type="text" class="form-control" id="requester">
                    <p>{{ $namaRequestId }}</p>
                </div>
                <div class="mb-3">
                    <label for="approval1" class="form-label">Approval1 User Id</label>
                    <input wire:model.live='approveBy1' type="text" class="form-control" id="approval1">
                </div>
                <div class="mb-3">
                    <label for="approval2" class="form-label">Approval2 User Id</label>
                    <input wire:model.live='approveBy2' type="text" class="form-control" id="approval2">
                </div>
            </div>
        </div>
    </div>
</div>
