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
    <div class="w-screen bg-black h-32 shadow-xl rounded-b-3xl flex justify-between">
        <div>
            <img src="{{ asset('images/Yifang-transparant-logo.png') }}" alt="Yifang Logo"
                style="opacity: .8; width:150px">
        </div>
        <div>
            <h1 class="text-white p-3">Hello, {{ auth()->user()->name }}</h1>
        </div>
    </div>
    <div class="flex px-5 pt-2 justify-center relative bottom-10">
        <div
            class="w-screen h-32 bg-red-100 text-gray-600  px-3 flex flex-col rounded-lg shadow-lg text-center justify-center">
            <h1 class="pb-3 font-bold text-2xl">Presensi Bulan Nopember 2023</h1>
            <div class="flex justify-around text-center">
                <div>
                    <p>Hari Kerja</p>
                    <p class="font-bold text-green-500 text-xl">{{ $total_hari_kerja }}</p>
                </div>

                <div>
                    <p>Jam Kerja</p>
                    <p class="font-bold text-green-500 text-xl">{{ $total_jam_kerja }}</p>
                </div>

                <div>
                    <p>Jam Lembur</p>
                    <p class="font-bold text-green-500 text-xl">{{ $total_jam_lembur }}</p>
                </div>

                <div>
                    <p>Keterlambatan</p>
                    <p class="font-bold text-green-500 text-xl">{{ $total_keterlambatan }}</p>
                </div>

            </div>


        </div>
    </div>

    @foreach ($dataArr as $d)
        <div class="flex px-3 pb-4 justify-center">
            <div
                class="flex border-pink-500 border-l-8 rounded-lg bg-blue-100 w-screen h-20 items-center p-2 rounded-lg shadow-lg justify-around  ">
                <div
                    class="rounded-full bg-white w-10 h-10 flex justify-center items-center font-bold text-xl text-green-500">
                    {{ $d['tgl'] }}
                </div>
                <div class="text-center">
                    <p class="text-gray-500">Jam Kerja</p>
                    <p class="font-bold text-blue-500">
                        {{ $d['jam_kerja'] }}
                    </p>
                </div>
                <div class="text-center">
                    <p class="text-gray-500">Terlambat </p>
                    <p class="font-bold text-blue-500">
                        {{ $d['terlambat'] }}
                    </p>

                </div>
                <div class="text-center">
                    <p class="text-gray-500">Jam Lembur</p>
                    <p class="font-bold text-blue-500">{{ $d['jam_lembur'] }}
                    </p>
                </div>
            </div>
        </div>
    @endforeach


    <div class="text-right px-5 py-2">
        <a href="{{ route('logout') }}"
            onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
            <i class="nav-icon fa-solid fa-right-from-bracket"></i>
            <button class="rounded-xl shadow bg-purple-500 text-white px-3 py-2">Logout</button>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>

</body>

</html>
