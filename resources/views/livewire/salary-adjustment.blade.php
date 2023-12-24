<div>
    <div class="d-flex  align-items-center py-2">
        <div class="col-12 col-lg-2">
            <select class="form-select" wire:model.live="pilihLamaKerja">
                <option value=" ">{{ __('Pilih lama bekerja') }}</option>
                <option value="90">{{ __('3 bulan') }}</option>
                <option value="120">{{ __('4 Bulan') }}</option>
                <option value="150">{{ __('5 Bulan') }}</option>
                <option value="180">{{ __('6 Bulan') }}</option>
                <option value="210">{{ __('7 Bulan') }}</option>
              </select>
        </div>
        <h4 class="mx-auto">{{ __('Penyesuaian Gaji') }}</h4>
       
    </div>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>{{ __('ID') }}</th>
                <th>{{ __('Nama') }}</th>
                <th>{{ __('Company') }}</th>
                <th>{{ __('Departemen') }}</th>
                <th>{{ __('Jabatan') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Tanggal Bergabung') }}</th>
                <th>{{ __('Lama Bekerja') }}</th>
                <th>{{ __('Gaji Pokok') }}</th>
                <th>{{ __('Gaji Rekomendasi') }} : Rp {{ number_format($gaji_rekomendasi) }}</th>
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
                    <td>{{ $d->tanggal_bergabung }}</td>

                    <td>{{ number_format(lama_bekerja($d->tanggal_bergabung, $today)) }}</td>
                    <td>{{ number_format($d->gaji_pokok) }}</td>
                    <td class="text-center">
                        <button wire:click="sesuaikan(`{{ $d->id }}`)" class="btn btn-primary btn-sm">Sesuaikan</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $data->links() }}
</div>
