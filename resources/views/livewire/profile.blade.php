<div>
    <div>
        {{-- <div class="h-screen"> --}}
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
                <div class="flex justify-center">
                    <h2 class="bg-purple-500 text-center text-white text-xl rounded-xl px-5 mt-3">User
                        Profile
                    </h2>
                </div>
            </div>
            <div class="main  flex-1 overflow-y-auto ">

                {{-- Ubah Password --}}
                <div class=" bg-white mx-3 px-3 py-3 mt-3  flex flex-col gap-2 rounded-xl shadow-xl">
                    <div>
                        {{-- <label class="block text-sm font-medium  text-gray-900">Password Lama</label> --}}
                        <div class="relative mt-1 rounded-md shadow-sm">
                            <input type="password" wire:model="old_password" placeholder="Password Lama"
                                class="block w-full rounded-md border-0 py-1.5 pl-3 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:">
                            @error('old_password')
                                <div class="text-red-500">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div>
                        {{-- <label class="block text-sm font-medium  text-gray-900">Password Baru</label> --}}
                        <div class="relative mt-1 rounded-md shadow-sm">
                            <input type="password" wire:model="new_password" placeholder="Password Baru"
                                class="block w-full rounded-md border-0 py-1.5 pl-3 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:">
                            @error('new_password')
                                <div class="text-red-500">
                                    {{ $message }}

                                </div>
                            @enderror
                        </div>
                    </div>
                    <div>
                        {{-- <label class="block text-sm font-medium  text-gray-900">Konfirmasi</label> --}}
                        <div class="relative mt-1 rounded-md shadow-sm">
                            <input type="password" wire:model="confirm_password" placeholder="Konfirmasi"
                                class="block w-full rounded-md border-0 py-1.5 pl-3 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:">
                            @error('confirm_password')
                                <div class="text-red-500">
                                    {{ $message }}

                                </div>
                            @enderror
                        </div>
                    </div>
                    <button wire:click="changePassword"
                        class="bg-purple-500 text-sm text-white px-1 py-1 w-1/3 rounded shadow">Ubah Password</button>
                </div>

                {{-- Ubah Email --}}
                <div class="bg-white mx-3 px-3 py-3 mt-3  flex flex-col gap-2 rounded-xl shadow-xl">

                    <div>
                        {{-- <label class="block text-sm font-medium  text-gray-900">Email Baru</label> --}}
                        <div class="relative mt-1 rounded-md shadow-sm">
                            <input type="text" wire:model="email" placeholder="Email Baru"
                                class="block w-full rounded-md border-0 py-1.5 pl-3 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:">
                            @error('email')
                                <div class="text-red-500">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <button class="bg-purple-500 text-sm text-white px-1 py-1 w-1/3 rounded shadow"
                        wire:click="changeEmail">Ubah
                        Email</button>
                </div>

                {{-- Ubah Bahasa --}}
                <div class="bg-white mx-3 px-3 py-3 mt-3  flex flex-col gap-2 rounded-xl shadow-xl">

                    {{-- <label class="block text-sm font-medium  text-gray-900">Bahasa</label> --}}
                    <div class="flex gap-5">
                        <div>
                            <input wire:model="language" value="Id" type="radio">
                            <label class="form-check-label" for="flexRadioDefault1">Indonesia</label>
                        </div>
                        <div>
                            <input wire:model="language" value="Cn" type="radio">
                            <label class="form-check-label" for="flexRadioDefault1">中文</label>
                        </div>
                    </div>
                    <button class="bg-purple-500 text-sm text-white px-1 py-1 w-1/3 rounded shadow"
                        wire:click="changeLanguage">Ubah
                        Bahasa</button>
                </div>
            </div>


            {{-- Footer --}}
            <div class="footer flex justify-between h-16 items-center bg-gray-800 ">


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
