<div class="container">
    {{-- Header --}}
    <div class="card mt-5 my-3">
        <div class="card-header" style="background-color: #466deb; color: white">
            <p class="text-center">{{ __('Placement Report') }} </p>

        </div>
        <div class="card-body" style="background-color: #b7c6f8; color: black">

            <div class="d-flex  flex-lg-row justify-content-evenly">
                <div class="d-flex gap-5 gap-lg-0 flex-row flex-lg-column text-center">
                    <h4 class="text-center">{{ __('Jumlah Karyawan') }}</h4>
                    <h5 class="text-center">{{ $jumlah_karyawan }}</h5>
                </div>
                <div class="d-flex gap-5 gap-lg-0 flex-row flex-lg-column text-center">
                    <h4 class="">{{ __('Laki laki') }}</h4>
                    <h5 class="">{{ $jumlah_laki_laki }}</h5>
                </div>
                <div class="d-flex gap-5 gap-lg-0 flex-row flex-lg-column text-center">
                    <h4 class="">{{ __('Perempuan') }}</h4>
                    <h5 class="">{{ $jumlah_perempuan }}</h5>
                </div>
                <div class="d-flex gap-5 gap-lg-0 flex-row flex-lg-column text-center">
                    <h4 class="">{{ __('Shift Malam') }}</h4>
                    <h5 class="">{{ $jumlah_shift_malam }}</h5>
                </div>
            </div>
        </div>

        <hr class="border border-primary  opacity-50">
        <div class="">
            <div class="px-4 d-flex align-items-center">
                <input type="date" wire:model.live='last_date' class="form-control  col-3 mr-5" id="exampleFormControlInput1">
                <span>{{ __('Pilih Placement dibawah untuk lihat detail') }}</span>

                <button wire:loading wire:target='placement' class="btn btn-primary " type="button">
                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                </button>
                <button wire:loading wire:target='last_date' class="btn btn-primary " type="button">
                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                </button>
            </div>
            <div class="d-flex justify-content-evenly my-3">
                <div class="form-check">
                    <input wire:model.live='placement' class="form-check-input" type="radio" id="YCME" value="YCME">
                    <label class="form-check-label" for="YCME">
                        YCME
                    </label>
                </div>
                <div class="form-check">
                    <input wire:model.live='placement' class="form-check-input" type="radio" id="YAM" value="YAM">
                    <label class="form-check-label" for="YAM">
                        YAM
                    </label>
                </div>
                <div class="form-check">
                    <input wire:model.live='placement' class="form-check-input" type="radio" id="YIG" value="YIG">
                    <label class="form-check-label" for="YIG">
                        YIG
                    </label>
                </div>
                <div class="form-check">
                    <input wire:model.live='placement' class="form-check-input" type="radio" id="YSM" value="YSM">
                    <label class="form-check-label" for="YSM">
                        YSM
                    </label>
                </div>
                <div class="form-check">
                    <input wire:model.live='placement' class="form-check-input" type="radio" id="YEV" value="YEV">
                    <label class="form-check-label" for="YEV">
                        YEV
                    </label>
                </div>
                <div class="form-check">
                    <input wire:model.live='placement' class="form-check-input" type="radio" id="YEV_SMOOT" value="YEV SMOOT">
                    <label class="form-check-label" for="YEV_SMOOT">
                        YEV SMOOT
                    </label>
                </div>
                <div class="form-check">
                    <input wire:model.live='placement' class="form-check-input" type="radio" id="YEV_OFFERO" value="YEV OFFERO">
                    <label class="form-check-label" for="YEV_OFFERO">
                        YEV OFFERO
                    </label>
                </div>
                <div class="form-check">
                    <input wire:model.live='placement' class="form-check-input" type="radio" id="YEV_SUNRA" value="YEV SUNRA">
                    <label class="form-check-label" for="YEV_SUNRA">
                        YEV SUNRA
                    </label>
                </div>
                <div class="form-check">
                    <input wire:model.live='placement' class="form-check-input" type="radio" id="YEV_AIMA" value="YEV AIMA">
                    <label class="form-check-label" for="YEV_AIMA">
                        YEV AIMA
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- @if ($placement != '') --}}
{{-- Penempatan Bagian Karyawan Pabrik --}}
<div class="card my-3">
    <div class="card-header" style="background-color: #608ed3; color: white">
        <h2 class="py-1 text-center">{{ __('Penempatan Bagian Karyawan Pabrik') }}</h2>
    </div>
    <div class="card-body">
        <div>
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('Shift') }}</th>
                        <th class="text-end">{{ __('Produksi') }}</th>
                        <th class="text-end">{{ __('Quality Control') }}</th>
                        <th class="text-end">{{ __('Gudang') }}</th>
                        <th class="text-end">{{ __('Engineering') }}</th>
                        <th class="text-end">{{ __('GA') }}</th>
                        <th class="text-end">{{ __('Exim') }}</th>
                        <th class="text-end">{{ __('BD') }}</th>
                        <th class="text-end">{{ __('Procurement') }}</th>
                        <th class="text-end">{{ __('Total') }}</th>


                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ __('Shift Pagi') }}</td>
                        <td class="text-end">{{ $shift_pagi_produksi }}</td>
                        <td class="text-end">{{ $shift_pagi_quality_control }}</td>
                        <td class="text-end">{{ $shift_pagi_gudang }}</td>
                        <td class="text-end">{{ $shift_pagi_engineering }}</td>
                        <td class="text-end">{{ $shift_pagi_ga }}</td>
                        <td class="text-end">{{ $shift_pagi_exim }}</td>
                        <td class="text-end">{{ $shift_pagi_bd }}</td>
                        <td class="text-end">{{ $shift_pagi_procurement }}</td>
                        <td class="text-end">{{ $shift_pagi_total }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('Shift Malam') }}</td>
                        <td class="text-end">{{ $shift_malam_produksi }}</td>
                        <td class="text-end">{{ $shift_malam_quality_control }}</td>
                        <td class="text-end">{{ $shift_malam_gudang }}</td>
                        <td class="text-end">{{ $shift_malam_engineering }}</td>
                        <td class="text-end">{{ $shift_malam_ga }}</td>
                        <td class="text-end">{{ $shift_malam_exim }}</td>
                        <td class="text-end">{{ $shift_malam_bd }}</td>
                        <td class="text-end">{{ $shift_pagi_procurement }}</td>
                        <td class="text-end">{{ $shift_malam_total }}</td>
                    </tr>

                </tbody>
            </table>
            <div>
                <h6>Produksi + Teknisi + Office</h6>
                <h6>QC + QC AGING</h6>
            </div>
        </div>
    </div>
