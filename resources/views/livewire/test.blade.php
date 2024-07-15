<div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Tanggal Telat</th>
                        <th>Department</th>
                        <th>First In</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $p)
                        @if (is_first_in_late($p->first_in))
                            <tr>
                                <td>{{ $p->user_id }}</td>
                                <td>{{ $p->karyawan->nama }}</td>
                                <td>{{ format_tgl($p->date) }}</td>
                                <td>{{ nama_department($p->karyawan->department_id) }}</td>
                                <td>{{ $p->first_in }}</td>
                            </tr>
                        @endif

                        {{-- @endif --}}
                    @endforeach

                </tbody>
            </table>
            {{-- {{ $datas->links() }} --}}
        </div>
    </div>
</div>
