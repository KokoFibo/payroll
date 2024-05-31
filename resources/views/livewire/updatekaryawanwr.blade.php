<div>
    @section('title', 'Update Karyawan')
    <div class="container ">

        <div class="card mt-3 ">
            <div class="card-header bg-secondary">
                <h5 class="text-light py-2">{{ __('Update Data Karyawan') }}</h5>
            </div>

            <div class="card-body">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab">
                        <button class="nav-link active" id="nav-pribadi-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-pribadi" type="button" role="tab" aria-controls="nav-pribadi"
                            aria-selected="false"><span class="fs-5">{{ __('Data Pribadi') }}</span></button>
                        <button class="nav-link" id="nav-identitas-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-identitas" type="button" role="tab" aria-controls="nav-identitas"
                            aria-selected="false"><span class="fs-5">{{ __('Identitas') }}</span></button>
                        <button class="nav-link " id="nav-kepegawaian-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-kepegawaian" type="button" role="tab"
                            aria-controls="nav-kepegawaian" aria-selected="true"><span
                                class="fs-5">{{ __('Data Kepegawaian') }}</span></button>


                        {{-- baris dibawah ini jangan dihapus --}}
                        {{-- @if (!((auth()->user()->role < 4 && $gaji_pokok > 4500000) || auth()->user()->role < 3)) --}}
                        <button class="nav-link " id="nav-payroll-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-payroll" type="button" role="tab" aria-controls="nav-payroll"
                            aria-selected="false"><span class="fs-5">{{ __('Payroll') }}</span></button>
                        {{-- @endif --}}

                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">

                    <div class="tab-pane fade show active p-3" id="nav-pribadi" role="tabpanel"
                        aria-labelledby="nav-pribadi-tab">
                        @include('pribadi')
                    </div>

                    <div class="tab-pane fade p-3" id="nav-identitas" role="tabpanel"
                        aria-labelledby="nav-identitas-tab">
                        @include('identitas')
                    </div>

                    <div class="tab-pane fade p-3" id="nav-kepegawaian" role="tabpanel"
                        aria-labelledby="nav-kepegawaian-tab">
                        @include('kepegawaian')
                    </div>



                    {{-- baris dibawah ini jangan dihapus --}}
                    {{-- @if (!((auth()->user()->role < 4 && $gaji_pokok > 4500000) || auth()->user()->role < 3)) --}}
                    <div class="tab-pane fade p-3" id="nav-payroll" role="tabpanel" aria-labelledby="nav-payroll-tab">
                        @include('payroll')
                    </div>

                    {{-- @endif --}}


                </div>

                {{-- upload file --}}
                <div class="card mx-3 mb-3">
                    <div class='card-body'>
                        <label for="formFileLg" class="form-label">
                            <p>Upload Dokumen <span class="text-danger">*</span> (hanya
                                menerima format jpg, jpeg, png dan pdf</p>
                            <p>khusus untuk file PDF, tidak boleh melebihi 1,024Kb</p>
                        </label>
                        <div class="d-flex flex-row gap-5 align-items-center ">
                            <div>
                                <input class="form-control form-control-lg" id="formFileLg" type="file"
                                    wire:model='files' multiple>
                                @error('files.*')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <button class="btn btn-primary " wire:click="uploadfile" wire:loading.remove>Upload</button>
                            <div wire:loading wire:target='uploadfile'>
                                <div class="d-flex flex-row gap-3">
                                    <h4>Mohon tunggu sampai proses upload selesai</h4>
                                    <div class="spinner-border text-dark" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="d-flex gap-3 pb-3 px-3">

                    <button wire:click="update1" class="btn btn-primary mx-3">{{ __('Update') }}</button>
                    <button wire:click="exit" class="btn btn-dark mx-3">{{ __('Exit') }}</button>
                    @if (!$show_arsip)
                        <button wire:click="arsip" class="btn btn-success mx-3">{{ __('Lihat File Arsip') }}</button>
                    @else
                        <button class="btn btn-dark" wire:click='tutup_arsip'>Sembunyikan Arsip</button>
                    @endif
                </div>
            </div>


            @if ($show_arsip)
                <div class="card mt-3">
                    <div class="card-header">
                        <h3>File Arsip {{ $nama }}</h3>
                    </div>
                    <div class="card-body">
                        @foreach ($personal_files as $fn)
                            @if (strtolower(getFilenameExtension($fn->originalName)) == 'pdf')
                                {{-- <li class="list-group-item "> --}}
                                <div class="d-flex flex-row justify-content-between align-items-center">
                                    <h4> {{ $fn->originalName }}</h4>
                                    <button class="btn btn-danger"
                                        wire:click="deleteFile('{{ $fn->id }}')"wire:loading.remove>Remove</button>
                                    <div wire:loading wire:target='deleteFile'>
                                        <div class="text-center">
                                            <h5>Mohon tunggu sampai proses delete selesai</h5>
                                            <div class="spinner-border text-dark mt-2" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div>
                                    <iframe class="mt-1 mb-3" src="{{ getUrl($fn->filename) }}" width="100%"
                                        height="600px"></iframe>
                                </div>

                                {{-- </li> --}}
                            @endif
                        @endforeach
                        @foreach ($personal_files as $key => $fn)
                            @if (strtolower(getFilenameExtension($fn->originalName)) != 'pdf')
                                {{-- <li class="list-group-item"> --}}
                                <div class="d-flex flex-row gap-5 align-items-center">
                                    <div class="flex flex-col">
                                        <h4> {{ $fn->originalName }}</h4>


                                        <img class="mt-1 mb-3" src="{{ getUrl($fn->filename) }}" alt="">
                                    </div>
                                    <button class="btn btn-danger"
                                        wire:click="deleteFile('{{ $fn->id }}')"wire:loading.remove>Remove</button>
                                    <div wire:loading wire:target='deleteFile'>
                                        <div class="text-center">
                                            <h5>Mohon tunggu sampai proses delete selesai</h5>
                                            <div class="spinner-border text-dark mt-2" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- </li> --}}
                            @endif
                        @endforeach
                        <button class="btn btn-dark" wire:click='tutup_arsip'>Sembunyikan Arsip</button>
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>
