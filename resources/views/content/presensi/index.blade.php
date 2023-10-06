@extends('layouts.app2')

@section('title', 'Data - Presensi')

@section('content')
    <div class="row px-4 mt-4">

        <h4 class="col-6">
            Presensi Karyawan
        </h4>
        <div class="col-6 text-end">
            <a href="/presensiupload"><button class="btn btn-success mx-4">Import Presensi</button></a>
        </div>
    </div>

    <div class="row p-4">
        <div class="col-xl">
            <div class="card">
                <div class="card-header ">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5>Search</h5>
                        <div>
                            <span class="p-2 bg-warning bg-gradient text-dark ">No Scan =
                                {{ $noScanCount }}</span>
                            <span class="p-2 bg-danger bg-gradient text-white">Late = {{ $lateCount }}</span> dari
                            {{ $totalDataPerHari }} data
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('presensi.index') }}">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label" for="name-input">Nama / User Id</label>
                                    <input type="text" class="form-control" name="search" id="name-input"
                                        value="{{ $search }}" placeholder="John Doe" />
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label" for="date-input">Tanggal (mm/dd/yyyy)</label>
                                    <input type="date" value="{{ $date }}" class="form-control" name="date"
                                        id="date-input" placeholder="John Doe" />
                                </div>

                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label" for="short_by">Sort by</label>
                                    <select name="short" id="short_by" class="form-control">
                                        <option value="name" {{ $short == 'name' ? 'selected=true' : '' }}>Name
                                        </option>
                                        <option value="user_id" {{ $short == 'user_id' ? 'selected=true' : '' }}>ID</option>
                                        <option value="late" {{ $short == 'late' ? 'selected=true' : '' }}>late
                                        </option>
                                        <option value="no_scan" {{ $short == 'no_scan' ? 'selected=true' : '' }}>No scan
                                        </option>
                                        <option value="shift" {{ $short == 'shift' ? 'selected=true' : '' }}>shift
                                        </option>
                                    </select>

                                </div>

                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label" for="order_by" value="{{ $order }}">Order</label>
                                    <select name="order" id="order_by" class="form-control">
                                        <option value="asc" @if ($order == 'asc') selected @endif>Ascending
                                        </option>
                                        <option value="desc" @if ($order == 'desc') selected @endif>Descending
                                        </option>
                                    </select>

                                </div>

                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Cari</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>
    <div class="row px-4">
        <div class="col-md-12 col-lg-12 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-header">Data presensi tanggal {{ format_tgl($date) }}</h5>
                    <div class="table-responsive text-nowrap pb-4">
                        <table class="table" id="presensi">
                            <thead>
                                <tr class="text-nowrap">
                                    <th>#</th>
                                    <th>Karyawan ID</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Working date</th>
                                    <th>First in</th>
                                    <th>First out</th>
                                    <th>Second in</th>
                                    <th>Second out</th>
                                    <th>Overtime in</th>
                                    <th>Overtime out</th>
                                    <th>First in late</th>
                                    <th>Second in late</th>
                                    <th>Overtime in late</th>
                                    <th>Late</th>
                                    <th>No scan</th>
                                    <th>Shift</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($data as $key => $item)
                                    @php
                                        $no_scan = '';
                                        $first_in_late = '';
                                        $second_in_late = '';
                                        $overtime_in_late = '';
                                        $overtime_in = '';
                                        $overtime_out = '';
                                        if ($item->shift == 'Shift pagi') {
                                            $first_in_late = floor((strtotime($item->first_in) - strtotime('8:03')) / 60);
                                            $overtime_in_late = floor((strtotime($item->overtime_in) - strtotime('18:33')) / 60);
                                        } elseif ($item->shift == 'Shift malam') {
                                            $first_in_late = floor((strtotime($item->first_in) - strtotime('20:33')) / 60);
                                        }
                                        
                                        if (strtotime($item->first_out) < strtotime('12:00')) {
                                            $second_in_late = floor((strtotime($item->second_in) - strtotime('12:30')) / 60);
                                        } elseif (strtotime($item->first_out) >= strtotime('12:00')) {
                                            $second_in_late = floor((strtotime($item->second_in) - strtotime('13:00')) / 60);
                                        } elseif (strtotime($item->first_out) > strtotime('00:00')) {
                                            $second_in_late = floor((strtotime($item->second_in) - strtotime('1:00')) / 60);
                                        }
                                        
                                        if ($first_in_late < 0) {
                                            $first_in_late = '';
                                        }
                                        if ($second_in_late < 0) {
                                            $second_in_late = '';
                                        }
                                        if ($overtime_in_late < 0) {
                                            $overtime_in_late = '';
                                        }
                                        if ($item->first_in == '') {
                                            $no_scan = 'No First In, ' . $no_scan;
                                        }
                                        
                                        if ($item->second_out == '') {
                                            $no_scan = 'No Second Out, ' . $no_scan;
                                        }
                                        
                                        if ($item->first_out != '' || $item->second_in != '') {
                                            if ($item->first_out == '') {
                                                $no_scan = 'No First Out, ' . $no_scan;
                                            }
                                            if ($item->second_in == '' || $item->first_out == $item->second_in) {
                                                $no_scan = 'No Second In, ' . $no_scan;
                                            }
                                        }
                                        
                                        if ($item->overtime_out != '' && $item->overtime_in == '' && $item->shift == 'Shift pagi') {
                                            $no_scan = 'No overtime In, ' . $no_scan;
                                        }
                                        
                                        if ($item->overtime_out == '' && $item->overtime_in != '' && $item->shift == 'Shift pagi') {
                                            $no_scan = 'No overtime Out, ' . $no_scan;
                                        }
                                        
                                        if ($item->shift == 'Shift pagi') {
                                            $overtime_in = $item->overtime_in;
                                            $overtime_out = $item->overtime_out;
                                        }
                                        
                                    @endphp
                                    <tr
                                        class="@if ($item->no_scan == 1) table-warning table-gradient text-dark @elseif ($item->late == 1) table-danger table-gradient text-white @endif">
                                        <td scope="row">{{ $loop->iteration }}</td>
                                        <td>{{ $item->user_id }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->department }}</td>
                                        <td>{{ format_tgl($item->date) }}</td>
                                        <td>{{ $item->first_in }}</td>
                                        <td>
                                            {{ $item->first_out }}
                                        </td>
                                        <td>
                                            @if ($item->first_out != $item->second_in)
                                                {{ $item->second_in }}
                                            @endif
                                        </td>
                                        <td>{{ $item->second_out }}</td>
                                        <td>
                                            {{ $overtime_in }}
                                        </td>
                                        <td>
                                            {{ $overtime_out }}
                                        </td>
                                        <td>
                                            {{ $first_in_late }}

                                        </td>
                                        <td>
                                            {{ $second_in_late }}

                                        </td>
                                        <td>{{ $overtime_in_late }}</td>
                                        <td>{{ $item->late }}</td>
                                        <td>
                                            {{ $no_scan }}
                                        </td>
                                        <td>{{ $item->shift }}</td>
                                        <td>

                                            <div class="btn-group" role="group" aria-label="Basic example">



                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#edit{{ $loop->iteration }}">
                                                    Edit
                                                </button>


                                                <form
                                                    action="{{ route('presensi.deletedata', ['user_id' => $item->user_id, 'date' => $item->date]) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>



                                            <div class="modal fade" id="edit{{ $loop->iteration }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Edit presensi
                                                                {{ $item->name }} tgl {{ $item->date }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <form action="{{ url('presensi-update/' . $item->user_id) }}"
                                                                method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="hidden" name="date"
                                                                    value="{{ $item->date }}">

                                                                <div class="mb-3">
                                                                    <label for="first_in" class="form-label">First
                                                                        In</label>
                                                                    <input class="form-control" id="first_in"
                                                                        type="time" name="first_in"
                                                                        value="{{ $item->first_in }}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="first_out" class="form-label">First
                                                                        Out</label>
                                                                    <input class="form-control" id="first_out"
                                                                        type="time" name="first_out"
                                                                        value="{{ $item->first_out }}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="second_in" class="form-label">Second
                                                                        In</label>
                                                                    <input class="form-control" id="second_in"
                                                                        type="time" name="second_in"
                                                                        value="{{ $item->second_in }}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="second_out" class="form-label">Second
                                                                        Out</label>
                                                                    <input class="form-control" id="second_out"
                                                                        type="time" name="second_out"
                                                                        value="{{ $item->second_out }}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="overtime_in" class="form-label">Overtime
                                                                        In</label>
                                                                    <input class="form-control" id="overtime_in"
                                                                        type="time" name="overtime_in"
                                                                        value="{{ $overtime_in }}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="overtime_out" class="form-label">Overtime
                                                                        Out</label>
                                                                    <input class="form-control" id="overtime_out"
                                                                        type="time" name="overtime_out"
                                                                        value="{{ $overtime_out }}">
                                                                </div>



                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Save
                                                                changes</button>
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $data->appends(['search' => request('search'), 'date' => request('date'), 'order' => request('order'), 'short' => request('short')])->links() }}
                    </div>

                </div>
            </div>
        </div>

    </div>


@endsection
