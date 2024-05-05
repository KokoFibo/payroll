<div>

    <div class="card-body">
        <div class="table-responsive">

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ __('ID') }} <i class="fa-solid fa-sort"></i></th>
                        <th>
                            {{ __('Date') }} <i class="fa-solid fa-sort"></i></th>
                        <th>{{ __('Nama') }} <i class="fa-solid fa-sort"></i></th>
                        <th>{{ __('Placement') }} <i class="fa-solid fa-sort"></i>
                        <th>{{ __('Metode Penggajian') }} <i class="fa-solid fa-sort"></i>
                        <th>{{ __('Gaji Lembur') }} <i class="fa-solid fa-sort"></i>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if ($data->isNotEmpty())

                        @foreach ($data as $p)
                            <tr>
                                <td>{{ $p->id_karyawan }}</td>
                                <td>{{ $p->date }}</td>
                                <td>{{ $p->nama }}</td>
                                <td>{{ $p->placement }}</td>
                                <td>{{ $p->metode_penggajian }}</td>
                                <td>{{ $p->gaji_overtime }}</td>
                            </tr>
                        @endforeach
                    @else
                        <h4>{{ __('No Data Found') }}</h4>
                    @endif
                </tbody>
            </table>
            {{ $data->links() }}
        </div>
    </div>
</div>
