<div>
    <p>is_registered: {{ $is_registered }}</p>
    <p>Show: {{ $show }}</p>
    <p>Gender: {{ $gender }}</p>
    <p>is_update: {{ $is_update }}</p>
    @if (!$show)
        <button class="bg-green-500 text-white px-3 py-2 rounded-xl" wire:click="register">Register</button>
        <button class="bg-blue-500 text-white px-3 py-2 rounded-xl" wire:click="alreadyRegistered">Sudah Pernah
            Register</button>
    @endif

    @if ($is_registered)
        <label for="">Email</label>
        <input type="email" wire:model="registeredEmail">
        <input type="password" wire:model="registeredPassword">
        <button wire:click="submit">Submit</button>
    @endif

    @if ($show)
        <div>
            <h1 class="mt-10 text-center text-blue-500 lg:text-4xl text-2xl lg:font-semibold">Form Pendaftaran
                Calon Karyawan
            </h1>
            <h3 class="text-center lg:text-2xl my-2 mb-4">Mohon dilengkapi dan diperiksa sebelum tekan submit</h3>
            <div class="lg:w-2/3 w-full mx-auto">
                <form wire:submit='save'>
                    <div class="p-3 grid gap-6 mb-6 md:grid-cols-2">
                        {{-- <div class="lg:w-[800px]"> --}}
                        <div>
                            <label for="nama_lengkap" class="block mb-2 text-sm font-medium text-gray-900 ">Nama
                                Lengkap<span class="text-red-500 ml-1">*</span></label>
                            <input wire:model='nama' type="text" id="nama_lengkap"
                                class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full" />
                            @error('nama')
                                <div class="text-red-500">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email<span
                                    class="text-red-500 ml-1">*</span></label>
                            <input wire:model='email' type="email" id="email"
                                class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"
                                required />
                            @error('email')
                                <div class="text-red-500">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password<span
                                    class="text-red-500 ml-1">*</span></label>
                            <input wire:model='password' type="password" id="password"
                                class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"
                                required />
                            @error('password')
                                <div class="text-red-500">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div>
                            <label for="confirm_password" class="block mb-2 text-sm font-medium text-gray-900">Confirm
                                Password<span class="text-red-500 ml-1">*</span></label>
                            <input wire:model='confirm_password' type="password" id="confirm_password"
                                class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"
                                required />
                            @error('confirm_password')
                                <div class="text-red-500">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div>
                            <label for="hp" class="block mb-2 text-sm font-medium text-gray-900">Handphone<span
                                    class="text-red-500 ml-1">*</span></label>
                            <input wire:model='hp' type="text" id="hp"
                                class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"
                                placeholder="087812345678" required />
                            @error('hp')
                                <div class="text-red-500">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div>
                            <label for="telp" class="block mb-2 text-sm font-medium text-gray-900">Telepon<span
                                    class="text-red-500 ml-1">*</span></label>
                            <input wire:model='telp' type="text" id="telp"
                                class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"
                                placeholder="021569612341234" />
                            @error('telp')
                                <div class="text-red-500">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div>
                            <label for="tempat_lahir" class="block mb-2 text-sm font-medium text-gray-900">Kota
                                Kelahiran<span class="text-red-500 ml-1">*</span></label>
                            <input wire:model='tempat_lahir' type="text" id="tempat_lahir"
                                class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"
                                required />
                            @error('tempat_lahir')
                                <div class="text-red-500">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div>
                            <label for="tgl_lahir" class="block mb-2 text-sm font-medium text-gray-900">Tanggal
                                Lahir<span class="text-red-500 ml-1">*</span></label>
                            <input wire:model='tgl_lahir' type="date" id="tgl_lahir"
                                class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"
                                required />
                            @error('tgl_lahir')
                                <div class="text-red-500">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div>
                            <label for="gender" class="block mb-2 text-sm font-medium text-gray-900">Gender<span
                                    class="text-red-500 ml-1">*</span></label>
                            <div class="flex w-full gap-3">
                                <div class="flex w-1/2 items-center ps-4 border border-gray-200 rounded">
                                    <input wire:model='gender' id="bordered-radio-1" type="radio" value="Laki-laki"
                                        name="bordered-radio"
                                        class=" text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="bordered-radio-1"
                                        class="w-full py-2.5 ms-2 text-sm font-medium text-gray-900">Pria</label>
                                </div>
                                <div class="flex w-1/2 items-center ps-4 border border-gray-200 rounded">
                                    <input wire:model='gender' id="bordered-radio-2" type="radio" value="Perempuan"
                                        name="bordered-radio"
                                        class=" text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="bordered-radio-2"
                                        class="w-full py-2.5 ms-2 text-sm font-medium text-gray-900">Wanita</label>
                                </div>
                            </div>
                            @error('gender')
                                <div class="text-red-500">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div>
                            <label for="status_pernikahan" class="block mb-2 text-sm font-medium text-gray-900">Status
                                Pernikahan<span class="text-red-500 ml-1">*</span></label>
                            <select id="status_pernikahan" wire:model='status_pernikahan'
                                class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="" selected>Pilih Status Pernikahan</option>
                                <option value="Belum Kawin">Belum Kawin</option>
                                <option value="Kawin">Kawin</option>
                                <option value="Cerai Hidup">Cerai Hidup</option>
                                <option value="Cerai Mati">Cerai Mati</option>
                            </select>
                            @error('status_pernikahan')
                                <div class="text-red-500">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div>
                            <label for="golongan_darah" class="block mb-2 text-sm font-medium text-gray-900">Golongan
                                Darah<span class="text-red-500 ml-1">*</span></label>
                            <select id="golongan_darah" wire:model='golongan_darah'
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value=" ">{{ __('Pilih golongan darah') }}</option>
                                <option value="O">O</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="AB">AB</option>
                            </select>
                            @error('golongan_darah')
                                <div class="text-red-500">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div>
                            <label for="agama" class="block mb-2 text-sm font-medium text-gray-900">Agama<span
                                    class="text-red-500 ml-1">*</span></label>
                            <select id="agama" wire:model='agama'
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value=" ">{{ __('Pilih agama') }}</option>
                                <option value="Islam">{{ __('Islam') }}</option>
                                <option value="Kristen">{{ __('Kristen') }}</option>
                                <option value="Hindu">{{ __('Hindu') }}</option>
                                <option value="Budha">{{ __('Budha') }}</option>
                                <option value="Katolik">{{ __('Katolik') }}</option>
                                <option value="Konghucu">{{ __('Konghucu') }}</option>
                            </select>
                            @error('agama')
                                <div class="text-red-500">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div>
                            <label for="etnis" class="block mb-2 text-sm font-medium text-gray-900">Etnis<span
                                    class="text-red-500 ml-1">*</span></label>
                            <select id="etnis" wire:model='etnis'
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value=" ">{{ __('Pilih Etnis') }}</option>
                                <option value="Batak">{{ __('Batak') }}</option>
                                <option value="China">{{ __('China') }}</option>
                                <option value="Jawa">{{ __('Jawa') }}</option>
                                <option value="Sunda">{{ __('Sunda') }}</option>
                                <option value="Lampung">{{ __('Lampung') }}</option>
                                <option value="Palembang">{{ __('Palembang') }}</option>
                                <option value="Tionghoa">{{ __('Tionghoa') }}</option>
                                <option value="Lainnya">{{ __('Lainnya') }}</option>
                            </select>
                            @error('etnis')
                                <div class="text-red-500">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div>
                            <label for="nama_contact_darurat"
                                class="block mb-2 text-sm font-medium text-gray-900">Nama
                                Kontak
                                Darurat<span class="text-red-500 ml-1">*</span></label>
                            <input wire:model='nama_contact_darurat' type="text" id="nama_contact_darurat"
                                class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"
                                required />
                            @error('nama_contact_darurat')
                                <div class="text-red-500">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div>
                            <label for="contact_darurat_1"
                                class="block mb-2 text-sm font-medium text-gray-900">Handphone
                                Kontak Darurat 1<span class="text-red-500 ml-1">*</span></label>
                            <input wire:model='contact_darurat_1' type="text" id="contact_darurat_1"
                                class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"
                                required />
                            @error('contact_darurat_1')
                                <div class="text-red-500">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div>
                            <label for="contact_darurat_2"
                                class="block mb-2 text-sm font-medium text-gray-900">Handphone
                                Kontak Darurat 2</label>
                            <input wire:model='contact_darurat_2' type="text" id="contact_darurat_2"
                                class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"
                                required />
                            @error('contact_darurat_2')
                                <div class="text-red-500">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div>
                            <label for="jenis_identitas" class="block mb-2 text-sm font-medium text-gray-900">Jenis
                                Identitas<span class="text-red-500 ml-1">*</span></label>
                            <select id="jenis_identitas" wire:model='jenis_identitas'
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value=" ">{{ __('Pilih jenis Identitas') }}</option>
                                <option value="KTP">{{ __('KTP') }}</option>
                                <option value="Passport">{{ __('Passport') }}</option>
                            </select>
                            @error('jenis_identitas')
                                <div class="text-red-500">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div>
                            <label for="no_identitas" class="block mb-2 text-sm font-medium text-gray-900">Nomor
                                Identitas<span class="text-red-500 ml-1">*</span></label>
                            <input wire:model='no_identitas' type="text" id="no_identitas"
                                class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"
                                required />
                            @error('no_identitas')
                                <div class="text-red-500">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div>
                            <label for="alamat_identitas" class="block mb-2 text-sm font-medium text-gray-900">Alamat
                                Identitas<span class="text-red-500 ml-1">*</span></label>
                            <textarea id="message" rows="4" wire:model='alamat_identitas'
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-50"></textarea>
                            @error('alamat_identitas')
                                <div class="text-red-500">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div>
                            <label for="alamat_tinggal_sekarang"
                                class="block mb-2 text-sm font-medium text-gray-900">Alamat
                                Tinggal
                                Sekarang<span class="text-red-500 ml-1">*</span></label>
                            <textarea id="message" rows="4" wire:model='alamat_tinggal_sekarang'
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-50"></textarea>
                            @error('alamat_tinggal_sekarang')
                                <div class="text-red-500">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div>

                            <label class="block mb-2 text-sm font-medium text-gray-900" for="upload_files">Upload
                                Dokumen
                                (hanya menerima format jpg, png, pdf, zip, rar)<span
                                    class="text-red-500 ml-1">*</span></label>
                            <input wire:model='files' multiple
                                class="filepond block w-full px-2 py-3 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50"
                                id="upload_files" type="file">
                            @error('files')
                                <div class="text-red-500">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>
                    <div class="flex">
                        <div class="md:px-0 px-3">
                            @if ($is_update == false)
                                <button type="submit"
                                    class="w-full md:mx-0 mb-5 px-5 py-2.5 md:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm    text-center ">
                                    <span>Submit</span>
                                </button>
                            @else
                                <button type="submit"
                                    class="w-full md:mx-0 mb-5 px-5 py-2.5 md:w-auto text-white bg-orange-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm    text-center ">
                                    <span>Update</span>
                                </button>
                            @endif
                        </div>
                        <div role="status" wire:loading wire:target='save'>
                            <svg aria-hidden="true"
                                class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                                viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                    fill="currentColor" />
                                <path
                                    d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                    fill="currentFill" />
                            </svg>
                        </div>
                    </div>
                    @if ($files)
                        @foreach ($files as $file)
                            <div class='mb-10'>
                                <img src="{{ $file->temporaryUrl() }}" style="width:300px">
                            </div>
                        @endforeach
                    @endif

                </form>
                @if ($filenames)
                    @foreach ($filenames as $fn)
                        <p class="m-3">{{ $fn->originalName }}</p>
                        <button class="px-3 py-1 bg-red-500 text-white"
                            wire:click="deleteFile('{{ $fn->filename }}')">delete</button>
                        <img class="w-[400px]" src="{{ getUrl($fn->filename) }}" alt="">
                    @endforeach
                @endif
            </div>
        </div>
    @endif
    @section('links')
    @endsection
    @push('script')
    @endpush
</div>
