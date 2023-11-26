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
                {{-- <div class="py-2"> --}}
                <div class="flex justify-center">
                    <h2 class="bg-black text-center text-white text-xl rounded-xl px-5 mt-3">Peraturan kantor
                    </h2>
                </div>
                {{-- </div> --}}

            </div>
            <div class="main  flex-1 overflow-y-auto ">
                {{-- <h2 class="bg-black text-center text-white text-xl rounded-xl px-5  ">Informasi Terkini</h2> --}}
                <div class="w-screen flex px-3 mt-5  flex flex-col gap-5 ">

                    <div class="px-4 py-2">
                        <h2 class="text-lg font-bold mt-4 mb-2">Ketentuan:</h2>
                        <ul class="text-sm">
                            <li class="mb-2"><strong>Shift Pagi:</strong>
                                <ul class="list-disc ml-4">
                                    <li>Jam kerja: 08:00 - 17:00</li>
                                    <li>Jam Lembur: 18:00 - (mulai masuk setelah 17:30)</li>
                                </ul>
                            </li>
                            <li class="mb-2"><strong>Hari Sabtu:</strong>
                                <ul class="list-disc ml-4">
                                    <li>Jam Kerja: 08:00 - 15:00</li>
                                </ul>
                            </li>
                            <li class="mb-2"><strong>Istirahat Shift 1:</strong> 11:30 - 12:30 (batas waktu keluar
                                mulai dari jam 11:30 - 11:59)</li>
                            <li class="mb-2"><strong>Istirahat Shift 2:</strong> 12:00 - 13:00 (Batas waktu keluar
                                mulai dari jam 12:00)</li>
                        </ul>

                        <ul class="mt-4 text-sm">
                            <li class="mb-2"><strong>Shift Malam:</strong>
                                <ul class="list-disc ml-4">
                                    <li>Jam kerja: 20:00 - 05:00</li>
                                    <li>Jam Lembur: Mulai dari jam 05:00</li>
                                </ul>
                            </li>
                            <li class="mb-2"><strong>Hari Sabtu:</strong>
                                <ul class="list-disc ml-4">
                                    <li>Jam Kerja: 17:00 - 00:00</li>
                                </ul>
                            </li>
                            <li class="mb-2"><strong>Istirahat 00:00 - 01:00 (batas waktu keluar mulai dari jam 00:00)
                            </li>
                        </ul>

                        <h2 class="text-lg font-bold mt-4 mb-2">Perhitungan keterlambatan:</h2>
                        <p class="mb-2 text-sm">
                            Untuk karyawan pabrik saat bekerja pada jam kerja biasa, setiap
                            keterlambatan lebih dari 3 menit akan dikenakan potongan 1 jam.
                        </p>
                        <p class="mb-2 text-sm">Contoh:</p>
                        <p class="text-sm">Jika masuk kerja pada jam 08:04 atau lebih, maka jam kerja akan dihitung
                            mulai
                            jam
                            09:00. Jika masuk kerja sebelum jam 08:03, maka jam kerja akan dihitung mulai jam 08:00.</p>

                        <hr class="my-3">
                        <p class="mb-2 text-sm">Untuk jam lembur, dihitung dengan pembulatan 30 menit dengan toleransi 3
                            menit.</p>

                        <p class="mb-2 text-sm">Contoh:</p>
                        <p class="text-sm">Jika masuk lembur pada jam 18:04 atau lebih, maka lembur akan dihitung mulai
                            jam
                            18:30. Jika masuk lembur sebelum jam 18:03, maka lembur akan dihitung mulai jam 18:00.</p>

                        <h5 class="text-sm text-red-500 mt-4">* Silakan informasikan kebagian terkait jika anda masuk
                            diluar dari jam yang disebutkan di atas</h5>
                    </div>


                    {{-- Footer --}}
                    <div class="footer flex justify-between h-16 items-center bg-gray-800 fixed bottom-0">


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


                    </div>
                </div>
            </div>
        </div>
