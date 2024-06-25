<div>
    @if (auth()->user()->role != 8)
        <div class="text-center mt-5">
            <h1>COMING SOON</h1>
            <h4>Under Construction</h4>
        </div>
    @else
        <div class="p-4">
            <h2 class="text-xl font-bold mb-4">Request Time Off Form</h2>
            {{-- <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4"> --}}
            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <div class="mb-4">
                    <label for="requester_name" class="block text-gray-700 text-sm font-bold mb-2">Requester Name</label>
                    <input id="requester_name" name="requester_name" type="text" placeholder="Enter your name"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label for="request_type" class="block text-gray-700 text-sm font-bold mb-2">Request Type</label>
                    <select id="request_type" name="request_type"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="cuti">Cuti</option>
                        <option value="ijin_sakit">Ijin/Sakit</option>
                        <option value="izin_datang_telat">Izin Datang Telat</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="start_date" class="block text-gray-700 text-sm font-bold mb-2">Start Date</label>
                    <input id="start_date" name="start_date" type="date"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label for="end_date" class="block text-gray-700 text-sm font-bold mb-2">End Date</label>
                    <input id="end_date" name="end_date" type="date"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                    <textarea id="description" name="description" rows="4"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        placeholder="Enter description"></textarea>
                </div>
                <div class="mb-4">
                    <label for="file_upload" class="block text-gray-700 text-sm font-bold mb-2">Upload Filename</label>
                    <input id="file_upload" name="file_upload" type="file"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="flex items-center justify-between">
                    <button wire:click='save'
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    @endif

    <div class="mt-20"></div>
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
        @if (is_perbulan())
            <a href="cutirequest"><button
                    class="{{ 'cutirequest' == request()->path() ? 'bg-red-500 ' : '' }} text-purple-200 px-4 py-4 rounded  text-2xl "><i
                        class="fa-brands fa-wpforms"></i>
                </button></a>
        @else
            {{-- href="/userinformation" --}}
            <a wire:navigate href="userinformation"><button
                    class="{{ 'userinformation' == request()->path() ? 'bg-red-500 ' : '' }} text-purple-200 px-4 py-4 rounded  text-2xl "><i
                        class="fa-solid fa-circle-info"></i>
                </button></a>
        @endif

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
