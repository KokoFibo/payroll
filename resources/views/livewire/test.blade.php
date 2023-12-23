<div>
    <div  class="relative overflow-x-auto pb-2">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3"></th>
                    <th scope="col" class="px-6 py-3">ASB</th>
                    <th scope="col" class="px-6 py-3">DPA</th>
                    <th scope="col" class="px-6 py-3">YCME</th>
                    <th scope="col" class="px-6 py-3">YEV</th>
                    <th scope="col" class="px-6 py-3">YIG</th>
                    <th scope="col" class="px-6 py-3">YSM</th>
                </tr>
            </thead>    
            <tbody>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <th  scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">Nov 2023</th>
                    <td class="px-6 py-4">200,000,000</td>
                    <td class="px-6 py-4">200,000,000</td>
                    <td class="px-6 py-4">200,000,000</td>
                    <td class="px-6 py-4">200,000,000</td>
                    <td class="px-6 py-4">200,000,000</td>
                    <td class="px-6 py-4">200,000,000</td>
                </tr>    
            </tbody>
        </table>    
    </div>






    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Company</th>
                <th>Departemen</th>
                <th>Jabatan</th>
                <th>Status</th>
                <th>tanggal_bergabung</th>
                <th>lama_bekerja</th>

                <th>Gaji Pokok</th>

            </tr>
        </thead>
        {{-- <tbody>

            @foreach ($data as $d)
                <tr>
                    <td>{{ $d->id_karyawan }}</td>
                    <td>{{ $d->nama }}</td>
                    <th>{{ $d->company }}</th>
                    <th>{{ $d->departemen }}</th>
                    <th>{{ $d->jabatan }}</th>
                    <th>{{ $d->status_karyawan }}</th>
                    <td>{{ $d->tanggal_bergabung }}</td>

                    <td>{{ number_format(lama_bekerja($d->tanggal_bergabung, $today)) }}</td>
                    <td>{{ number_format($d->gaji_pokok) }}</td>
                </tr>
            @endforeach
        </tbody> --}}
    </table>
    {{ $data->links() }}
</div>
