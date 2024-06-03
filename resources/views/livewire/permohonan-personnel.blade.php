<div>
    <div>
        <div class="card m-3">
            <div class="card-header">
                <h3>Form Permohonan Personnel</h3>
            </div>
            <div class="card-body">
                <div class="d-flex">
                    <div class="mb-3 col-6">
                        <label for="posisi" class="form-label">Posisi</label>
                        <input type="text" class="form-control" id="posisi">
                    </div>
                    <div class="mb-3 col-6">
                        <label for="jumlah_yang_dibutuhkan" class="form-label">Jumlah yang dibutuhkan</label>
                        <input type="text" class="form-control" id="jumlah_yang_dibutuhkan">
                    </div>
                </div>
                <div class="d-flex">
                    <div class="mb-3 col-6">
                        <label for="level_posisi" class="form-label">Level posisi</label>
                        <input type="text" class="form-control" id="level_posisi">
                    </div>
                    <div class="mb-3 col-6">
                        <label for="manpower_posisi" class="form-label">Manpower posisi</label>
                        <input type="text" class="form-control" id="manpower_posisi">
                    </div>
                </div>
                <div class="d-flex">
                    <div class="mb-3 col-6">
                        <label for="jumlah_manpower_saat_ini" class="form-label">Jumlah manpower saat ini</label>
                        <input type="text" class="form-control" id="jumlah_manpower_saat_ini">
                    </div>
                    <div class="mb-3 col-6">
                        <label for="waktu_masuk_kerja" class="form-label">Waktu masuk kerja</label>
                        <input type="text" class="form-control" id="waktu_masuk_kerja">
                    </div>
                </div>
                <div class="d-flex">
                    <div class="mb-3 col-6">
                        <label for="job_desc" class="form-label">Job description</label>
                        <input type="text" class="form-control" id="job_desc">
                    </div>
                    <div class="mb-3 col-6">
                        <label for="usia" class="form-label">Usia</label>
                        <input type="text" class="form-control" id="usia">
                    </div>
                </div>
                <div class="d-flex">
                    <div class="mb-3 col-6">
                        <label for="pendidikan" class="form-label">Pendidikan</label>
                        <input type="text" class="form-control" id="pendidikan">
                    </div>
                    <div class="mb-3 col-6">
                        <label for="pengalaman_kerja" class="form-label">Pengalaman kerja (tahun)</label>
                        <input type="text" class="form-control" id="pengalaman_kerja">
                    </div>
                </div>
                <div class="d-flex">
                    <div class="mb-3 col-6">
                        <label for="kualifikasi_lain" class="form-label">Kualifikasi lain</label>
                        <input type="text" class="form-control" id="kualifikasi_lain">
                    </div>
                    <div class="mb-3 col-6">
                        <label for="kisaran_gaji" class="form-label">Kisaran gaji</label>
                        <input type="text" class="form-control" id="kisaran_gaji">
                    </div>
                </div>

                <div class="d-flex">
                    {{-- Jenis kelamin --}}
                    <div class="mb-3 col-4">
                        <label class="form-label">Jenis kelamin</label>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="pria">
                            <label class="form-check-label" for="pria">
                                Pria
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="wanita">
                            <label class="form-check-label" for="wanita">
                                Wanita
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="bebas">
                            <label class="form-check-label" for="bebas">
                                Bebas
                            </label>
                        </div>
                    </div>
                    {{-- Skill wajib --}}
                    <div class="mb-3 col-4">
                        <label for="skill_wajib" class="form-label">Skill wajib</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="bahasa_inggris">
                            <label class="form-check-label" for="bahasa_inggris">
                                Bahasa inggris
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="bahasa_mandarin">
                            <label class="form-check-label" for="bahasa_mandarin">
                                Bahasa mandarin
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="komputer">
                            <label class="form-check-label" for="komputer">
                                Komputer
                            </label>
                        </div>

                    </div>
                    {{-- Alasan permohonan --}}
                    <div class="mb-3 col-4">
                        <label for="alasan_permohonan" class="form-label">Alasan permohonan</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value=""
                                id="menggantikan_yang_resign">
                            <label class="form-check-label" for="menggantikan_yang_resign">
                                Menggantikan yang resign
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value=""
                                id="menggantikan_yang_dimutasi">
                            <label class="form-check-label" for="menggantikan_yang_dimutasi">
                                Menggantikan yang dimutasi
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value=""
                                id="beban_kerja_bertambah">
                            <label class="form-check-label" for="beban_kerja_bertambah">
                                Beban kerja bertambah
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="pengembangan_bisnis">
                            <label class="form-check-label" for="pengembangan_bisnis">
                                Pengembangan bisnis
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Request By  --}}
                <div class="card-body rounded my-3" style="background-color: rgb(226, 216, 216)">
                    <div class="d-flex">
                        <div class="mb-3 col-4">
                            <label for="approved_1" class="form-label">Request by</label>
                            <input type="text" class="form-control" id="approved_1">
                        </div>

                        <div class="mb-3 col-4">
                            <label for="approved_1" class="form-label">Date of request</label>
                            <input type="text" class="form-control" id="approved_1">
                        </div>
                    </div>
                </div>
                {{-- Approved 1 --}}
                <div class="card-body rounded my-3" style="background-color: rgb(226, 216, 216)">
                    <div class="d-flex">
                        <div class="mb-3 col-4">
                            <label for="approved_1" class="form-label">1st Approval by</label>
                            <input type="text" class="form-control" id="approved_1">
                        </div>
                        <div class="mb-3 col-4">
                            <div class="form-check text-center ">
                                <div>
                                    <label for="approved_1" class="form-label">Signature</label>
                                </div>

                                <div class="mt-1">
                                    <input class="form-check-input" type="checkbox" value="" id="approved_1">
                                    <label class="form-check-label" for="approved_1">
                                        Approve
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 col-4">
                            <label for="approved_1" class="form-label">Date of approval</label>
                            <input type="text" class="form-control" id="approved_1">
                        </div>
                    </div>
                </div>

                {{-- Approved 2 --}}
                <div class="card-body rounded my-3" style="background-color: rgb(226, 216, 216)">
                    <div class="d-flex">
                        <div class="mb-3 col-4">
                            <label for="approved_2" class="form-label">2nd Approval by</label>
                            <input type="text" class="form-control" id="approved_2">
                        </div>
                        <div class="mb-3 col-4">
                            <div class="form-check text-center ">
                                <div>
                                    <label for="approved_2" class="form-label">Signature</label>
                                </div>

                                <div class="mt-1">
                                    <input class="form-check-input" type="checkbox" value="" id="approved_2">
                                    <label class="form-check-label" for="approved_2">
                                        Approve
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 col-4">
                            <label for="approved_2" class="form-label">Date of approval</label>
                            <input type="text" class="form-control" id="approved_2">
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
