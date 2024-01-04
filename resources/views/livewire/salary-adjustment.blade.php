<div>
    <div class="d-flex flex-column-reverse flex-lg-row align-items-center pt-2">
        <div class="col-12 col-lg-2">
            <select class="form-select" wire:model.live="pilihLamaKerja">
                {{-- <option value=" ">{{ __('Pilih lama bekerja') }}</option> --}}
                <option value="3">{{ __('3 bulan') }}</option>
                <option value="4">{{ __('4 Bulan') }}</option>
                <option value="5">{{ __('5 Bulan') }}</option>
                <option value="6">{{ __('6 Bulan') }}</option>
                <option value="7">{{ __('7 Bulan') }}</option>
                <option value="8">{{ __('8 Bulan') }}</option>
                {{-- <option value="9">{{ __('9 Bulan') }}</option> --}}
            </select>
        </div>
        <h3 class="mx-auto">{{ __('Penyesuaian Gaji') }}</h3>

    </div>
    <div class="table-responsive p-3">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('ID Karyawan') }}</th>
                    <th>{{ __('Nama') }}</th>
                    <th>{{ __('Company') }}</th>
                    <th>{{ __('Departemen') }}</th>
                    <th>{{ __('Jabatan') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Tanggal Bergabung') }}</th>
                    <th>{{ __('Lama Bekerja') }}</th>
                    <th>{{ __('Gaji Pokok') }}</th>
                    <th>{{ __('Gaji Rekomendasi') }} : Rp {{ number_format($gaji_rekomendasi) }}</th>
                    {{-- <th></th> --}}
                    <th></th>
                </tr>
            </thead>
            <tbody>

                @foreach ($data as $d)
                    <tr>
                        <td>{{ $d->id_karyawan }}</td>
                        <td>{{ $d->nama }}</td>
                        <td>{{ $d->company }}</td>
                        <td>{{ $d->departemen }}</td>
                        <td>{{ $d->jabatan }}</td>
                        <td>{{ $d->status_karyawan }}</td>
                        <td>{{ format_tgl($d->tanggal_bergabung) }}</td>
                        <td>{{ number_format(lama_bekerja($d->tanggal_bergabung, $today)) }}</td>
                        <td>{{ number_format($d->gaji_pokok) }}</td>
                        <td class="text-center">
                            @if (auth()->user()->role >= 3)
                                <button data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                                    wire:click="edit(`{{ $d->id }}`)" @click="open_modal()"
                                    class="btn btn-warning btn-sm">Edit</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $data->links() }}
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Penyesuaian Gaji Karyawan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <div class="d-flex flex-column">
                            <p class="form-label">Nama : {{ $nama }} </p>
                            <p class="form-label">Rekomendasi : Rp{{ number_format($gaji_rekomendasi) }} </p>
                        </div>
                        <div class="d-flex gap-2 mt-2 align-items-center">
                            <input type="text" class="form-control" value="{{ number_format($gaji_pokok) }}">
                            <span>=></span>
                            <input type="text" class="form-control" wire:model.live="gaji" type-currency="IDR">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                        wire:confirm="Yakin mau dirubah menjadi Rp{{ number_format(convert_numeric($gaji)) }}?"
                        wire:click="save">Save</button>
                </div>
            </div>
        </div>
    </div>
    <style>
        td,
        th {
            white-space: nowrap;
        }
    </style>
</div>
