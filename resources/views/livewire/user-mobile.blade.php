<div>

    <div>
        <div class=header>
            <div class="w-screen bg-gray-800 h-24 shadow-xl rounded-b-3xl   ">
                <div class="flex justify-between">
                    <div>
                        <img src="{{ asset('images/Yifang-transparant-logo.png') }}" alt="Yifang Logo"
                            style="opacity: .8; width:150px">
                    </div>
                    <div class="flex flex-col p-3 gap-5 items-end">
                        <div>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">

                                <button
                                    class="rounded-xl shadow bg-purple-500 text-sm text-white px-3 py-1">Logout</button>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                        <div>
                            <h1 class="text-white text-sm">Hello, {{ auth()->user()->name }}</h1>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        {{-- Main --}}
        <div>

            {{-- Select Payroll --}}
            <div class="flex px-3  justify-center ">
                <div class="w-screen bg-teal-500 h-12 shadow-xl rounded-3xl mt-2  ">
                    <div class="h-12 flex justify-evenly items-center">
                        <div>
                            <select wire:model.live="selectedYear" class="bg-teal-500 text-white text-sm">
                                <option value="2023">2023</option>
                            </select>
                        </div>
                        <div>
                            <select wire:model.live="selectedMonth" class="bg-teal-500 text-white text-sm">
                                <option value="11">November</option>
                                {{-- <option value="12">Desember</option> --}}
                            </select>
                        </div>
                        <div class="invisible">
                            {{-- <div> --}}
                            <button wire:click="slip_gaji"
                                class="text-white bg-teal-900 hover:bg-teal-700 px-3 py-1 rounded-xl text-sm">Slip
                                Gaji</button>

                        </div>
                    </div>
                </div>
            </div>

            {{-- Isi Slip Gaji --}}
            @if ($is_slipGaji == true)
                @if ($data_payroll != null)
                    <div class="w-screen  ">
                        <h2 class="font-semibold text-lg text-center my-2">Slip Gaji {{ monthName($selectedMonth) }}
                            {{ $selectedYear }}</h2>
                        <table class="mx-auto text-sm">
                            <tbody>

                                <tr>
                                    <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">Id</td>

                                    <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">
                                        {{ $data_payroll->id_karyawan }}</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">Nama</td>
                                    <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">
                                        {{ $data_payroll->nama }}</td>
                                </tr>
                                @if ($data_karyawan->no_npwp != null)
                                    <tr>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">No. NPWP</td>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">
                                            {{ $data_karyawan->no_npwp }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">Nama Bank</td>
                                    <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">
                                        {{ $data_karyawan->nama_bank }}</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">No. Rekening</td>
                                    <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">
                                        {{ $data_karyawan->nomor_rekening }}</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">Hari Kerja</td>
                                    <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">
                                        {{ $data_payroll->hari_kerja }} hari</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">Jam Kerja</td>
                                    <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">
                                        {{ $data_payroll->jam_kerja }} jam</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">Jam Lembur</td>
                                    <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">
                                        {{ $data_payroll->jam_lembur / 60 }} jam</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">Gaji Pokok</td>
                                    <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">
                                        Rp. {{ number_format($data_payroll->gaji_pokok) }}</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">Gaji Lembur</td>
                                    <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">
                                        Rp. {{ number_format($data_payroll->gaji_lembur) }}</td>
                                </tr>
                                @if ($data_payroll->tambahan_shift_malam != null)
                                    <tr>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">Bonus Shift Malam
                                        </td>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">
                                            Rp. {{ number_format($data_payroll->tambahan_shift_malam) }}
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">Subtotal Gaji</td>
                                    <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">
                                        Rp. {{ number_format($data_payroll->subtotal) }}</td>
                                </tr>
                                @if ($data_karyawan->iuran_air != null)
                                    <tr>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">Iuran air minum
                                        </td>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">
                                            Rp. {{ number_format($data_karyawan->iuran_air) }}</td>
                                    </tr>
                                @endif
                                @if ($data_karyawan->iuran_locker != null)
                                    <tr>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">Iuran Locker</td>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">
                                            Rp. {{ number_format($data_karyawan->iuran_locker) }}</td>
                                    </tr>
                                @endif

                                @if ($data_payroll->bonus1x != null)
                                    <tr>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">Bonus1x payroll
                                        </td>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">
                                            Rp. {{ number_format($data_payroll->bonus1x) }}
                                        </td>
                                    </tr>
                                @endif
                                @if ($data_payroll->potongan1x - ($data_karyawan->iuran_air + $data_karyawan->iuran_locker) != 0)
                                    <tr>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">Potongan1x
                                            payroll</td>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">
                                            Rp. {{ number_format($data_payroll->potongan1x) }}
                                        </td>
                                    </tr>
                                @endif
                                @if ($data_payroll->denda_lupa_absen != 0)
                                    <tr>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">Denda Lupa Absen
                                        </td>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">
                                            Rp. {{ number_format($data_payroll->denda_lupa_absen) }}
                                        </td>
                                    </tr>
                                @endif
                                @if ($data_payroll->jht != null)
                                    <tr>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">BPJS JHT</td>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">
                                            Rp. {{ number_format($data_payroll->jht) }}
                                        </td>
                                    </tr>
                                @endif
                                @if ($data_payroll->jp != null)
                                    <tr>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">BPJS JP</td>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">
                                            Rp. {{ number_format($data_payroll->jp) }}
                                        </td>
                                    </tr>
                                @endif
                                {{-- @if ($data_payroll->jkk != null)
                                    <tr>
                                        <td  class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">BPJS JKK</td>
                                        <td  class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">Rp. {{ number_format($data_payroll->jkk) }}
                                        </td>
                                    </tr>
                                @endif
                                @if ($data_payroll->jkm != null)
                                    <tr>
                                        <td  class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">BPJS JKM</td>
                                        <td  class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">Rp. {{ number_format($data_payroll->jkm) }}
                                        </td>
                                    </tr>
                                @endif --}}
                                @if ($data_payroll->kesehatan != null)
                                    <tr>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">BPJS Kesehatan
                                        </td>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">
                                            Rp. {{ number_format($data_payroll->kesehatan) }}
                                        </td>
                                    </tr>
                                @endif
                                @if ($data_karyawan->ptkp != null)
                                    <tr>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">PTKP</td>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">
                                            Rp. {{ number_format($data_karyawan->ptkp) }}</td>
                                    </tr>
                                @endif

                                <tr>
                                    <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">Total Gaji</td>
                                    <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-500">
                                        Rp. {{ number_format($data_payroll->total) }}
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                        <div class="mt-20"></div>


                        {{-- <button wire:click="close" class="bg-black text-white px-2 py-1 mt-2">Close</button> --}}
                    </div>
                @endif
            @else
                {{-- Isi Presensi --}}
                <div>
                    <div class="flex px-3 pt-2 justify-center  ">
                        <div
                            class="w-screen h-30 bg-red-200 text-gray-600  px-3  flex flex-col rounded-lg shadow text-center justify-center">
                            <h1 class="pt-1 font-semibold">Presensi Bulan November 2023</h1>
                            <div class="flex justify-around text-center pb-1">

                                <div>
                                    <p class="text-sm">Hari</p>
                                    <p class="font-bold text-green-500 text-lg">{{ $total_hari_kerja }}</p>
                                </div>

                                <div>
                                    <p class="text-sm">Jam Kerja</p>
                                    <p class="font-bold text-green-500 text-lg">{{ $total_jam_kerja }}</p>
                                </div>

                                <div>
                                    <p class="text-sm">Jam Lembur</p>
                                    <p class="font-bold text-green-500 text-lg">{{ $total_jam_lembur }}</p>
                                </div>

                                <div>
                                    <p class="text-sm">Terlambat</p>
                                    <p class="font-bold text-green-500 text-lg">{{ $total_keterlambatan }}</p>
                                </div>


                            </div>


                        </div>
                    </div>
                    <div class="flex px-3 mt-2 justify-center relative h-48">
                        <table class="w-screen">
                            <tbody>
                                @foreach ($data as $d)
                                    <tr
                                        class="flex justify-evenly border-pink-500 border-l-8 rounded-lg bg-blue-100 w-full h-18 items-center p-2 rounded-lg shadow mb-2">
                                        <td class="text-center">
                                            <p
                                                class="rounded-full bg-white w-10 h-10 flex justify-center items-center font-bold text-xl text-green-500">
                                                {{ tgl_doang($d->date) }}
                                            </p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-gray-500 text-sm">Jam Kerja</p>
                                            <p class="font-bold text-blue-500">
                                                @php
                                                    $tgl = tgl_doang($d->date);
                                                    $jam_kerja = hitung_jam_kerja($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->late, $d->shift, $d->date, $d->karyawan->jabatan);
                                                    $terlambat = late_check_jam_kerja_only($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->shift, $d->date, $d->karyawan->jabatan);

                                                    if ($d->karyawan->jabatan === 'Satpam') {
                                                        $jam_kerja = $terlambat >= 6 ? 0.5 : $jam_kerja;
                                                    }

                                                    $langsungLembur = langsungLembur($d->second_out, $d->date, $d->shift, $d->karyawan->jabatan);

                                                    $jam_lembur = hitungLembur($d->overtime_in, $d->overtime_out) / 60 + $langsungLembur;

                                                    if ($d->shift == 'Malam') {
                                                        if (is_saturday($d->date)) {
                                                            if ($jam_kerja >= 6) {
                                                                $jam_lembur = $jam_lembur + 1;
                                                            }
                                                        } else {
                                                            if ($jam_kerja >= 8) {
                                                                $jam_lembur = $jam_lembur + 1;
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                {{ $jam_kerja }}
                                            </p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-gray-500 text-sm">Jam Lembur</p>
                                            <p class="font-bold text-blue-500">
                                                {{ $jam_lembur }}
                                            </p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-gray-500 text-sm">Terlambat</p>
                                            <p class="font-bold text-blue-500">
                                                {{ late_check_jam_kerja_only($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->shift, $d->date, $d->karyawan->jabatan) }}
                                            </p>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                        <div>
                            <a wire:navigate href="{{ $data->previousPageUrl() }}"><button
                                    class="bg-blue-700 opacity-20 text-purple-200 h-14 w-14 rounded-full text-2xl absolute top-1/3 left-1 "><i
                                        class="fa-solid fa-left-long"></i>
                                </button></a>
                        </div>
                        <div>
                            <a wire:navigate href="{{ $data->nextPageUrl() }}"><button
                                    class="bg-blue-700 opacity-20 text-purple-200 h-14 w-14 rounded-full text-2xl absolute top-1/3 right-1 "><i
                                        class="fa-solid fa-right-long"></i></button></a>
                        </div>


                    </div>
                </div>
            @endif
        </div>

        {{-- {{ $data->links() }} --}}
        {{-- Footer --}}
        <div class="footer bg-slate-800 flex justify-between h-16 items-center fixed bottom-0 left-0 right-0 ">
            @if (isset($data))
                {{-- @if ($data->currentPage() > 1) --}}
                {{-- <a wire:navigate href="{{ $data->previousPageUrl() }}"><button
                    class="text-purple-200 px-4 py-4 rounded  text-2xl"><i class="fa-solid fa-left-long"></i>
                </button></a> --}}
                <a wire:navigate href="userregulation"><button
                        class="{{ 'userregulation' == request()->path() ? 'bg-red-500 ' : '' }} text-purple-200 px-4 py-4 rounded  text-2xl"><i
                            class="fa-solid fa-list-check"></i></button></a>

                {{-- @endif --}}
                {{-- href="/profile" --}}
                <a wire:navigate href="profile"><button
                        class="{{ 'profile' == request()->path() ? 'bg-red-500 ' : '' }} text-purple-200 px-4 py-4 rounded  text-2xl"><i
                            class="fa-solid fa-user"></i>
                    </button></a>
                <a wire:navigate href="usermobile"><button
                        class="{{ 'usermobile' == request()->path() ? 'bg-red-500 ' : '' }} text-purple-200 px-4 py-4 rounded  text-2xl"><i
                            class="fa-solid fa-house"></i>
                    </button></a>
                {{-- href="/userinformation" --}}
                <a wire:navigate href="userinformation"><button
                        class="{{ 'userinformation' == request()->path() ? 'bg-red-500 ' : '' }} text-purple-200 px-4 py-4 rounded  text-2xl "><i
                            class="fa-solid fa-circle-info"></i>
                    </button></a>
                {{-- @if ($data->hasMorePages()) --}}
                {{-- <a wire:navigate href="{{ $data->nextPageUrl() }}"><button
                    class="text-purple-200 px-4 py-4 rounded text-2xl "><i
                        class="fa-solid fa-right-long"></i></button></a> --}}

                <div>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">

                        <button class="text-purple-200 px-4 py-4 rounded text-2xl "><i
                                class="fa-solid fa-power-off"></i></button>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>

                {{-- @endif --}}
            @endif
        </div>
    </div>
</div>