</div>


{{-- Posisi karyawan resign per minggu --}}
<div class="card my-3">
    <div class="card-header" style="background-color: #608ed3; color: white">
        <h2 class="py-1 text-center">{{ __('Posisi karyawan resign per minggu') }}</h2>
    </div>
    <div class="card-body">
        <div>
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-end">{{ __('Produksi') }}</th>
                        <th class="text-end">{{ __('Quality Control') }}</th>
                        <th class="text-end">{{ __('Gudang') }}</th>
                        <th class="text-end">{{ __('Engineering') }}</th>
                        <th class="text-end">{{ __('GA') }}</th>
                        <th class="text-end">{{ __('Exim') }}</th>
                        <th class="text-end">{{ __('BD') }}</th>
                        <th class="text-end">{{ __('Procurement') }}</th>
                        <th class="text-end">{{ __('Total') }}</th>


                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-end">{{ $shift_pagi_produksi }}</td>
                        <td class="text-end">{{ $shift_pagi_quality_control }}</td>
                        <td class="text-end">{{ $shift_pagi_gudang }}</td>
                        <td class="text-end">{{ $shift_pagi_engineering }}</td>
                        <td class="text-end">{{ $shift_pagi_ga }}</td>
                        <td class="text-end">{{ $shift_pagi_exim }}</td>
                        <td class="text-end">{{ $shift_pagi_bd }}</td>
                        <td class="text-end">{{ $shift_pagi_procurement }}</td>
                        <td class="text-end">{{ $shift_pagi_total }}</td>
                    </tr>


                </tbody>
            </table>

        </div>
    </div>
</div>


{{-- Posisi karyawan baru per minggu --}}
<div class="card my-3">
    <div class="card-header" style="background-color: #608ed3; color: white">
        <h2 class="py-1 text-center">{{ __('Posisi karyawan baru per minggu') }}</h2>
    </div>
    <div class="card-body">
        <div>
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-end">{{ __('Produksi') }}</th>
                        <th class="text-end">{{ __('Quality Control') }}</th>
                        <th class="text-end">{{ __('Gudang') }}</th>
                        <th class="text-end">{{ __('Engineering') }}</th>
                        <th class="text-end">{{ __('GA') }}</th>
                        <th class="text-end">{{ __('Exim') }}</th>
                        <th class="text-end">{{ __('BD') }}</th>
                        <th class="text-end">{{ __('Procurement') }}</th>
                        <th class="text-end">{{ __('Total') }}</th>


                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-end">{{ $shift_pagi_produksi }}</td>
                        <td class="text-end">{{ $shift_pagi_quality_control }}</td>
                        <td class="text-end">{{ $shift_pagi_gudang }}</td>
                        <td class="text-end">{{ $shift_pagi_engineering }}</td>
                        <td class="text-end">{{ $shift_pagi_ga }}</td>
                        <td class="text-end">{{ $shift_pagi_exim }}</td>
                        <td class="text-end">{{ $shift_pagi_bd }}</td>
                        <td class="text-end">{{ $shift_pagi_procurement }}</td>
                        <td class="text-end">{{ $shift_pagi_total }}</td>
                    </tr>


                </tbody>
            </table>

        </div>
    </div>
</div>


{{-- Karyawan cuti/izin --}}
<div class="card my-3">
    <div class="card-header" style="background-color: #608ed3; color: white">
        <h2 class="py-1 text-center">{{ __('Karyawan cuti/izin') }}</h2>
    </div>
    <div class="card-body">
        <div>
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-end">{{ __('Produksi') }}</th>
                        <th class="text-end">{{ __('Quality Control') }}</th>
                        <th class="text-end">{{ __('Gudang') }}</th>
                        <th class="text-end">{{ __('Engineering') }}</th>
                        <th class="text-end">{{ __('GA') }}</th>
                        <th class="text-end">{{ __('Exim') }}</th>
                        <th class="text-end">{{ __('BD') }}</th>
                        <th class="text-end">{{ __('Procurement') }}</th>
                        <th class="text-end">{{ __('Total') }}</th>


                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-end">{{ $shift_pagi_produksi }}</td>
                        <td class="text-end">{{ $shift_pagi_quality_control }}</td>
                        <td class="text-end">{{ $shift_pagi_gudang }}</td>
                        <td class="text-end">{{ $shift_pagi_engineering }}</td>
                        <td class="text-end">{{ $shift_pagi_ga }}</td>
                        <td class="text-end">{{ $shift_pagi_exim }}</td>
                        <td class="text-end">{{ $shift_pagi_bd }}</td>
                        <td class="text-end">{{ $shift_pagi_procurement }}</td>
                        <td class="text-end">{{ $shift_pagi_total }}</td>
                    </tr>


                </tbody>
            </table>
        </div>
    </div>
</div>
{{-- @endif --}}
</div>