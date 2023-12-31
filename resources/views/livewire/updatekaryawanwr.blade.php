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
                            aria-selected="true"><span class="fs-5">{{ __('Data Pribadi') }}</span></button>
                        <button class="nav-link " id="nav-identitas-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-identitas" type="button" role="tab" aria-controls="nav-identitas"
                            aria-selected="false"><span class="fs-5">{{ __('Identitas') }}</span></button>
                        <button class="nav-link " id="nav-kepegawaian-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-kepegawaian" type="button" role="tab"
                            aria-controls="nav-kepegawaian" aria-selected="false"><span
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
                <div class="d-flex gap-3 pb-3 px-3">

                    <button wire:click="update1" class="btn btn-primary mx-3">{{ __('Update') }}</button>
                    <button wire:click="exit" class="btn btn-dark mx-3">{{ __('Exit') }}</button>
                </div>
            </div>
        </div>
    </div>

</div>
