<div>
    @section('title', 'Payroll')
    <div class="pt-2">
        <div class="">
            <h4 class="text-center text-bold mb-3">Yifang Payroll</h4>

            <div class="d-flex  flex-column gap-2 flex-xl-row align-items-center justify-content-between px-4">


                <button class="btn btn-info mb-2">Total Gaji : Rp. {{ number_format($total) }}</button>

                <div wire:loading>
                    <button class="btn btn-primary" type="button" disabled>
                        <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                        <span role="status">Building Payroll... sedikit lama, jangan tekan apapun.</span>
                    </button>
                </div>
                <div>

                    <a href="/reportindex"><button class="btn btn-success text-end mb-2 mr-2" wire:loading.remove>Report
                            for
                            bank</button></a>


                    <button wire:click="getPayroll" class="btn btn-primary text-end mb-2">Rebuild</button>
                    {{-- <button wire:click="rebuild" class="btn btn-primary text-end mb-2">Rebuild</button> --}}
                    {{-- <button wire:click="getPayrollQueue" class="btn btn-primary text-end mb-2">Rebuild</button> --}}
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                {{-- <div class="d-flex justify-content-between align-items-center"> --}}
                <div class="d-flex flex-xl-row flex-column col-xl-12 justify-content-between align-items-center ">
                    <div class="col-xl-4 d-flex flex-xl-row flex-column gap-2">
                        <div class="input-group col-xl-6 col-12">
                            <button class="btn btn-primary" type="button"><i
                                    class="fa-solid fa-magnifying-glass"></i></button>
                            <input type="search" wire:model.live="search" class="form-control"
                                placeholder="Search ...">
                        </div>
                        <div class="col-xl-6 col-12">
                            <select wire:model.live="selected_company" class="form-select"
                                aria-label="Default select example">
                                <option value="0"selected>All</option>
                                <option value="1">Pabrik 1</option>
                                <option value="2">Pabrik 2</option>
                                <option value="3">Kantor</option>
                                <option value="4">ASB</option>
                                <option value="5">DPA</option>
                                <option value="6">YCME</option>
                                <option value="7">YEV</option>
                                <option value="8">YIG</option>
                                <option value="9">YSM</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-xl-3 d-flex gap-2 mt-2">

                        <div class="col-xl-6">

                            <select class="form-select" wire:model.live="year">
                                {{-- <option selected>Open this select menu</option> --}}
                                <option value="{{ $year }}">{{ $year }}</option>
                            </select>
                        </div>
                        <div class="col-xl-6">
                            <select class="form-select" wire:model.live="month">
                                {{-- <option selected>Open this select menu</option> --}}
                                <option value="11">{{ monthName(11) }}</option>
                                <option value="12">{{ monthName(12) }}</option>
                            </select>
                        </div>
                    </div>


                    <div
                        class="col-xl-5 mt-2 gap-2 d-flex flex-xl-row flex-column align-items-center  justify-content-end">
                        <div class="col-xl-4">
                            <select class="form-select" wire:model.live="perpage">
                                {{-- <option selected>Open this select menu</option> --}}
                                <option value="10">10 rows perpage</option>
                                <option value="15">15 rows perpage</option>
                                <option value="20">20 rows perpage</option>
                                <option value="25">25 rows perpage</option>
                            </select>

                        </div>
                        <div class="col-xl-4">
                            <select class="form-select" wire:model.live="status">
                                <option value="0">Semua</option>
                                <option value="1">Status Aktif</option>
                                <option value="2">Status Non Aktif</option>
                            </select>
                        </div>
                        <div
                            class="col-xl-4 mt-xl-0 mt-2 d-flex align-items-center justify-content-between justify-content-xl-end gap-2 ">
                            <div>
                                <button wire:click="export" class="btn btn-success">Excel</button>
                            </div>
                            {{-- <div>
                                <button class="btn btn-danger">PDF</button>
                            </div>
                            <div>
                                <button class="btn btn-dark">Print</button>
                            </div> --}}
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
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th></th>
                                <th wire:click="sortColumnName('id_karyawan')">ID <i class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('nama')">Nama <i class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('status_karyawan')">Status <i
                                        class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('jabatan')">Jabatan <i class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('company')">Company <i class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('placement')">Placement <i class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('metode_penggajian')">Metode Penggajian <i
                                        class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('id_karyawan')">Hari Kerja <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('jam_kerja')">Jam Kerja Bersih <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('jam_lembur')">Jam Lembur <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('jumlah_jam_terlambat')">Terlambat <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('gaji_pokok')">Gaji Pokok <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('gaji_lembur')">Gaji Lembur <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('gaji_bpjs')">Gaji BPJS <i class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('subtotal')">Sub Gaji <i class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('tambahan_shift_malam')">Tambahan Shift Malam <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('bonus1x')">Bonus/U.Makan <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('bonus1x')">Bonus Karyawan<i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('potongan1x')">Potongan 1X<i
                                        class="fa-solid fa-sort"></i>
                                </th>

                                <th wire:click="sortColumnName('potongan1x')">Potongan Karyawan<i
                                        class="fa-solid fa-sort"></i>
                                </th>


                                <th wire:click="sortColumnName('denda_lupa_absen')">Lupa Absen <i
                                        class="fa-solid fa-sort"></i>
                                </th>

                                <th wire:click="sortColumnName('pajak')">Pajak <i class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('jht')">JHT <i class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('jp')">JP <i class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('jkk')">JKK <i class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('jkm')">JKM <i class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('kesehatan')">Kesehatan <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('total')">Total <i class="fa-solid fa-sort"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($payroll->isNotEmpty())

                                @foreach ($payroll as $p)
                                    <tr>
                                        <td>
                                            <button type="button" class="btn btn-success btn-sm"
                                                wire:click="showDetail({{ $p->id_karyawan }})" data-bs-toggle="modal"
                                                data-bs-target="#payroll"><i
                                                    class="fa-solid fa-magnifying-glass"></i></button>

                                        </td>


                                        <td>{{ $p->id_karyawan }}</td>
                                        <td>{{ $p->nama }}</td>
                                        <td>{{ $p->status_karyawan }}</td>
                                        <td>{{ $p->jabatan }}</td>
                                        <td>{{ $p->company }}</td>
                                        <td>{{ $p->placement }}</td>
                                        <td>{{ $p->metode_penggajian }}</td>
                                        <td class="text-end">{{ $p->hari_kerja }}</td>
                                        <td class="text-end">{{ number_format($p->jam_kerja, 1) }}</td>
                                        <td class="text-end">{{ $p->jam_lembur }}</td>
                                        <td class="text-end">{{ $p->jumlah_jam_terlambat }}</td>
                                        <td class="text-end">{{ number_format($p->gaji_pokok) }}</td>
                                        <td class="text-end">
                                            {{ $p->gaji_lembur ? number_format($p->gaji_lembur) : '' }}
                                        </td>
                                        <td class="text-end">
                                            {{ $p->gaji_bpjs ? number_format($p->gaji_bpjs) : '' }}
                                        </td>
                                        <td class="text-end">{{ number_format($p->subtotal) }}</td>
                                        <td class="text-end">
                                            {{ $p->tambahan_shift_malam ? number_format($p->tambahan_shift_malam) : '' }}
                                        </td>
                                        <td class="text-end">
                                            {{ $p->bonus1x ? number_format($p->bonus1x) : '' }}
                                        </td>
                                        @php
                                            $total_potongan_dari_karyawan = 0;
                                            $total_bonus_dari_karyawan = 0;
                                            $total_potongan_dari_karyawan = $p->iuran_air + $p->iuran_locker;
                                            $total_bonus_dari_karyawan = $p->thr + $p->tunjangan_jabatan + $p->tunjangan_bahasa + $p->tunjangan_skill + $p->tunjangan_lembur_sabtu + $p->tunjangan_lama_kerja;

                                        @endphp
                                        <td class="text-end">
                                            {{ number_format($total_bonus_dari_karyawan) }}
                                            {{-- {{ $total_bonus_dari_karyawan ? number_format($total_bonus_dari_karyawan) : '' }} --}}
                                        </td>
                                        <td class="text-end">
                                            {{ $p->potongan1x ? number_format($p->potongan1x) : '' }}
                                        </td>

                                        <td class="text-end">
                                            {{ $total_potongan_dari_karyawan ? number_format($total_potongan_dari_karyawan) : '' }}
                                        </td>
                                        <td class="text-end">
                                            {{ $p->denda_lupa_absen ? number_format($p->denda_lupa_absen) : '' }}
                                        </td>

                                        <td class="text-end">{{ $p->pajak ? number_format($p->pajak) : '' }}</td>
                                        <td class="text-end">{{ $p->jht ? number_format($p->jht) : '' }}</td>
                                        <td class="text-end">{{ $p->jp ? number_format($p->jp) : '' }}</td>
                                        <td class="text-end">{{ $p->jkk ? 'Yes' : '' }}</td>
                                        <td class="text-end">{{ $p->jkm ? 'Yes' : '' }}</td>
                                        <td class="text-end">{{ $p->kesehatan ? number_format($p->kesehatan) : '' }}
                                        </td>

                                        <td class="text-end">{{ number_format($p->total) }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <h4>No Data Found</h4>
                            @endif
                        </tbody>
                    </table>
                    {{ $payroll->onEachSide(0)->links() }}
                </div>
            </div>
            <p class="px-3 text-success">Last update: {{ $last_build }} </p>
        </div>
    </div>
    @if ($data_payroll != null && $data_karyawan != null)
        @include('modals.payroll-modal')
    @endif






</div>
