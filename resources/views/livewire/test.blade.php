<div>
    <p>Etnis : {{ $etnis }}</p>
    <div class="col-2 pt-3">
        <select class="form-select" aria-label="Default select example" wire:model.live="etnis">
            <option selected>Open this select menu</option>
            <option value="lainnya">lainnya</option>
            <option value="lainnnya">lainnnya</option>
            <option value="kosong">Kosong</option>
        </select>
    </div>
    <table class="table mt-5">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Etnis</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $d)
                <tr>
                    <td>{{ $d->id_karyawan }}</td>
                    <td>{{ $d->nama }}</td>
                    <td>{{ $d->etnis }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $data->links() }}
    <button wire:click='change' class="btn btn-primary">Change</button>

</div>
