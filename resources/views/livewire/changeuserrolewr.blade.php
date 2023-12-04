<div>
    @section('title', 'Change Role')
    <div>
        <div class="container">
            <div class="mx-auto  pt-4">
                <button class="mx-auto col-12 btn btn-info btn-large">
                    <h3 class="px-3">{{ __('Rubah Role Karyawan') }}</h3>
                </button>
                <div class="card mt-5  mx-auto">
                    <div class="card-header">
                        <h5>{{ __('Rubah Role Karyawan') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="input-group col-xl-6 col-12 ">
                            <button class="btn btn-primary" type="button"><i
                                    class="fa-solid fa-magnifying-glass"></i></button>
                            <input type="search" wire:model.live="search" class="form-control"
                                placeholder="{{ __('Masukkan Nama/ID Karyawan') }}">
                        </div>
                        @if ($data)
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Nama') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Handphone') }}</th>
                                        <th>{{ __('Tanggal Lahir') }}</th>
                                        <th>{{ __('Company') }}</th>
                                        <th>{{ __('Departemen') }}</th>
                                        <th>{{ __('Jabatan') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $data->id_karyawan }}</td>
                                        <td>{{ $data->nama }}</td>
                                        <td>{{ $data->email }}</td>
                                        <td>{{ $data->hp }}</td>
                                        <td>{{ format_tgl($data->tanggal_lahir) }}</td>
                                        <td>{{ $data->branch }}</td>
                                        <td>{{ $data->departemen }}</td>
                                        <td>{{ $data->jabatan }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input wire:model="role" class="form-check-input" type="radio"
                                                value="1">
                                            <label class="form-check-label">
                                                <h5>{{ __('User') }}</h5>
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input wire:model="role" class="form-check-input" type="radio"
                                                value="2">
                                            <label class="form-check-label">
                                                <h5>{{ __('Admin') }}</h5>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input wire:model="role" class="form-check-input" type="radio"
                                                value="3">
                                            <label class="form-check-label">
                                                <h5>{{ __('Senior Admin') }}</h5>
                                            </label>
                                        </div>
                                        @if (auth()->user()->role > 3)
                                            <div class="form-check">
                                                <input wire:model="role" class="form-check-input" type="radio"
                                                    value="4">
                                                <label class="form-check-label">
                                                    <h5>{{ __('Super Admin') }}</h5>
                                                </label>
                                            </div>
                                        @endif

                                    </div>

                                </div>
                            </div>

                            <div class="col-3">
                                <button wire:click="save" class="btn btn-primary">{{ __('Save') }}</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
