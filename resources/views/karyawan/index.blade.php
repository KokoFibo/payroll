@extends('layouts.app2')

@section('title', 'Karyawan')

@section('content')
    <div>
        {{-- <style>
        #all {
            font-family: 'inter';
        }
    </style> --}}
        <div id="all" class="card p-2">
            <div class="card-head">
                <h3 class="mx-4">Data Karyawan
                    <a href="/karyawancreate" class="float-end"><button class="btn btn-primary">Create New</button></a>
                </h3>

            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>ID Karyawan</th>
                            <th>Branch</th>
                            <th>Department</th>
                            <th>Jabatan</th>
                            <th>Level Jabatan</th>
                            <th></th>

                        </tr>
                    </thead>
                    <tbody x-data="{}">
                        @foreach ($datas as $data)
                            <tr>
                                <td @dblclick="window.location.href = '/karyawanupdate/'+{{ $data->id }}">
                                    {{ $data->id }}</td>
                                <td @dblclick="window.location.href = '/karyawanupdate/'+{{ $data->id }}">
                                    {{ $data->nama }}</td>
                                <td @dblclick="window.location.href = '/karyawanupdate/'+{{ $data->id }}">
                                    {{ $data->id_karyawan }}</td>
                                <td @dblclick="window.location.href = '/karyawanupdate/'+{{ $data->id }}">
                                    {{ $data->branch }}</td>
                                <td @dblclick="window.location.href = '/karyawanupdate/'+{{ $data->id }}">
                                    {{ $data->departemen }}</td>
                                <td @dblclick="window.location.href = '/karyawanupdate/'+{{ $data->id }}">
                                    {{ $data->jabatan }}</td>
                                <td @dblclick="window.location.href = '/karyawanupdate/'+{{ $data->id }}">
                                    {{ $data->level_jabatan }}</td>
                                <td @dblclick="window.location.href = '/karyawanupdate/'+{{ $data->id }}">
                                    <a href="/karyawanupdate/{{ $data->id }}"><button class="btn btn-warning btn-sm"><i
                                                class="fa-regular fa-pen-to-square"></i></button></a>
                                    <a href="#"><button class="btn btn-danger btn-sm"><i
                                                class="fa-solid fa-trash-can"></i></button></a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <span>{{ $datas->links() }}</span>
            </div>
        </div>
    </div>
@endsection
