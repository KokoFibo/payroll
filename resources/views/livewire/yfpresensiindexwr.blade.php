<div>

    <div class="col-4 p-4">

        <div class="input-group">
            <button class="btn btn-primary" type="button"><i class="fa-solid fa-magnifying-glass"></i></button>
            <input type="search" wire:model.live="search" class="form-control" placeholder="Search ...">
        </div>
        Total No scan : {{ $totalNoScan }}
        Total Late : {{ $totalLate }}

    </div>
    <div class="px-4">
        <div class="card">
            <div class="card-header">
                <h3>Data Presensi
                    <a href="/yfupload"><button class="btn btn-primary float-end">Upload YF Presensi</button></a>
                </h3>
            </div>
            <div class="card-body">
                <table class="table table-hover mb-4">
                    <thead>
                        <tr>
                            <td>Action</td>
                            <td wire:click="sortColumnName('user_id')">Karyawan Id <i class="fa-solid fa-sort"></i></td>
                            <td wire:click="sortColumnName('name')">Nama <i class="fa-solid fa-sort"></i></td>
                            <td wire:click="sortColumnName('department')">Department <i class="fa-solid fa-sort"></i>
                            </td>
                            <td wire:click="sortColumnName('date')">Working Date <i class="fa-solid fa-sort"></i></td>
                            <td wire:click="sortColumnName('first_in')">First in <i class="fa-solid fa-sort"></i></td>
                            <td wire:click="sortColumnName('first_out')">First out <i class="fa-solid fa-sort"></i></td>
                            <td wire:click="sortColumnName('second_in')">Second in <i class="fa-solid fa-sort"></i></td>
                            <td wire:click="sortColumnName('second_out')">Second out <i class="fa-solid fa-sort"></i>
                            </td>
                            <td wire:click="sortColumnName('overtime_in')">Overtime in <i class="fa-solid fa-sort"></i>
                            </td>
                            <td wire:click="sortColumnName('overtime_out')">Overtime out <i
                                    class="fa-solid fa-sort"></i>
                            </td>
                            <td wire:click="sortColumnName('late')">Late <i class="fa-solid fa-sort"></i></td>
                            <td wire:click="sortColumnName('no_scan')">No scan <i class="fa-solid fa-sort"></i></td>
                            <td wire:click="sortColumnName('shift')">Shift <i class="fa-solid fa-sort"></i></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datas as $data)
                            @php
                                $no_scan = '';
                                $first_in_late = '';
                                $first_out_late = '';
                                $second_in_late = '';
                                $second_out_late = '';
                                $overtime_in_late = '';
                                $overtime_in = '';
                                $overtime_out = '';

                                if ($data->shift == 'Shift pagi') {
                                    $second_out_late = floor((strtotime($data->second_out) - strtotime('17:00')) / 60);
                                } elseif ($data->shift == 'Shift malam') {
                                    if (is_saturday($data->date)) {
                                        $second_out_late = floor((strtotime($data->second_out) - strtotime('00:00')) / 60);
                                    } else {
                                        $second_out_late = floor((strtotime($data->second_out) - strtotime('05:00')) / 60);
                                    }
                                }

                                if ($data->shift == 'Shift pagi') {
                                    $first_in_late = floor((strtotime($data->first_in) - strtotime('8:03')) / 60);
                                    $overtime_in_late = floor((strtotime($data->overtime_in) - strtotime('18:33')) / 60);
                                    $first_out_late = floor((strtotime($data->first_out) - strtotime('11:30')) / 60);
                                } elseif ($data->shift == 'Shift malam') {
                                    $first_in_late = floor((strtotime($data->first_in) - strtotime('20:03')) / 60);
                                    if ($data->first_out < '24:00' && $data->first_out > '20:04') {
                                        $first_out_late = floor((strtotime($data->first_out) - strtotime('24:00')) / 60);
                                    } else {
                                        $first_out_late = floor((strtotime($data->first_out) - strtotime('00:00')) / 60);
                                    }

                                    if ($data[0]->first_out < '24:00' && $data[0]->first_out > '20:04' && $data[0]->first_out != '') {
                                        $late = 1;
                                    }
                                }

                                if (strtotime($data->first_out) < strtotime('12:00')) {
                                    $second_in_late = floor((strtotime($data->second_in) - strtotime('12:30')) / 60);
                                } elseif (strtotime($data->first_out) >= strtotime('12:00')) {
                                    $second_in_late = floor((strtotime($data->second_in) - strtotime('13:00')) / 60);
                                } elseif (strtotime($data->first_out) > strtotime('00:00')) {
                                    $second_in_late = floor((strtotime($data->second_in) - strtotime('1:00')) / 60);
                                }

                                if ($second_out_late >= 0) {
                                    $second_out_late = '';
                                }
                                if ($first_in_late <= 0) {
                                    $first_in_late = '';
                                }
                                if ($second_in_late <= 0) {
                                    $second_in_late = '';
                                }
                                if ($overtime_in_late <= 0) {
                                    $overtime_in_late = '';
                                }
                                if ($data->first_in == '') {
                                    $no_scan = 'No First In, ' . $no_scan;
                                }

                                if ($data->second_out == '') {
                                    $no_scan = 'No Second Out, ' . $no_scan;
                                }

                                if ($data->first_out != '' || $data->second_in != '') {
                                    if ($data->first_out == '') {
                                        $no_scan = 'No First Out, ' . $no_scan;
                                    }
                                    if ($data->second_in == '' || $data->first_out == $data->second_in) {
                                        $no_scan = 'No Second In, ' . $no_scan;
                                    }
                                }

                                if ($data->overtime_out != '' && $data->overtime_in == '' && $data->shift == 'Shift pagi') {
                                    $no_scan = 'No overtime In, ' . $no_scan;
                                }

                                if ($data->overtime_out == '' && $data->overtime_in != '' && $data->shift == 'Shift pagi') {
                                    $no_scan = 'No overtime Out, ' . $no_scan;
                                }

                                if ($data->shift == 'Shift pagi') {
                                    $overtime_in = $data->overtime_in;
                                    $overtime_out = $data->overtime_out;
                                }

                            @endphp


                            <tr class="{{ $data->no_scan ? 'table-warning' : '' }}">
                                <td>edit/del</td>
                                <td>{{ $data->user_id }}</td>
                                <td>{{ $data->name }}</td>
                                <td>{{ $data->department }}</td>
                                <td>{{ format_tgl($data->date) }}</td>
                                <td class="{{ $first_in_late > 0 ? 'text-danger' : '' }}">
                                    {{ format_jam($data->first_in) }} </td>
                                <td class="{{ $first_out_late > 0 ? 'text-danger' : '' }}">
                                    {{ format_jam($data->first_out) }}</td>
                                <td class="{{ $second_in_late > 0 ? 'text-danger' : '' }}">
                                    {{ format_jam($data->second_in) }}</td>
                                <td class="{{ $second_out_late > 0 ? 'text-danger' : '' }}">
                                    {{ format_jam($data->second_out) }}</td>
                                <td class="{{ $overtime_in_late > 0 ? 'text-danger' : '' }}">
                                    {{ format_jam($data->overtime_in) }}</td>
                                <td>
                                    {{ format_jam($data->overtime_out) }}</td>
                                <td>{{ $data->late }}</td>
                                <td>{{ $data->no_scan }}</td>
                                <td>{{ $data->shift }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $datas->links() }}
            </div>
        </div>
    </div>

</div>
