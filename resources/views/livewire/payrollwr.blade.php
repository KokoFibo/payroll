<div>
    <div class="pt-5">
        <div class="d-flex gap-3">
            <h3>Total Gaji : Rp. {{ number_format($total) }}</h3>

            <div class="col-2">
                <select wire:model.live="selected_company" class="form-select" aria-label="Default select example">
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
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5>Payroll</h5>
                    <button wire:click="rebuild" class="btn btn-primary">Rebuild</button>
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
                    </tbody>
                </table>
                {{ $payroll->links() }}
            </div>
            <p class="px-3 text-success">Last Build: {{ $last_build }} </p>
        </div>
    </div>
</div>
