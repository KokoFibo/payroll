<div>
    <div>

        <div class=header>
            <div class="w-screen bg-gray-800 h-36 shadow-xl rounded-b-3xl  sticky top-0 ">
                <div class="flex justify-between">
                    <div class="flex flex-col gap-2">
                        <div>
                            <img src="{{ asset('images/Yifang-transparant-logo.png') }}" alt="Yifang Logo"
                                style="opacity: .8; width:150px">
                        </div>
                        <div class="flex justify-left -mt-4">
                            <div class="text-right px-5 pt-2 ">
                                <select name="" id="" class="bg-black text-white text-sm">
                                    <option value="">2023</option>
                                </select>
                            </div>
                            <div class="text-right px-5 pt-2 ">
                                <select name="" id="" class="bg-black text-white text-sm">
                                    <option value="">November</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col p-3 gap-4 items-end">
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
                        <div>
                            <a href=""><button class="text-white bg-red-500 px-3 py-1 rounded-xl text-sm">Slip
                                    Gaji</button></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex px-3 pt-2 justify-center relative bottom-5">
                <div
                    class="w-screen h-32 bg-red-200 text-gray-600  px-3  flex flex-col rounded-lg shadow text-center justify-center">
                    <h1 class="pt-1 font-bold text-lg">Presensi Bulan November 2023</h1>
                    <div class="flex justify-around text-center pb-1">

                        <div>
                            <p>Hari</p>
                            <p class="font-bold text-green-500 text-lg">{{ $total_hari_kerja }}</p>
                        </div>

                        <div>
                            <p>Jam Kerja</p>
                            <p class="font-bold text-green-500 text-lg">{{ $total_jam_kerja }}</p>
                        </div>

                        <div>
                            <p>Jam Lembur</p>
                            <p class="font-bold text-green-500 text-lg">{{ $total_jam_lembur }}</p>
                        </div>

                        <div>
                            <p>Terlambat</p>
                            <p class="font-bold text-green-500 text-lg">{{ $total_keterlambatan }}</p>
                        </div>

                    </div>


                </div>
            </div>

            <div class="flex px-3 -mt-2 justify-center ">
                <table class="w-screen">
                    <tbody>
                        @foreach ($data as $d)
                            <tr
                                class="flex justify-evenly border-pink-500 border-l-8 rounded-lg bg-blue-100 w-full h-20 items-center p-2 rounded-lg shadow mb-3">
                                <td class="text-center">
                                    <p
                                        class="rounded-full bg-white w-10 h-10 flex justify-center items-center font-bold text-xl text-green-500">
                                        {{ tgl_doang($d->date) }}
                                    </p>
                                </td>
                                <td class="text-center">
                                    <p class="text-gray-500">Jam Kerja</p>
                                    <p class="font-bold text-blue-500">
                                        {{ hitung_jam_kerja($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->late, $d->shift, $d->date, $d->karyawan->jabatan) }}
                                    </p>
                                </td>
                                <td class="text-center">
                                    <p class="text-gray-500">Jam Lembur</p>
                                    <p class="font-bold text-blue-500">
                                        {{ langsungLembur($d->second_out, $d->date, $d->shift, $d->karyawan->jabatan) + hitungLembur($d->overtime_in, $d->overtime_out) / 60 }}
                                    </p>
                                </td>
                                <td class="text-center">
                                    <p class="text-gray-500">Terlambat</p>
                                    <p class="font-bold text-blue-500">
                                        {{ late_check_jam_kerja_only($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->shift, $d->date, $d->karyawan->jabatan) }}
                                    </p>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- {{ $data->links() }} --}}

    <div class="footer bg-slate-800 flex justify-between h-15 fixed bottom-0 left-0 right-0 ">
        @if (isset($data))
            {{-- @if ($data->currentPage() > 1) --}}
            <a wire:navigate href="{{ $data->previousPageUrl() }}"><button
                    class="  bg-opacity-0 text-purple-200 px-4 py-3 rounded  text-2xl"><i
                        class="fa-solid fa-left-long"></i>
                </button></a>

            {{-- @endif --}}
            {{-- href="/profile" --}}
            <a wire:navigate href="profile"><button class="bg-opacity-0 text-purple-200 px-4 py-3 rounded  text-2xl"><i
                        class="fa-solid fa-user"></i>
                </button></a>
            <a wire:navigate href="usermobile"><button
                    class="bg-opacity-0 text-purple-200 px-4 py-3 rounded  text-2xl"><i class="fa-solid fa-house"></i>
                </button></a>
            {{-- href="/userinformation" --}}
            <a wire:navigate href="userinformation"><button
                    class="bg-opacity-0 text-purple-200 px-4 py-3 rounded  text-2xl "><i
                        class="fa-solid fa-circle-info"></i>
                </button></a>
            {{-- @if ($data->hasMorePages()) --}}
            <a wire:navigate href="{{ $data->nextPageUrl() }}"><button
                    class="bg-opacity-0 text-purple-200 px-4 py-3 rounded text-2xl "><i
                        class="fa-solid fa-right-long"></i></button></a>

            {{-- @endif --}}
        @endif
    </div>

</div>
</div>
