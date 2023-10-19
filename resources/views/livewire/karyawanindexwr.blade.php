<div>
    <style>
        td,
        th {
            white-space: nowrap;
        }
    </style>
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
                <div class="table-responsive-md">
                    <table class="table table-xl   table-hover mb-2">
                        <thead>
                            <tr>
                                <th wire:click="sortColumnName('nama')">Nama <i class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('id_karyawan')">Id Karyawan <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('branch')">Branch <i class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('departemen')">Departemen <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('jabatan')">Jabatan <i class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('level_jabatan')">Level Jabatan <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th></th>
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
                                    <td class="btn-group mx-auto gap-2">
                                        <a href="/karyawanupdate/{{ $data->id }}"><button
                                                class="btn btn-warning btn-sm"><i
                                                    class="fa-regular fa-pen-to-square"></i></button></a>

                                        @if (Auth::user()->role == 3 || Auth::user()->role == 4)
                                            <button wire:click="confirmDelete({{ $data->id }})"
                                                class="btn btn-danger btn-sm"><i
                                                    class="fa-solid fa-trash-can"></i></button>
                                            {{-- <form method="post"
                                                action="{{ route('karyawan.destroy', ['id' => $data->id]) }}">
                                                @csrf
                                                @method('delete')



                                                <a href="{{ route('karyawan.destroy', ['id' => $data->id]) }}"><button
                                                        type="submit" class="btn btn-danger btn-sm confirm-delete"><i
                                                            class="fa-solid fa-trash-can"></i></button></a>
                                            </form> --}}
                                        @endif



                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $datas->links() }}

            </div>
        </div>
    </div>
    <script>
        window.addEventListener("swal:confirm", (event) => {
            Swal.fire({
                title: "Apakah yakin mau di delete",
                text: "Data yang sudah di delete tidak dapat dikembalikan",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Delete",
            }).then((willDelete) => {
                if (willDelete) {
                    @this.dispatch("delete", event.detail.id);
                }
            });
        });
    </script>
</div>
