<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <title>Yifang Mobile</title>
</head>

<body style="font-family: 'nunito';">
    <div class="w-screen bg-black h-32 shadow-xl rounded-b-3xl">
        <img src="{{ asset('images/Yifang-transparant-logo.png') }}" alt="Yifang Logo" style="opacity: .8; width:150px">
    </div>

    <h1>Hello {{ auth()->user()->name }}</h1>
    @foreach ($data as $d)
        @if ($d->no_scan == null)
            <div class="flex px-3 py-2 justify-center">
                <div
                    class="flex border-pink-500 border-l-8 rounded-lg bg-blue-100 w-screen h-20 items-center p-2 rounded-lg shadow-lg justify-around  ">
                    <div
                        class="rounded-full bg-white w-10 h-10 flex justify-center items-center font-bold text-xl text-blue-500">
                        {{ tgl_doang($d->date) }}
                    </div>
                    <div class="text-center">
                        <p class="text-gray-500">Jam Kerja</p>
                        <p class="font-bold text-blue-500">
                            {{ hitung_jam_kerja($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->late, $d->shift, $d->date) }}
                        </p>
                    </div>
                    <div class="text-center">
                        <p class="text-gray-500">Terlambat </p>
                        <p class="font-bold text-blue-500">
                            {{ late_check_jam_kerja_only($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->shift, $d->date) }}
                        </p>

                    </div>
                    <div class="text-center">
                        <p class="text-gray-500">Jam Lembur</p>
                        <p class="font-bold text-blue-500">{{ hitungLembur($d->overtime_in, $d->overtime_out) / 60 }}
                        </p>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    <a class="nav-link" href="{{ route('logout') }}"
        onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
        <i class="nav-icon fa-solid fa-right-from-bracket"></i>
        <button class="bg-purple-500 text-white px-3 py-2">Logout</button>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

</body>

</html>
