<div>
    @section('title', 'Karyawan')

    <div class="col-4 p-4">

        <div class="input-group">
            <button class="btn btn-primary" type="button"><i class="fa-solid fa-magnifying-glass"></i></button>
            <input type="search" wire:model.live="search" class="form-control" placeholder="Search ...">
        </div>

    </div>




    <div class="px-4">


        <div class="card">
            <div class="card-header">
                <h3>Data Karyawan
                    <a href="/karyawancreate"><button class="btn btn-primary float-end"><i class="fa-solid fa-plus"></i>
                            Karyawan baru</button></a>
                </h3>
            </div>
            <div class="card-body">
                <table class="table table-hover mb-2">
                    <thead>
                        <tr>
                            <th wire:click="sortColumnName('nama')">Nama <i class="fa-solid fa-sort"></i></th>
                            <th wire:click="sortColumnName('id_karyawan')">Id Karyawan <i class="fa-solid fa-sort"></i>
                            </th>
                            <th wire:click="sortColumnName('branch')">Branch <i class="fa-solid fa-sort"></i></th>
                            <th wire:click="sortColumnName('departemen')">Departemen <i class="fa-solid fa-sort"></i>
                            </th>
                            <th wire:click="sortColumnName('jabatan')">Jabatan <i class="fa-solid fa-sort"></i></th>
                            <th wire:click="sortColumnName('level_jabatan')">Level Jabatan <i
                                    class="fa-solid fa-sort"></i>
                            </th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datas as $data)
                            <tr>
                                <td>{{ $data->nama }}</td>
                                <td>{{ $data->id_karyawan }}</td>
                                <td>{{ $data->branch }}</td>
                                <td>{{ $data->departemen }}</td>
                                <td>{{ $data->jabatan }}</td>
                                <td>{{ $data->level_jabatan }}</td>
                                <td class="btn-group gap-2"
                                    @dblclick="window.location.href = '/karyawanupdate/'+{{ $data->id }}">
                                    <a href="/karyawanupdate/{{ $data->id }}"><button
                                            class="btn btn-warning btn-sm"><i
                                                class="fa-regular fa-pen-to-square"></i></button></a>
                                    {{-- <a href="/karyawandelete/{{ $data->id }}" onclick="confirmation(event)"
                                        class="btn btn-danger btn-sm"><i class="fa-solid fa-trash-can"></i></a> --}}

                                    <form method="post" action="{{ route('karyawan.destroy', ['id' => $data->id]) }}">
                                        @csrf
                                        @method('delete')



                                        <a href="{{ route('karyawan.destroy', ['id' => $data->id]) }}"><button
                                                type="submit" class="btn btn-danger btn-sm confirm-delete"><i
                                                    class="fa-solid fa-trash-can"></i></button></a>
                                    </form>


                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $datas->links() }}

            </div>
        </div>
    </div>
</div>
