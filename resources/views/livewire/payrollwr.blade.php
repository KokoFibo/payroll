<div>
    <div class="pt-2">
        <div class="">
            <h2 class="text-center text-bold">Yifang Payroll</h2>
            <div class="d-flex align-items-center justify-content-between px-4">
                <h5>Total Gaji : Rp. {{ number_format($total) }}</h5>
                <button wire:click="rebuild" class="btn btn-primary text-end mb-3">Rebuild</button>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div class="d-flex gap-3 col-8">
                        <div class="input-group col-3">
                            <button class="btn btn-primary" type="button"><i
                                    class="fa-solid fa-magnifying-glass"></i></button>
                            <input type="search" wire:model.live="search" class="form-control"
                                placeholder="Search ...">
                        </div>
                        <div class="col-2">
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
                        <div class="col-2 d-flex align-items-center gap-3">
                            <select class="form-select" wire:model.live="perpage">
                                {{-- <option selected>Open this select menu</option> --}}
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                                <option value="25">25</option>
                            </select>
                            Perpage
                        </div>
                        <div class="col-2 d-flex align-items-center gap-3">
                            <select class="form-select" wire:model.live="year">
                                {{-- <option selected>Open this select menu</option> --}}
                                <option value="{{ $year }}">{{ $year }}</option>
                            </select>
                        </div>
                        <div class="col-2 d-flex align-items-center gap-3">
                            <select class="form-select" wire:model.live="month">
                                {{-- <option selected>Open this select menu</option> --}}
                                <option value="{{ $month }}">{{ $month }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-right px-1">
                        <div class="d-flex gap-2">
                            <button class="btn btn-success">Excel</button>
                            <button class="btn btn-danger">PDF</button>
                            <button class="btn btn-dark">Print</button>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Company</th>
                            <th>Metode Penggajian</th>
                            <th>Hari Kerja</th>
                            <th>Jam Kerja</th>
                            <th>Jam Lembur</th>
                            <th>Gaji Pokok</th>
                            <th>Gaji Lembur</th>
                            <th>Sub Gaji</th>
                            <th>Pajak</th>
                            <th>JHT</th>
                            <th>JP</th>
                            <th>JKK</th>
                            <th>JKM</th>
                            <th>Kesehatan</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($data_kosong > 0)


                            @foreach ($payroll as $p)
                                <tr>
                                    <td>{{ $p->karyawan->id_karyawan }}</td>
                                    <td>{{ $p->karyawan->nama }}</td>
                                    <td>{{ $p->karyawan->jabatan }}</td>
                                    <td>{{ $p->karyawan->company }}</td>
                                    <td>{{ $p->karyawan->metode_penggajian }}</td>
                                    <td class="text-end">{{ $p->jamkerjaid->total_hari_kerja }}</td>
                                    <td class="text-end">{{ number_format($p->jamkerjaid->jumlah_jam_kerja, 1) }}</td>
                                    <td class="text-end">{{ $p->jamkerjaid->jumlah_menit_lembur / 60 }}</td>
                                    <td class="text-end">{{ number_format($p->karyawan->gaji_pokok) }}</td>
                                    <td class="text-end">{{ number_format($p->karyawan->gaji_overtime) }}</td>
                                    <td class="text-end">{{ number_format($p->subtotal) }}</td>
                                    <td class="text-end">{{ number_format($p->pajak) }}</td>
                                    <td class="text-end">0</td>
                                    <td class="text-end">0</td>
                                    <td class="text-end">0</td>
                                    <td class="text-end">0</td>
                                    <td class="text-end">0</td>
                                    <td class="text-end">{{ number_format($p->total) }}</td>
                                </tr>
                            @endforeach
                        @else
                            <h4>No Data Found</h4>
                        @endif
                    </tbody>
                </table>
                {{ $payroll->links() }}
            </div>
            <p class="px-3 text-success">Last Build: {{ $last_build }} </p>
        </div>
    </div>
</div>
