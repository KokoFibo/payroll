<div>
    <div class="col-xl-4 mx-auto pt-5">
        <div class="card">
            <div class="card-header bg-primary">
                <h3>Move Presensi Data</h3>
            </div>
            <div class="card-body">

                <div class="mb-3">
                    <select class="form-select" aria-label="Default select example" wire:model.live="getYear">
                        <option selected value=" ">Select year</option>
                        @foreach ($dataTahun as $item)
                            <option value="{{ $item }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                @if ($getYear)
                    <div class="mb-3">
                        <select class="form-select" aria-label="Default select example" wire:model.live="getMonth">
                            <option selected value=" ">Select Month</option>
                            @foreach ($dataBulan as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                {{-- @if ($dataBulan) --}}
                {{-- <p>Tahun: {{ $getYear }}, Bulan : {{ $getMonth }}</p> --}}
                {{-- @endif --}}
                @if ($getMonth != null && $getYear != null)
                    <p>Total data {{ $getMonth }} - {{ $getYear }} : {{ $totalData }} Data</p>
                    <p>Pastikan table "rekapbackup" tersedia</p>
                    <button class="btn btn-warning" wire:click="move">Move Data {{ $getMonth }} -
                        {{ $getYear }}</button>
                    <button class="btn btn-dark" wire:click="cancel">Cancel</button>
                @endif
            </div>
        </div>
    </div>


</div>
