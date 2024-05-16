<div>
    <h1 class="mt-10 text-center text-blue-500 lg:text-4xl text-2xl lg:font-semibold">Form Pendaftaran Calon Karyawan
    </h1>
    <h3 class="text-center lg:text-2xl my-2 mb-4">Mohon dilengkapi dan diperiksa sebelum tekan submit</h3>
    <div class="lg:w-2/3 w-full mx-auto">
        <form>
            <div class="p-3 grid gap-6 mb-6 md:grid-cols-2">
                {{-- <div class="lg:w-[800px]"> --}}
                <div>
                    <label for="nama_lengkap" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
                        Lengkap<span class="text-red-500 ml-1">*</span></label>
                    <input type="text" id="nama_lengkap"
                        class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                </div>
                <div>
                    <label for="email"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email<span
                            class="text-red-500 ml-1">*</span></label>
                    <input type="email" id="email"
                        class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        required />
                </div>
                <div>
                    <label for="hp"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Handphone<span
                            class="text-red-500 ml-1">*</span></label>
                    <input type="text" id="hp"
                        class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="087812345678" required />
                </div>
                <div>
                    <label for="telp"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Telepon<span
                            class="text-red-500 ml-1">*</span></label>
                    <input type="text" id="telp"
                        class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="021569612341234" />
                </div>
                <div>
                    <label for="tempat_lahir" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kota
                        Kelahiran<span class="text-red-500 ml-1">*</span></label>
                    <input type="text" id="tempat_lahir"
                        class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        required />
                </div>
                <div>
                    <label for="tgl_lahir" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal
                        Lahir<span class="text-red-500 ml-1">*</span></label>
                    <input type="date" id="tgl_lahir"
                        class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        required />
                </div>
                <div>
                    <label for="gender"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gender<span
                            class="text-red-500 ml-1">*</span></label>
                    <div class="flex w-full gap-3">
                        <div class="flex w-1/2 items-center ps-4 border border-gray-200 rounded dark:border-gray-700">
                            <input id="bordered-radio-1" type="radio" value="" name="bordered-radio"
                                class=" text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="bordered-radio-1"
                                class="w-full py-2.5 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Pria</label>
                        </div>
                        <div class="flex w-1/2 items-center ps-4 border border-gray-200 rounded dark:border-gray-700">
                            <input checked id="bordered-radio-2" type="radio" value="" name="bordered-radio"
                                class=" text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="bordered-radio-2"
                                class="w-full py-2.5 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Wanita</label>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="status_pernikahan"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status Pernikahan<span
                            class="text-red-500 ml-1">*</span></label>
                    <select id="countries"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected>Pilih Status Pernikahan</option>
                        <option value="Belum Kawin">Belum Kawin</option>
                        <option value="Kawin">Kawin</option>
                        <option value="Cerai Hidup">Cerai Hidup</option>
                        <option value="Cerai Mati">Cerai Mati</option>
                    </select>
                </div>
                <div>
                    <label for="golongan_darah"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Golongan Darah<span
                            class="text-red-500 ml-1">*</span></label>
                    <select id="countries"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value=" ">{{ __('Pilih golongan darah') }}</option>
                        <option value="O">O</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="AB">AB</option>
                    </select>
                </div>
                <div>
                    <label for="agama"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Agama<span
                            class="text-red-500 ml-1">*</span></label>
                    <select id="countries"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value=" ">{{ __('Pilih agama') }}</option>
                        <option value="Islam">{{ __('Islam') }}</option>
                        <option value="Kristen">{{ __('Kristen') }}</option>
                        <option value="Hindu">{{ __('Hindu') }}</option>
                        <option value="Budha">{{ __('Budha') }}</option>
                        <option value="Katolik">{{ __('Katolik') }}</option>
                        <option value="Konghucu">{{ __('Konghucu') }}</option>
                    </select>
                </div>
                <div>
                    <label for="etnis"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Etnis<span
                            class="text-red-500 ml-1">*</span></label>
                    <select id="countries"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
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
                </div>
                <div>
                    <label for="nama_contact_darurat"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Kontak Darurat<span
                            class="text-red-500 ml-1">*</span></label>
                    <input type="text" id="nama_contact_darurat"
                        class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        required />
                </div>
                <div>
                    <label for="contact_darurat1"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Handphone 1<span
                            class="text-red-500 ml-1">*</span></label>
                    <input type="text" id="contact_darurat1"
                        class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        required />
                </div>
                <div>
                    <label for="contact_darurat2"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Handphone 2</label>
                    <input type="text" id="contact_darurat2"
                        class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        required />
                </div>
                <div>
                    <label for="jenis_identitas"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Identitas<span
                            class="text-red-500 ml-1">*</span></label>
                    <select id="countries"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value=" ">{{ __('Pilih jenis Identitas') }}</option>
                        <option value="KTP">{{ __('KTP') }}</option>
                        <option value="Passport">{{ __('Passport') }}</option>
                    </select>
                </div>
                <div>
                    <label for="no_identitas"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nomor Identitas<span
                            class="text-red-500 ml-1">*</span></label>
                    <input type="text" id="no_identitas"
                        class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        required />
                </div>
                <div>
                    <label for="alamat_identitas"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat Identitas<span
                            class="text-red-500 ml-1">*</span></label>
                    <textarea id="message" rows="4"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                </div>
                <div>
                    <label for="alamat_tinggal_sekarang"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat Tinggal
                        Sekarang<span class="text-red-500 ml-1">*</span></label>
                    <textarea id="message" rows="4"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                </div>
                <div>

                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                        for="upload_files">Upload Dokumen (hanya menerima format jpg, png, pdf, zip, rar)<span
                            class="text-red-500 ml-1">*</span></label>
                    <input
                        class="block w-full px-2 py-3 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                        id="upload_files" type="file" multiple>

                </div>

            </div>
            <div class="md:px-0 px-3">
                <button type="submit"
                    class="w-full md:mx-0 mb-5 px-5 py-2.5 md:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm    text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>

            </div>
        </form>
    </div>

</div>
