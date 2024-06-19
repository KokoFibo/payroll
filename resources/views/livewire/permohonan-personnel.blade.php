<div class='container'>
    {{-- <p>skil_wajib: {{ var_export($skil_wajib) }}</p>
    <p>alasan_permohonan: {{ var_export($alasan_permohonan) }}</p> --}}
    <p>is_requester = {{ $is_requester }}</p>
    <p>is_approval_1 = {{ $is_approval_1 }}</p>
    <p>is_approvel_2 = {{ $is_approvel_2 }}</p>
    <p>is_admin = {{ $is_admin }}</p>
    <p>approve_1 = {{ $approve_1 }}</p>
    <p>approve_2 = {{ $approve_2 }}</p>
    <div class='mt-3 p-3'>
        <h4>Hello, {{ auth()->user()->name }}</h4>
        @if (!$is_add && !$is_update && $is_requester)
            <button class='btn btn-primary' wire:click='add'>Click to make new Request</button>
        @endif
    </div>
    @if ($is_add || $is_update)
        <div>
            <div class="card m-3">
                <div class="card-header">
                    <h3>Form Permohonan Personnel</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex">

                        <div class="mb-3 col-6">
                            <label for="posisi" class="form-label">Posisi</label>
                            <input {{ !$is_requester ? 'disabled' : '' }} wire:model='posisi' type="text"
                                class="form-control" id="posisi">
                            @error('posisi')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3 col-6">
                            <label for="jumlah_yang_dibutuhkan" class="form-label">Jumlah yang dibutuhkan</label>
                            <input {{ !$is_requester ? 'disabled' : '' }} wire:model='jumlah_dibutuhkan' type="text"
                                class="form-control" id="jumlah_yang_dibutuhkan">
                            @error('jumlah_dibutuhkan')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>
                    <div class="d-flex">
                        <div class="mb-3 col-6">
                            <label for="level_posisi" class="form-label">Level posisi</label>
                            <input {{ !$is_requester ? 'disabled' : '' }} wire:model='level_posisi' type="text"
                                class="form-control" id="level_posisi">
                            @error('level_posisi')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3 col-6">
                            <label for="manpower_posisi" class="form-label">Manpower posisi</label>
                            <input {{ !$is_requester ? 'disabled' : '' }} wire:model='manpower_posisi' type="text"
                                class="form-control" id="manpower_posisi">
                            @error('manpower_posisi')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="mb-3 col-6">
                            <label for="jumlah_manpower_saat_ini" class="form-label">Jumlah manpower saat ini</label>
                            <input {{ !$is_requester ? 'disabled' : '' }} wire:model='jumlah_manpower_saat_ini'
                                type="text" class="form-control" id="jumlah_manpower_saat_ini">
                            @error('jumlah_manpower_saat_ini')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3 col-6">
                            <label for="waktu_masuk_kerja" class="form-label">Waktu masuk kerja</label>
                            <input {{ !$is_requester ? 'disabled' : '' }} wire:model='waktu_masuk_kerja' type="text"
                                class="form-control" id="waktu_masuk_kerja">
                            @error('waktu_masuk_kerja')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="mb-3 col-6">
                            <label for="job_desc" class="form-label">Job description</label>
                            <input {{ !$is_requester ? 'disabled' : '' }} wire:model='job_description' type="text"
                                class="form-control" id="job_desc">
                            @error('job_description')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3 col-6">
                            <label for="usia" class="form-label">Usia</label>
                            <input {{ !$is_requester ? 'disabled' : '' }} wire:model='usia' type="text"
                                class="form-control" id="usia">
                            @error('usia')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="mb-3 col-6">
                            <label for="pendidikan" class="form-label">Pendidikan</label>
                            <input {{ !$is_requester ? 'disabled' : '' }} wire:model='pendidikan' type="text"
                                class="form-control" id="pendidikan">
                            @error('pendidikan')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3 col-6">
                            <label for="pengalaman_kerja" class="form-label">Pengalaman kerja (tahun)</label>
                            <input {{ !$is_requester ? 'disabled' : '' }} wire:model='pengalaman_kerja' type="text"
                                class="form-control" id="pengalaman_kerja">
                            @error('pengalaman_kerja')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="mb-3 col-6">
                            <label for="kualifikasi_lain" class="form-label">Kualifikasi lain</label>
                            <input {{ !$is_requester ? 'disabled' : '' }} wire:model='kualifikasi_lain' type="text"
                                class="form-control" id="kualifikasi_lain">
                            @error('kualifikasi_lain')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3 col-6">
                            <label for="kisaran_gaji" class="form-label">Kisaran gaji</label>
                            <input {{ !$is_requester ? 'disabled' : '' }} wire:model='kisaran_gaji' type="text"
                                class="form-control" id="kisaran_gaji">
                            @error('kisaran_gaji')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex">
                        {{--  Gender --}}
                        <div class="mb-3 col-4">
                            <label class="form-label">Gender</label>

                            <div class="form-check">
                                <input {{ !$is_requester ? 'disabled' : '' }} wire:model='gender' value='Pria'
                                    class="form-check-input" type="radio" name="flexRadioDefault" id="pria">
                                <label class="form-check-label" for="pria">
                                    Pria
                                </label>
                            </div>
                            <div class="form-check">
                                <input {{ !$is_requester ? 'disabled' : '' }} wire:model='gender' value='Wanita'
                                    class="form-check-input" type="radio" name="flexRadioDefault" id="wanita">
                                <label class="form-check-label" for="wanita">
                                    Wanita
                                </label>
                            </div>
                            <div class="form-check">
                                <input {{ !$is_requester ? 'disabled' : '' }} wire:model='gender' value='Bebas'
                                    class="form-check-input" type="radio" name="flexRadioDefault" id="bebas">
                                <label class="form-check-label" for="bebas">
                                    Bebas
                                </label>
                            </div>
                            @error('gender')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        {{-- Skill wajib --}}
                        <div class="mb-3 col-4">
                            <label for="skill_wajib" class="form-label">Skill wajib</label>
                            <div class="form-check">
                                <input {{ !$is_requester ? 'disabled' : '' }} wire:model.live='skil_wajib'
                                    class="form-check-input" type="checkbox" value="Bahasa inggris"
                                    id="bahasa_inggris">
                                <label class="form-check-label" for="bahasa_inggris">
                                    Bahasa inggris
                                </label>
                            </div>
                            <div class="form-check">
                                <input {{ !$is_requester ? 'disabled' : '' }} wire:model.live='skil_wajib'
                                    class="form-check-input" type="checkbox" value="Bahasa mandarin"
                                    id="bahasa_mandarin">
                                <label class="form-check-label" for="bahasa_mandarin">
                                    Bahasa mandarin
                                </label>
                            </div>
                            <div class="form-check">
                                <input {{ !$is_requester ? 'disabled' : '' }} wire:model.live='skil_wajib'
                                    class="form-check-input" type="checkbox" value="Komputer" id="komputer">
                                <label class="form-check-label" for="komputer">
                                    Komputer
                                </label>
                            </div>
                            @error('skil_wajib')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        {{-- Alasan permohonan --}}
                        <div class="mb-3 col-4">
                            <label for="alasan_permohonan" class="form-label">Alasan permohonan</label>
                            <div class="form-check">
                                <input {{ !$is_requester ? 'disabled' : '' }} wire:model.live='alasan_permohonan'
                                    class="form-check-input" type="checkbox" value="Menggantikan yang resign"
                                    id="menggantikan_yang_resign">
                                <label class="form-check-label" for="menggantikan_yang_resign">
                                    Menggantikan yang resign
                                </label>
                            </div>
                            <div class="form-check">
                                <input {{ !$is_requester ? 'disabled' : '' }} wire:model.live='alasan_permohonan'
                                    class="form-check-input" type="checkbox" value="Menggantikan yang dimutasi"
                                    id="menggantikan_yang_dimutasi">
                                <label class="form-check-label" for="menggantikan_yang_dimutasi">
                                    Menggantikan yang dimutasi
                                </label>
                            </div>
                            <div class="form-check">
                                <input {{ !$is_requester ? 'disabled' : '' }} wire:model.live='alasan_permohonan'
                                    class="form-check-input" type="checkbox" value="Beban kerja bertambah"
                                    id="beban_kerja_bertambah">
                                <label class="form-check-label" for="beban_kerja_bertambah">
                                    Beban kerja bertambah
                                </label>
                            </div>
                            <div class="form-check">
                                <input {{ !$is_requester ? 'disabled' : '' }} wire:model.live='alasan_permohonan'
                                    class="form-check-input" type="checkbox" value="Pengembangan bisnis"
                                    id="pengembangan_bisnis">
                                <label class="form-check-label" for="pengembangan_bisnis">
                                    Pengembangan bisnis
                                </label>
                            </div>
                            @error('alasan_permohonan')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    @if ($is_requester)

                        @if ($is_add)
                            <div wire:click='save' class="button btn btn-primary">Submit</div>
                        @endif
                        @if ($is_update)
                            <div wire:click='update' class="button btn btn-primary">Update</div>
                        @endif
                        <div wire:click='exit' class="ml-5 button btn btn-dark">Exit/Cancel</div>
                    @endif



                    {{-- Request By  --}}
                    <div class="card-body rounded my-3" style="background-color: rgb(226, 216, 216)">
                        <div class="d-flex">
                            <div class="mb-3 col-4">
                                <label for="approved_1" class="form-label">Request by</label>
                                {{-- <input wire:model='requester_id' type="text" class="form-control"
                                    id="approved_1"> --}}
                                <input value='{{ getName($requester_id) }}' type="text" class="form-control"
                                    id="approved_1" disabled>
                            </div>

                            <div class="mb-3 col-4">
                                <label for="approved_1" class="form-label">Date of request</label>
                                {{-- <input wire:model='tgl_request' type="text" class="form-control" id="approved_1"> --}}
                                <input value='{{ format_tgl($tgl_request) }}' type="text" class="form-control"
                                    id="approved_1" disabled>
                            </div>
                        </div>
                    </div>

                    {{-- Approved 1 --}}
                    @if ($is_approval_1 || $is_admin)
                        <div class="card-body rounded my-3" style="background-color: rgb(226, 216, 216)">
                            <div class="d-flex">
                                <div class="mb-3 col-4">
                                    <label for="approved_1" class="form-label">1st Approval by</label>
                                    <input {{ $is_admin ? 'disabled' : '' }} wire:model='' type="text"
                                        class="form-control" id="approved_1">
                                </div>
                                <div class="mb-3 col-4">
                                    <div class="form-check text-center ">
                                        <div>
                                            <label for="approved_1" class="form-label">Signature</label>
                                        </div>

                                        <div class="mt-1">
                                            <input {{ $is_admin ? 'disabled' : '' }} wire:model.live='approve_1'
                                                class="form-check-input" type="checkbox" value="true"
                                                id="approved_1">
                                            <label class="form-check-label" for="approved_1">
                                                Approve
                                            </label>
                                        </div>
                                        @error('approve_1')
                                            <div class="text-danger">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 col-4">
                                    <label for="approved_1" class="form-label">Date of approval</label>
                                    <input {{ $is_admin ? 'disabled' : '' }} wire:model.live='' type="text"
                                        class="form-control" id="approved_1">
                                </div>
                            </div>
                        </div>
                        @if ($is_approvel_2)
                            <button wire:click='save_approve_1' class="btn btn-primary">Approve</button>
                            <button wire:click='exit' class="btn btn-dark">Exit</button>
                        @endif
                    @endif
                    {{-- Approved 2 --}}
                    @if ($is_approvel_2 || $is_admin)
                        <div class="card-body rounded my-3" style="background-color: rgb(226, 216, 216)">
                            <div class="d-flex">
                                <div class="mb-3 col-4">
                                    <label for="approved_2" class="form-label">2nd Approval by</label>
                                    <input {{ $is_admin ? 'disabled' : '' }} wire:model='' type="text"
                                        class="form-control" id="approved_2">
                                </div>
                                <div class="mb-3 col-4">
                                    <div class="form-check text-center ">
                                        <div>
                                            <label for="approved_2" class="form-label">Signature</label>
                                        </div>

                                        <div class="mt-1">
                                            <input {{ $is_admin ? 'disabled' : '' }} wire:model.live='approve_2'
                                                class="form-check-input" type="checkbox" value="true"
                                                id="approved_2">
                                            <label class="form-check-label" for="approved_2">
                                                Approve
                                            </label>
                                        </div>
                                        @error('approve_2')
                                            <div class="text-danger">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 col-4">
                                    <label for="approved_2" class="form-label">Date of approval</label>
                                    <input {{ $is_admin ? 'disabled' : '' }} wire:model='' type="text"
                                        class="form-control" id="approved_2">
                                </div>
                            </div>
                        </div>
                        @if ($is_approvel_2)
                            <button wire:click='save_approve_2' class="btn btn-primary">Click to Approve</button>
                            <button wire:click='exit' class="btn btn-dark">Exit</button>
                        @endif
                    @endif
                    @if ($is_admin)
                        <button wire:click='exit' class="btn btn-dark">Exit</button>
                    @endif
                </div>
            </div>
        </div>
    @endif
    {{-- table --}}
    @if (!$is_add && !$is_update)
        <div>
            <div class="card">
                <div class="card-header">
                    <h3>List of Request</h3>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Requester</th>
                                <th>Posisi</th>
                                <th>Jumlah dibutuhkan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $d)
                                <tr>
                                    <td>{{ $d->id }}</td>
                                    <td>{{ getName($d->requester_id) }}</td>
                                    {{-- <td>{{ $d->personellrequestform->posisi }}</td> --}}
                                    <td>{{ $d->posisi }}</td>
                                    {{-- <td>{{ $d->personellrequestform->jumlah_dibutuhkan }}</td> --}}
                                    <td>{{ $d->jumlah_dibutuhkan }}</td>
                                    <td>{{ $d->status }}</td>
                                    <td>
                                        <button wire:click='edit({{ $d->id }})'
                                            class="btn btn-warning btn-sm">{{ $is_requester ? 'Edit' : 'Show' }}</button>
                                        @if ($is_requester)
                                            <button wire:click='deleteConfirmation({{ $d->id }})'
                                                class="btn btn-danger btn-sm">Delete</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
    @script
        <script>
            window.addEventListener("show-delete-confirmation", (event) => {
                Swal.fire({
                    title: "Yakin mau delete data Requester ini?",
                    text: event.detail.text,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, delete",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $wire.dispatch("delete-confirmed");
                    }
                });
            });
        </script>
    @endscript
</div>
