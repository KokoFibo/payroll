@extends('layouts.app2')

@section('title', 'Import - Presensi')

@section('content')

    <h4 class="px-4 mt-4">Import Presensi</h4>

    <div class="row mb-5 p-4">
        <div class="col-md-12 col-lg-12 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5>Import file presensi harian</h5>
                    <form action="{{ route('presensi.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <label for="formFile" class="form-label mt-2 mb-3">Upload data</label>
                            <input class="form-control" type="file" name="file" id="formFile" accept="xlsx"
                                required>
                        </div>
                        {{-- <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <select name="location" class="form-control" required>
                                <option value=""></option>
                                <option value="office 1">Office 1</option>
                                <option value="office 2">Office 2</option>
                                <option value="office 3">Office 3</option>
                                <option value="factory 1">Factory 1</option>
                                <option value="factory 2">Factory 2</option>
                                <option value="factory 3">Factory 3</option>
                            </select>
                        </div> --}}
                        <button type="submit" class="btn btn-outline-primary" id="import-btn">Import</button>
                        <a href="/presensi" wire:navigate><button type="button" class="btn btn-primary" ">Exit</button></a>

                                                                                    <div class="spinner-grow text-success" role="status" id="loading">
                                                                                        <span class="visually-hidden">Loading...</span>
                                                                                    </div>

                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>


@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('form').submit(function() {
            $('#loading').show(); // Display the loading element
            $('#import-btn').hide();
        });

        $('#loading').hide();
        $('#import-btn').show();
    });
</script>
