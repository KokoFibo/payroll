<div>
    <div>
        <div class="flex flex-col h-screen">
            <div class=header>
                <div class="w-screen bg-gray-800 h-24 shadow-xl rounded-b-3xl   ">
                    <div class="flex justify-between">
                        <div>
                            <img src="{{ asset('images/Yifang-transparant-logo.png') }}" alt="Yifang Logo"
                                style="opacity: .8; width:150px">
                        </div>
                        <div class="flex flex-col p-3 gap-5 items-end">
                            @if (auth()->user()->role < 4)
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
                            @else
                                <div>
                                    <a href="/dashboard"><button
                                            class="rounded-xl shadow bg-green-500 text-sm text-white px-3 py-1">Dasboard</button>
                                    </a>


                                </div>
                            @endif

                            <div>
                                <h1 class="text-white text-sm">Hello, {{ auth()->user()->name }}</h1>
                            </div>

                        </div>
                    </div>
                </div>
                {{-- selection --}}
                <div class="flex px-3  justify-center ">
                    <div class="w-screen bg-teal-500 h-12 shadow-xl rounded-3xl mt-2 ">
                        <div class="h-12 flex justify-evenly items-center">
                            <div>
                                <select wire:model.live="selectedYear" class="bg-teal-500 text-white text-sm">
                                    <option value="2023">2023</option>
                                </select>
                            </div>
                            <div>
                                <select wire:model.live="selectedMonth" class="bg-teal-500 text-white text-sm">
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                            </div>
                            <div class="{{ auth()->user()->role <= 3 ? 'invisible' : '' }}">
                                {{-- <div> --}}
                                <button wire:click="slip_gaji" {{-- class="bg-red-200 text-gray-700 hover:bg-teal-700 px-3 py-1 rounded-xl text-sm">Slip --}}
                                    class="bg-gray-800 text-white hover:bg-teal-700 px-3 py-1 rounded-xl text-sm">Slip
                                    Gaji</button>

                            </div>
                        </div>
                    </div>
                </div>
                {{-- end selection --}}

                {{-- Summary --}}
                <div>
                    <div class="flex px-3 pt-2 justify-center  ">
                        <div
                            class="w-screen h-30 bg-red-200 text-gray-600  px-3  flex flex-col rounded-lg shadow text-center justify-center">
                            <h1 class="pt-1 font-semibold">Presensi Bulan {{ monthName($selectedMonth) }}
                                {{ $selectedYear }}</h1>
                            <div class="flex justify-around text-center pb-1">

                                <div>
                                    <p class="text-sm">Hari</p>
                                    <p class="font-bold text-green-500 text-lg">{{ $total_hari_kerja }}</p>
                                </div>

                                <div>
                                    <p class="text-sm">J. Kerja</p>
                                    <p class="font-bold text-green-500 text-lg">{{ $total_jam_kerja }}</p>
                                </div>

                                <div>
                                    <p class="text-sm">J. Lembur</p>
                                    <p class="font-bold text-green-500 text-lg">{{ $total_jam_lembur }}</p>
                                </div>

                                <div>
                                    <p class="text-sm">Terlambat</p>
                                    <p class="font-bold text-green-500 text-lg">{{ $total_keterlambatan }}</p>
                                </div>
                                <div>
                                    <p class="text-sm">S. Malam</p>
                                    <p class="font-bold text-green-500 text-lg">
                                        {{ $total_tambahan_shift_malam }}
                                    </p>
                                </div>


                            </div>


                        </div>
                    </div>
                </div>
                {{-- End Summary --}}

            </div>

            {{-- Main Table --}}
            <div class="main  flex-1 overflow-y-auto ">
                {{-- <h2 class="bg-black text-center text-white text-xl rounded-xl px-5  ">Informasi Terkini</h2> --}}
                <div class="w-screen flex px-3  mt-3 flex flex-col ">
                    <table>
                        <tbody>
                            @foreach ($data as $index => $d)
                                <tr
                                    class="flex justify-evenly border-pink-500 border-l-8 rounded-lg bg-blue-100 w-full h-18 items-center p-2 rounded-lg shadow mb-2">
                                    <td class="text-center">
                                        <p
                                            class="rounded-full bg-white w-10 h-10 flex justify-center items-center font-bold text-xl text-green-500">
                                            {{ tgl_doang($d->date) }}
                                        </p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-gray-500 text-sm">J. Kerja</p>
                                        <p class="font-bold text-blue-500">
                                            @php
                                                $tgl = tgl_doang($d->date);
                                                $jam_kerja = hitung_jam_kerja($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->late, $d->shift, $d->date, $d->karyawan->jabatan);
                                                $terlambat = late_check_jam_kerja_only($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->shift, $d->date, $d->karyawan->jabatan);

                                                if ($d->karyawan->jabatan === 'Satpam') {
                                                    $jam_kerja = $terlambat >= 6 ? 0.5 : $jam_kerja;
                                                }

                                                $langsungLembur = langsungLembur($d->second_out, $d->date, $d->shift, $d->karyawan->jabatan);

                                                if (is_sunday($d->date)) {
                                                    $jam_lembur = (hitungLembur($d->overtime_in, $d->overtime_out) / 60) * 2;
                                                } else {
                                                    $jam_lembur = hitungLembur($d->overtime_in, $d->overtime_out) / 60 + $langsungLembur;
                                                }

                                                $tambahan_shift_malam = 0;

                                                if ($d->shift == 'Malam') {
                                                    if (is_saturday($d->date)) {
                                                        if ($jam_kerja >= 6) {
                                                            // $jam_lembur = $jam_lembur + 1;
                                                            $tambahan_shift_malam = 1;
                                                        }
                                                    } elseif (is_sunday($d->date)) {
                                                        if ($jam_kerja >= 16) {
                                                            // $jam_lembur = $jam_lembur + 2;
                                                            $tambahan_shift_malam = 2;
                                                        }
                                                    } else {
                                                        if ($jam_kerja >= 8) {
                                                            // $jam_lembur = $jam_lembur + 1;
                                                            $tambahan_shift_malam = 1;
                                                        }
                                                    }
                                                }
                                                if ($jam_lembur >= 9 && is_sunday($d->date) == false) {
                                                    $jam_lembur = 0;
                                                }
                                                if ($d->karyawan->placement == 'YIG' || $d->karyawan->placement == 'YSM' || $d->karyawan->jabatan == 'Satpam') {
                                                    if (is_friday($d->date)) {
                                                        $jam_kerja = 7.5;
                                                    } elseif (is_saturday($d->date)) {
                                                        $jam_kerja = 6;
                                                    } else {
                                                        $jam_kerja = 8;
                                                    }
                                                }

                                                if ($d->karyawan->jabatan == 'Satpam' && is_sunday($d->date)) {
                                                    $jam_kerja = hitung_jam_kerja($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->late, $d->shift, $d->date, $d->karyawan->jabatan);
                                                }

                                                if ($d->karyawan->jabatan == 'Satpam' && is_saturday($d->date)) {
                                                    $jam_lembur = 0;
                                                }
                                            @endphp
                                            {{ $jam_kerja }}
                                        </p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-gray-500 text-sm">J. Lembur</p>
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
                                    <td class="text-center">
                                        <p class="text-gray-500 text-sm">S. Malam</p>
                                        <p class="font-bold text-blue-500">{{ $tambahan_shift_malam }}</p>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>.
            {{-- End Main Table --}}


            <div class="mt-10"></div>
            {{-- Footer --}}
            <div class="footer w-screen flex justify-between h-16 items-center bg-gray-800 fixed bottom-0">
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


            </div>
            {{--  end footer --}}
        </div>
    </div>
</div>
