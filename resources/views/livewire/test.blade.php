<div>
    <h4>Data karyawan yg tidak terdapat di table USER</h4>
    <ul>
        @foreach ($missingKaryawanIds as $d)
            <li>{{ $d }}</li>
        @endforeach
    </ul>

</div>
