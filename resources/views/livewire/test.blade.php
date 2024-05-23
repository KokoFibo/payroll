<div>
    <livewire:placementreport />
    <br><br><br>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ __('ID') }} <i class="fa-solid fa-sort"></i></th>
                        <th>{{ __('Nama') }} <i class="fa-solid fa-sort"></i></th>
                        <th>
                            {{ __('Date') }} <i class="fa-solid fa-sort"></i></th>
                        <th>{{ __('first_in') }} <i class="fa-solid fa-sort"></i></th>
                        <th>{{ __('first_out') }} <i class="fa-solid fa-sort"></i></th>
                        <th>{{ __('second_in') }} <i class="fa-solid fa-sort"></i></th>
                        <th>{{ __('second_out') }} <i class="fa-solid fa-sort"></i></th>
                        <th>{{ __('overtime_in') }} <i class="fa-solid fa-sort"></i></th>
                        <th>{{ __('overtime_out') }} <i class="fa-solid fa-sort"></i></th>
                        <th>{{ __('late') }} <i class="fa-solid fa-sort"></i></th>
                        <th>{{ __('no scan') }} <i class="fa-solid fa-sort"></i></th>
                        <th>{{ __('no scan history') }} <i class="fa-solid fa-sort"></i></th>
                        <th>{{ __('shift') }} <i class="fa-solid fa-sort"></i></th>


                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if ($datas->isNotEmpty())
                        @foreach ($datas as $p)
                            <tr>
                                <td>{{ $p->karyawan_id }}</td>
                                <td>{{ $p->nama }}</td>
                                <td>{{ $p->date }}</td>
                                <td>{{ $p->first_in }}</td>
                                <td>{{ $p->first_out }}</td>
                                <td>{{ $p->second_in }}</td>
                                <td>{{ $p->second_out }}</td>
                                <td>{{ $p->overtime_in }}</td>
                                <td>{{ $p->overtime_out }}</td>
                                <td>{{ $p->late }}</td>
                                <td>{{ $p->no_scan }}</td>
                                <td>{{ $p->no_scan_history }}</td>
                                <td>{{ $p->shift }}</td>
                            </tr>
                            {{-- @endif --}}
                        @endforeach
                    @else
                        <h4>{{ __('No Data Found') }}</h4>
                    @endif
                </tbody>
            </table>
            {{ $datas->links() }}
        </div>
    </div>
</div>
