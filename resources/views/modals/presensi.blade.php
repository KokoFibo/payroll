 <div wire:ignore.self class="modal fade" id="update-form-modal" data-bs-backdrop="static" data-bs-keyboard="false"
     tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <div class="modal-header">
                 <h1 class="modal-title fs-5" id="staticBackdropLabel">Data Presensi Karyawan</h1>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <h4>November 2023</h4>
                 <p>User ID : {{ $user_id }}</p>
                 <p>Nama : {{ $name }}</p>

                 <table class="table table-hover">
                     <thead>
                         <tr>
                             <th>Tanggal</th>
                             <th>Jam Kerja</th>
                             <th>Jam Lembur</th>
                             <th>Terlambat</th>
                             <th>Shift Malam</th>
                         </tr>
                     </thead>
                     <tbody>


                         @foreach ($this->dataArr as $d)
                             <tr>
                                 <td class="text-center">{{ $d['tgl'] }}</td>
                                 <td class="text-center">{{ $d['jam_kerja'] }}</td>
                                 <td class="text-center">{{ $d['jam_lembur'] }}</td>
                                 <td class="text-center">{{ $d['terlambat'] }}</td>
                                 <td class="text-center">{{ $d['tambahan_shift_malam'] }}</td>
                             </tr>
                         @endforeach
                         {{-- @foreach ($dataArr as $d)
                                <tr>
                                    <td class="text-center">{{ $d->tgl }}</td>
                                    <td class="text-center">{{ $d->jam_kerja }}</td>
                                    <td class="text-center">{{ $d->jam_lembur }}</td>
                                    <td class="text-center">{{ $d->terlambat }}</td>
                                </tr>
                            @endforeach --}}


                         <tr>
                             <th class="text-center">{{ $total_hari_kerja }}</th>
                             <th class="text-center">{{ $total_jam_kerja }}</th>
                             <th class="text-center">{{ $total_jam_lembur }}</th>
                             <th class="text-center">{{ $total_keterlambatan }}</th>
                             <th class="text-center">{{ $total_tambahan_shift_malam }}</th>
                         </tr>
                     </tbody>
                 </table>

             </div>
             <div class="modal-footer">


                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>


             </div>
         </div>
     </div>
 </div>
