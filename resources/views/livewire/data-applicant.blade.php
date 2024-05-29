<div>

    <div class="p-3">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-row justify-content-between">
                    <div>
                        <h2>Data Applicant</h2>
                    </div>
                    @if ($show_data)
                        <div>
                            <button class="btn btn-dark" wire:click='kembali'>Kembali</button>
                        </div>
                    @endif
                </div>
            </div>
            @if ($show_table)
                <div class="card-body">
                    <div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>HP</th>
                                    <th>Gender</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Status Penerimaan</th>
                                    <th>Submitted</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $d)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $d->nama }}</td>
                                        <td>{{ $d->email }}</td>
                                        <td>{{ $d->hp }}</td>
                                        <td>{{ $d->gender }}</td>
                                        <td>{{ $d->tgl_lahir }}</td>
                                        @if ($d->status == 'Diterima')
                                            <td class="{{ $d->status == 'Diterima' ? 'badge-success' : '' }}">
                                                <span class="badge text-bg-success">{{ $d->status }}</span>
                                                </h6>
                                            </td>
                                        @elseif($d->status == 'Waiting List')
                                            <td class="{{ $d->status == 'Diterima' ? 'badge-success' : '' }}">
                                                <span class="badge text-bg-primary">{{ $d->status }}</span>
                                                </h6>
                                            </td>
                                        @else
                                            <td class="{{ $d->status == 'Diterima' ? 'badge-success' : '' }}">
                                                <span class="badge text-bg-secondary">{{ $d->status }}</span>
                                                </h6>
                                            </td>
                                        @endif
                                        <td>{{ $d->created_at }}</td>
                                        <td>
                                            <button
                                                class="btn
                                            btn-sm btn-warning"
                                                wire:click='show({{ $d->id }})'>Show</button>
                                            <button class="btn btn-sm btn-danger"
                                                wire:click='delete({{ $d->id }})'>Delete</button>
                                            <button class="btn btn-sm btn-success"
                                                wire:click='diterima({{ $d->id }})'>Diterima</button>
                                            <button class="btn btn-sm btn-primary"
                                                wire:click='waitingList({{ $d->id }})'>Waiting List</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $data->links() }}
                </div>
            @endif
            @if ($show_data)
                <div class="d-flex">
                    <ul class="list-group">
                        <li class="list-group-item">Nama</li>
                        <li class="list-group-item">Email</li>
                        <li class="list-group-item">HP</li>
                        <li class="list-group-item">Telepon</li>
                        <li class="list-group-item">Tempat/ Tanggal Lahir</li>
                        <li class="list-group-item">Gender</li>
                        <li class="list-group-item">Status Pernikahan</li>
                        <li class="list-group-item">Golongan Darah</li>
                        <li class="list-group-item">Agama</li>
                        <li class="list-group-item">Etnis</li>
                        <li class="list-group-item">Nama Kontak Darurat</li>
                        <li class="list-group-item">No Kontak Darurat</li>
                        <li class="list-group-item">Identitas/No</li>
                        <li class="list-group-item">Alamat Identitas</li>
                        <li class="list-group-item">Alamat Tinggal Sekarang</li>
                        <li class="list-group-item">Status</li>

                    </ul>
                    <ul class="list-group">
                        <li class="list-group-item">{{ $personal_data->nama }}</li>
                        <li class="list-group-item">{{ $personal_data->email }}</li>
                        <li class="list-group-item">{{ $personal_data->hp }}</li>
                        <li class="list-group-item">{{ $personal_data->telp }}</li>
                        <li class="list-group-item">{{ $personal_data->tempat_lahir }}/
                            {{ $personal_data->tgl_lahir }}</li>
                        <li class="list-group-item">{{ $personal_data->gender }}</li>
                        <li class="list-group-item">{{ $personal_data->status_pernikahan }}</li>
                        <li class="list-group-item">{{ $personal_data->golongan_darah }}</li>
                        <li class="list-group-item">{{ $personal_data->agama }}</li>
                        <li class="list-group-item">{{ $personal_data->etnis }}</li>
                        <li class="list-group-item">{{ $personal_data->nama_contact_darurat }}</li>
                        <li class="list-group-item">{{ $personal_data->contact_darurat_1 }} /
                            {{ $personal_data->contact_darurat_2 }}
                        </li>
                        <li class="list-group-item">{{ $personal_data->jenis_identitas }}:
                            {{ $personal_data->no_identitas }}</li>
                        <li class="list-group-item">{{ $personal_data->alamat_identitas }}</li>
                        <li class="list-group-item">{{ $personal_data->alamat_tinggal_sekarang }}</li>
                        <li class="list-group-item">{{ $personal_data->status }}</li>
                        @foreach ($personal_files as $f)
                            @if (strtolower(getFilenameExtension($f->originalName)) == 'pdf')
                                <li class="list-group-item"><button
                                        class="btn btn-success">{{ $f->originalName }}</button>
                                    <iframe src="{{ getUrl($f->filename) }}" width="100%" height="600px"></iframe>

                                </li>
                            @endif
                        @endforeach
                        @foreach ($personal_files as $key => $fn)
                            @if (strtolower(getFilenameExtension($fn->originalName)) != 'pdf')
                                <li class="list-group-item">
                                    <div class="flex flex-col">
                                        <div> {{ $fn->originalName }}</div>
                                        <img class="my-3" src="{{ getUrl($fn->filename) }}" alt="">
                                    </div>
                                </li>
                            @endif
                        @endforeach
                        <li class=" list-group-item" style="text-decoration: none">
                            <div class='w-1/5 text-center '>
                                <button class="btn btn-dark" wire:click='kembali'>Kembali</button>
                            </div>
                        </li>

                    </ul>
                </div>

            @endif
        </div>
    </div>
</div>
