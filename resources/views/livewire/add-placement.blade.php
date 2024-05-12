<div>
    <div class="col-6 mx-auto pt-5">
        <div>
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
        </div>
        <div class="card">
            <div class="card-header bg-primary d-flex justify-content-between">
                <div>
                    Add Placement
                </div>
                @if ($is_add)
                    <button wire:click='save' class="btn btn-success">Add</button>
                @else
                    <button wire:click='update' class="btn btn-success">Update</button>
                @endif
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Placement Name</label>
                    <input wire:model.live="placement_name" type="text"
                        class="form-control @error('placement_name') is-invalid @enderror"
                        id="exampleFormControlInput1">
                    @error('placement_name')
                        <div class="invalid-feedback">
                            <span class="text-danger"> {{ $message }}</span>
                        </div>
                    @enderror

                </div>
            </div>
        </div>
        <div>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Placement</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $d)
                        <tr>
                            <td>{{ $d->id }}</td>
                            <td>{{ $d->placement_name }}</td>
                            <td><button wire:click="edit({{ $d->id }})" class="btn btn-warning">Edit</button> |
                                <button wire:confirm="Are you sure you want to delete this placement?"
                                    wire:click='delete({{ $d->id }})' class="btn btn-danger">Delete</button>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>
