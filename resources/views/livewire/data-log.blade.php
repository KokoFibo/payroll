<div>
    <h1>test</h1>
    <button class="btn btn-primary">test</button>
    <div>
        <table class='table'>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>description</th>
                    <th>Karyawan</th>
                    <th>Admin</th>

                    <th>Keterangan Perubahan</th>
                    <th>Tanggal Update</th>
                </tr>
            </thead>
            @foreach ($activity as $a)
                @if ($a->event == 'deleted')
                    <tr class='table-warning'>
                    @else
                    <tr>
                @endif
                <td>{{ $a->id }}</td>
                <td>{{ $a->event }}</td>
                <td>{{ getSubjectName($a->subject_id) }}</td>
                <td>{{ getCauserName($a->causer_id) }}</td>
                @if ($a->event == 'deleted')
                    <td></td>
                @else
                    <td>{{ $a->properties }}</td>
                @endif
                <td>{{ $a->updated_at }}</td>
                </tr>
                </td>
            @endforeach
        </table>
    </div>
</div>
