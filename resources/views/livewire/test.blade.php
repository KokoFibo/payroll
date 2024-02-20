<div>

    <table class="table mt-5">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>first in</th>
                <th>first out</th>
                <th>second in</th>
                <th>second out</th>
                <th>overtime in</th>
                <th>overtime out</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            {{-- @foreach ($data as $d)
                <tr>
                    <td>{{ $d->id }}</td>
                    <td></td>
                    <td>{{ $d->first_in }}</td>
                    <td>{{ $d->first_out }}</td>
                    <td>{{ $d->second_in }}</td>
                    <td>{{ $d->second_out }}</td>
                    <td>{{ $d->overtime_in }}</td>
                    <td>{{ $d->overtime_out }}</td>
                    <td>{{ is_halfday($d->first_in, $d->first_out, $d->second_in, $d->second_out) }}</td>
                </tr>
            @endforeach --}}
        </tbody>
    </table>
    {{ $data->links() }}


</div>
