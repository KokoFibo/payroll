<!-- resources/views/livewire/abnormal.blade.php -->
<div class="p-4 space-y-4">
    <form wire:submit.prevent="render" class="grid grid-cols-1 md:grid-cols-5 gap-4 bg-white p-4 rounded shadow">
        <p>{{ $month }} / {{ $year }}</p>
        <!-- Month -->
        <div>
            <label class="block text-sm font-semibold mb-1">Bulan</label>
            <select wire:model.live="month" class="w-full border rounded p-2">
                @foreach (\App\Models\Yfrekappresensi::selectRaw('MONTH(date) as m')->groupBy('m')->orderBy('m')->pluck('m') as $m)
                    <option value="{{ $m }}">{{ $m }}</option>
                @endforeach
            </select>
        </div>

        <!-- Year -->
        <div>
            <label class="block text-sm font-semibold mb-1">Tahun</label>
            <select wire:model.live="year" class="w-full border rounded p-2">
                @foreach (\App\Models\Yfrekappresensi::selectRaw('YEAR(date) as y')->groupBy('y')->orderBy('y')->pluck('y') as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>
        </div>


        <!-- Compare -->
        <div>
            <label class="block text-sm font-semibold mb-1">Operator</label>
            <select wire:model.live="compare" class="w-full border rounded p-2">
                <option value=">">&gt;</option>
                <option value="=">=</option>
                <option value="<">&lt;</option>
            </select>
        </div>

        <!-- Jumlah -->
        <div>
            <label class="block text-sm font-semibold mb-1">Jumlah</label>
            <input type="number" wire:model.live="jumlah" class="w-full border rounded p-2" />
        </div>

    </form>

    <!-- TABLE -->
    <div class="bg-white shadow rounded p-4 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b bg-gray-100">
                    <th class="p-2 text-left">Nama</th>
                    <th class="p-2 text-left">ID Karyawan</th>
                    <th class="p-2 text-left">Tanggal</th>
                    <th class="p-2 text-left">Total Jam Kerja</th>
                    <th class="p-2 text-left">Total Jam Lembur</th>
                    <th class="p-2 text-left">Total Jam Kerja Libur</th>
                    <th class="p-2 text-left">Total Jam Lembur Libur</th>
                    <th class="p-2 text-left">Total Hari Kerja</th>
                    <th class="p-2 text-left">Total Hari Kerja Libur</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $row)
                    <tr class="border-b">
                        <td class="p-2">{{ getName($row->user_id) }}</td>
                        <td class="p-2">{{ $row->user_id }}</td>
                        <td class="p-2">{{ $row->date }}</td>
                        <td class="p-2">
                            {{ $row->total_jam_kerja > $jumlah ? $row->total_jam_kerja : '' }}
                        </td>

                        <td class="p-2">
                            {{ $row->total_jam_lembur > $jumlah ? $row->total_jam_lembur : '' }}
                        </td>

                        <td class="p-2">
                            {{ $row->total_jam_kerja_libur > $jumlah ? $row->total_jam_kerja_libur : '' }}
                        </td>

                        <td class="p-2">
                            {{ $row->total_jam_lembur_libur > $jumlah ? $row->total_jam_lembur_libur : '' }}
                        </td>
                        <td class="p-2">
                            {{ $row->total_hari_kerja > 1 ? $row->total_hari_kerja : '' }}
                        </td>
                        <td class="p-2"> {{ $row->total_hari_kerja_libur > 1 ? $row->total_hari_kerja_libur : '' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $data->links() }}
        </div>
    </div>
</div>
