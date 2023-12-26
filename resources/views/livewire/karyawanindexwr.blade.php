<div>

    @section('title', 'Karyawan')
    @if (auth()->user()->role == 2 || auth()->user()->role == 3)
        <div x-data="{
            search: $persist(@entangle('search').live),
            columnName: $persist(@entangle('columnName').live),
            direction: $persist(@entangle('direction').live),
            selectStatus: $persist(@entangle('selectStatus').live),
            perpage: $persist(@entangle('perpage').live),
            page: $persist(@entangle('paginators.page').live),
        
        }">


        </div>
    @endif




    <div class="d-flex flex-column flex-xl-row gap-2 p-3 gap-xl-3 justify-content-end">

        <div class="col-xl-2 col-12">
            @if (Auth::user()->role > 3)
                <select wire:model.live="selected_company" class="form-select" aria-label="Default select example">
                    <option value="0"selected>{{ __('All Companies') }}</option>
                    <option value="1">{{ __('Pabrik 1') }}</option>
                    <option value="2">{{ __('Pabrik 2') }}</option>
                    <option value="3">{{ __('Kantor') }}</option>
                    <option value="4">ASB</option>
                    <option value="5">DPA</option>
                    <option value="6">YCME</option>
                    <option value="7">YEV</option>
                    <option value="8">YIG</option>
                    <option value="9">YSM</option>
                </select>
            @endif

        </div>
        <div class="col-12 col-xl-1">
            @if (Auth::user()->role > 3)
                <div class="col-12">
                    <button wire:click="excel" class="btn btn-success col-12">Excel</button></a>
                </div>
            @endif
        </div>
    </div>


    <div class="px-4">


        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-column flex-xl-row  justify-content-between  align-items-center">
                    <div class="col-12 col-xl-4">
                        <h3 class="fw-semibold fs-5 fwfs-3-xl">{{ __('Data Karyawan') }}</h3>
                    </div>
                    <div
                        class="col-12 d-flex flex-column flex-xl-row justify-content-end gap-xl-3 gap-2 col-12 col-xl-6">
                        <div class="col-12 col-xl-3">
                            <select class="form-select" wire:model.live="perpage">
                                {{-- <option selected>Open this select menu</option> --}}
                                <option value="10">10 {{ __('rows perpage') }}</option>
                                <option value="15">15 {{ __('rows perpage') }}</option>
                                <option value="20">20 {{ __('rows perpage') }}</option>
                                <option value="25">25 {{ __('rows perpage') }}</option>
                            </select>
                        </div>
                        <div class="col-12 col-xl-3">
                            <select wire:model.live="selectStatus" class="form-select"
                                aria-label="Default select example">
                                <option value="0">{{ __('All Status') }}</option>
                                <option value="1">{{ __('Aktif') }}</option>
                                <option value="2">{{ __('Non Aktif') }}</option>
                            </select>
                        </div>

                        <div class="col-12 col-xl-3">
                            <button wire:click="reset_filter" class="btn btn-success col-12">Reset</button>
                        </div>
                        <div class="col-12 col-xl-3">
                            <a href="/karyawancreate"><button class="btn btn-primary col-12"><i
                                        class="fa-solid fa-plus"></i>
                                    {{ __('Karyawan baru') }}</button></a>
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
            <div class="card-body">
                <div class="table-responsive">

                    <table class="table  table-sm  table-hover mb-2 ">
                        <thead>
                            <tr>
                                <th style="width: 50px; border-style: none;"></th>
                                <th style="width: 130px; border-style: none;">

                                    <input wire:model.live="search_id_karyawan" type="text" class="form-control"
                                        placeholder="{{ __('ID Karyawan') }}">
                                </th>
                                <th style="border-style: none;">
                                    <input wire:model.live="search_nama" type="text" class="form-control"
                                        placeholder="{{ __('Nama Karyawan') }}">
                                </th>
                                <th style="width: 130px; border-style: none;">
                                    <div style="width: 130px">
                                        <select wire:model.live="search_company" class="form-select"
                                            aria-label="Default select example">
                                            <option value="">{{ __('Company') }}</option>
                                            <option value="ASB">ASB</option>
                                            <option value="DPA">DPA</option>
                                            <option value="YCME">YCME</option>
                                            <option value="YEV">YEV</option>
                                            <option value="YIG">YIG</option>
                                            <option value="YSM">YSM</option>
                                        </select>
                                    </div>
                                </th>
                                <th style="width:130px; border-style: none;">
                                    <div style="width: 130px">
                                        <select wire:model.live="search_placement" class="form-select"
                                            aria-label="Default select example">
                                            <option value="">{{ __('Placement') }}</option>
                                            <option value="1">Pabrik 1</option>
                                            <option value="2">Pabrik 2</option>
                                            <option value="3">Kantor</option>
                                        </select>
                                    </div>
                                </th>
                                <th style="width: 200px; border-style: none;">
                                    <div style="width: 130px">
                                        <select wire:model.live="search_department" class="form-select"
                                            aria-label="Default select example">
                                            <option value="">{{ __('Department') }}</option>
                                            @foreach ($departments as $j)
                                                <option value="{{ $j->departemen }}">{{ $j->departemen }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </th>
                                <th style="width: 220px; border-style: none;">
                                    <div style="width: 130px">
                                        <select wire:model.live="search_jabatan"class="form-select"
                                            aria-label="Default select example">
                                            <option value="">{{ __('Jabatan') }}</option>
                                            @foreach ($jabatans as $j)
                                                <option value="{{ $j->jabatan }}">{{ $j->jabatan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </th>

                                @if (Auth::user()->role > 3)
                                    <th style="border-style: none;">
                                    </th>
                                @endif
                                <th style="border-style: none;">

                                </th>
                                @if (Auth::user()->role > 3)
                                    <th style="width: 150px; border-style: none;">
                                        <div style="width: 140px">
                                            <select wire:model.live="search_tanggal_bergabung" class="form-select"
                                                aria-label="Default select example">
                                                <option value="">{{ __('Lama Bekerja') }}</option>
                                                <option value="Asc">Asc</option>
                                                <option value="Desc">Desc</option>
                                            </select>
                                        </div>

                                    </th>
                </div>

                <th style="border-style: none;"></th>
                <th style="width: 150px; border-style: none;">
                    <div style="width: 130px">

                        <select wire:model.live="search_gaji_pokok" class="form-select"
                            aria-label="Default select example">
                            <option value="">{{ __('Gaji Pokok') }}</option>

                            <option value="Asc">Asc</option>
                            <option value="Desc">Desc</option>


                        </select>
                </th>
            </div>

            <th style="width: 150px; border-style: none;">
                <div style="width: 140px">
                    <select wire:model.live="search_gaji_overtime" class="form-select"
                        aria-label="Default select example">
                        <option value="">{{ __('Gaji Overtime') }}</option>
                        <option value="Asc">Asc</option>
                        <option value="Desc">Desc</option>
                    </select>
            </th>
        </div>

        <th style="width: 150px; border-style: none;">
            <button wire:click="excelByDepartment" class="btn btn-success btn-sm mb-1"
                @if ($search_placement == null || $search_department == null) disabled @endif>Excel by Departement</button>
        </th>
        @endif


        </tr>
        <tr>
            <th></th>
            <th wire:click="sortColumnName('id_karyawan')">{{ __('ID Karyawan') }}
            </th>
            <th wire:click="sortColumnName('nama')">{{ __('Nama') }} </th>
            <th class="text-center" wire:click="sortColumnName('company')">
                {{ __('Company') }} </th>
            <th class="text-center" wire:click="sortColumnName('placement')">
                {{ __('Placement') }}

            </th>
            <th class="text-center" wire:click="sortColumnName('departemen')">
                {{ __('Departemen') }}
            </th>
            <th class="text-center" wire:click="sortColumnName('jabatan')">
                {{ __('Jabatan') }} </th>
            @if (Auth::user()->role > 3)
                <th class="text-center" wire:click="sortColumnName('level_jabatan')">
                    {{ __('Level Jabatan') }}
            @endif
            </th>
            <th class="text-center" wire:click="sortColumnName('status_karyawan')">
                {{ __('Status') }}
            </th>
            @if (Auth::user()->role > 3)
                <th class="text-center" wire:click="sortColumnName('tanggal_bergabung')">
                    {{ __('Lama Bekerja') }}
                </th>
            @endif
            <th class="text-center" wire:click="sortColumnName('metode_penggajian')">
                {{ __('Metode Penggajian') }}
            </th>
            <th class="text-center" wire:click="sortColumnName('gaji_pokok')">
                {{ __('Gaji Pokok') }}
            </th>
            <th class="text-center" wire:click="sortColumnName('gaji_overtime')">
                {{ __('Overtime') }}
            </th>
            <th class="text-center" wire:click="sortColumnName('iuran_air')">
                {{ __('Iuran Air') }}
            </th>
            <th class="text-center" wire:click="sortColumnName('iuran_locker')">
                {{ __('Iuran Locker') }}
            </th>
            @if ($selectStatus == 2 && auth()->user()->role > 3)
                <th class="text-center">
                    {{ __('Lama bekerja') }}
                </th>
            @endif


        </tr>
        </thead>
        <tbody>
            @foreach ($datas as $data)
                <tr>
                    <td>
                        <div class="text-start">
                            <a href="/karyawanupdate/{{ $data->id }}"><button class="btn btn-success btn-sm"><i
                                        class="fa-regular fa-pen-to-square"></i></button></a>


                            @if (Auth::user()->role > 4)
                                <button wire:click="delete(`{{ $data->id }}`)"
                                    wire:confirm.prompt="Yakin mau di delete?\n\nKetik DELETE untuk konfirmasi|DELETE"
                                    class="btn btn-danger btn-sm"><i class="fa-solid fa-trash-can"></i></button>
                            @endif
                        </div>
                    </td>
                    <td>{{ $data->id_karyawan }}</td>
                    <td>{{ $data->nama }}</td>
                    <td class="text-center">{{ $data->company }}</td>
                    <td class="text-center">{{ $data->placement }}</td>
                    <td class="text-center">{{ $data->departemen }}</td>
                    <td class="text-center">{{ $data->jabatan }}</td>
                    @if (Auth::user()->role > 3)
                        <td class="text-center">{{ $data->level_jabatan }}</td>
                    @endif
                    <td class="text-center">{{ $data->status_karyawan }}</td>
                    @if (
                        (auth()->user()->role == 2 && $data->gaji_pokok <= 4500000) ||
                            (auth()->user()->role == 3 && $data->gaji_pokok <= 10000000) ||
                            auth()->user()->role > 3)
                        @if (Auth::user()->role > 3)
                            <td class="text-center">{{ lamaBekerja($data->tanggal_bergabung) }}</td>
                        @endif
                        <td class="text-center">{{ $data->metode_penggajian }}</td>
                        <td class="text-center">{{ number_format($data->gaji_pokok) }}</td>
                        <td class="text-center">{{ number_format($data->gaji_overtime) }}</td>
                        <td class="text-center">{{ number_format($data->iuran_air) }}</td>
                        <td class="text-center">{{ number_format($data->iuran_locker) }}</td>
                        {{-- <td class="text-center">{{ format_tgl($data->tanggal_bergabung) }}</td> --}}
                    @endif
                    @if ($selectStatus == 2 && auth()->user()->role > 3)
                        <td class="text-center">
                            {{ lama_resign($data->tanggal_bergabung, $data->tanggal_resigned, $data->tanggal_blacklist) }}
                        </td>
                    @endif


                </tr>
            @endforeach
        </tbody>
        </table>
        {{ $datas->onEachSide(0)->links() }}
        {{-- {{ $datas->links() }} --}}
    </div>
</div>
</div>
</div>
</div>


{{-- </div> --}}
