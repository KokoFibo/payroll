@extends('layouts.app4')

@section('title', 'Dashboard')

@section('content')
    <div class="pt-5">
    </div>
    {{-- Jumlah Karyawan --}}


    {{-- Dashboard device = {{ isDesktop() }} --}}
    <div id="root">
        <div class="container pt-5">
            <div class="row align-items-stretch">
                <div class="c-dashboardInfo col-lg-3 col-md-6">
                    <div class="wrap">
                        <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">
                            {{ __('Jumlah Karyawan') }}
                        </h4><span class="hind-font caption-12 c-dashboardInfo__count">{{ $jumlah_total_karyawan }}</span>
                    </div>
                </div>
                <div class="c-dashboardInfo col-lg-3 col-md-6">
                    <div class="wrap">
                        <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">
                            {{ __('Karyawan Pria') }}</h4>
                        <span class="hind-font caption-12 c-dashboardInfo__count">{{ $jumlah_karyawan_pria }}</span>
                        {{-- <span
                            class="hind-font caption-12 c-dashboardInfo__subInfo">Last month: €30</span> --}}
                    </div>
                </div>
                <div class="c-dashboardInfo col-lg-3 col-md-6">
                    <div class="wrap">
                        <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title text-center">
                            {{ __('Karyawan Wanita') }}</h4><span
                            class="hind-font caption-12 c-dashboardInfo__count">{{ $jumlah_karyawan_wanita }}</span>
                    </div>
                </div>
                <div class="c-dashboardInfo col-lg-3 col-md-6">
                    <div class="wrap">
                        <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">
                            {{ __('Reserved') }}
                        </h4><span class="hind-font caption-12 c-dashboardInfo__count">100%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Department --}}
    <div class="flex justify-evenly mt-3">
        {{-- Jumlah karyawan --}}
        <div>
            <div class="h-3 rounded-t-lg bg-blue-500">
            </div>
            <div class="bg-blue-200 h-96 rounded-b-lg w-96 shadow p-3 ">
                <p class="text-center text-lg mb-3">Jumlah Karyawan</p>
                <div class="flex gap-3 justify-evenly">
                    <div class="flex flex-column gap-2">
                        <h2 class="text-center   text-gray-600">Pabrik 1</h2>
                        <h2 class="text-center   text-gray-600">Pabrik 2</h2>
                        <h2 class="text-center   text-gray-600">Kantor</h2>
                        <h2 class="text-center   text-gray-600">ASB</h2>
                        <h2 class="text-center   text-gray-600">DPA</h2>
                        <h2 class="text-center   text-gray-600">YCME</h2>
                        <h2 class="text-center   text-gray-600">YEV</h2>
                        <h2 class="text-center   text-gray-600">YIG</h2>
                        <h2 class="text-center   text-gray-600">YSM</h2>
                        <h2 class="text-center font-semibold  text-gray-600">Total</h2>
                    </div>
                    <div class="flex flex-column gap-2">
                        <h2 class="text-center  font-semibold text-gray-600">{{ $jumlah_Pabrik_1 }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $jumlah_Pabrik_2 }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $jumlah_Kantor }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $jumlah_ASB }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $jumlah_DPA }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $jumlah_YCME }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $jumlah_YEV }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $jumlah_YIG }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $jumlah_YSM }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $jumlah_all }}</h2>
                    </div>
                </div>
            </div>
        </div>
        {{-- Departement --}}
        <div>
            <div class="h-3 rounded-t-lg bg-green-500">
            </div>
            <div class="bg-green-200 w-96  rounded-b-lg shadow p-3  ">
                <p class="text-center text-lg mb-3 ">Department</p>
                <div class="flex gap-3 justify-evenly">
                    <div class="flex flex-column gap-2">
                        <h2 class="text-center   text-gray-600">BD</h2>
                        <h2 class="text-center   text-gray-600">Engineering</h2>
                        <h2 class="text-center   text-gray-600">EXIM</h2>
                        <h2 class="text-center   text-gray-600">Finance_Accounting</h2>
                        <h2 class="text-center   text-gray-600">GA</h2>
                        <h2 class="text-center   text-gray-600">Gudang</h2>
                        <h2 class="text-center   text-gray-600">HR</h2>
                        <h2 class="text-center   text-gray-600">Legal</h2>
                        <h2 class="text-center   text-gray-600">Procurement</h2>
                        <h2 class="text-center   text-gray-600">Produksi</h2>
                        <h2 class="text-center   text-gray-600">Quality Control</h2>
                        <h2 class="text-center   text-gray-600">Yifang</h2>


                    </div>
                    <div class="flex flex-column gap-2">
                        <h2 class="text-center  font-semibold text-gray-600">{{ $department_BD }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $department_Engineering }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $department_EXIM }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $department_Finance_Accounting }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $department_GA }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $department_Gudang }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $department_HR }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $department_Legal }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $department_Procurement }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $department_Produksi }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $department_Quality_Control }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $department_Yifang }}</h2>
                    </div>
                </div>
            </div>
        </div>
        {{-- Jabatan --}}
        <div>
            <div class="h-3 rounded-t-lg bg-red-500">
            </div>

            <div class="bg-red-200 w-96 h-96 shadow p-3 rounded-b-lg overflow-y-auto ">
                <p class="text-center text-lg mb-3">Jabatan</p>
                <div class="flex gap-3 justify-evenly">
                    <div class="flex flex-column gap-2">
                        <h2 class="text-center   text-gray-600">Admin</h2>
                        <h2 class="text-center   text-gray-600">Asisten Direktur</h2>
                        <h2 class="text-center   text-gray-600">Asisten Kepala</h2>
                        <h2 class="text-center   text-gray-600">Asisten Manager</h2>
                        <h2 class="text-center   text-gray-600">Asisten Pengawas</h2>
                        <h2 class="text-center   text-gray-600">Asisten Wakil_Presiden</h2>
                        <h2 class="text-center   text-gray-600">Design grafis</h2>
                        <h2 class="text-center   text-gray-600">Director</h2>
                        <h2 class="text-center   text-gray-600">Kepala</h2>
                        <h2 class="text-center   text-gray-600">Manager</h2>
                        <h2 class="text-center   text-gray-600">Pengawas</h2>
                        <h2 class="text-center   text-gray-600">President</h2>
                        <h2 class="text-center   text-gray-600">Senior_staff</h2>
                        <h2 class="text-center   text-gray-600">Staff</h2>
                        <h2 class="text-center   text-gray-600">Supervisor</h2>
                        <h2 class="text-center   text-gray-600">Vice President</h2>
                        <h2 class="text-center   text-gray-600">Satpam</h2>
                        <h2 class="text-center   text-gray-600">Koki</h2>
                        <h2 class="text-center   text-gray-600">Dapur Kantor</h2>
                    </div>
                    <div class="flex flex-column gap-2">
                        <h2 class="text-center  font-semibold text-gray-600">{{ $jabatan_Admin }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $jabatan_Asisten_Direktur }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $jabatan_Asisten_Kepala }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $jabatan_Asisten_Manager }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $jabatan_Asisten_Pengawas }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $jabatan_Asisten_Wakil_Presiden }}
                        </h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $jabatan_Design_grafis }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $jabatan_Director }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $jabatan_Kepala }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $jabatan_Manager }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $jabatan_Pengawas }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $jabatan_President }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $jabatan_Senior_staff }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $jabatan_Staff }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $jabatan_Supervisor }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $jabatan_Vice_President }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $jabatan_Satpam }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $jabatan_Koki }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ $jabatan_Dapur_Kantor }}</h2>

                    </div>
                </div>
            </div>
        </div>
    </div>







    {{-- <div style="display: none">
        <div class="w-1/5 h-40 bg-teal-500 rounded-xl shadow-xl">
            <h2></h2>
            1000
        </div>
    </div> --}}

    <style>
        .c-dashboardInfo {
            margin-bottom: 15px;
        }

        .c-dashboardInfo .wrap {
            background: #ffffff;
            box-shadow: 2px 10px 20px rgba(0, 0, 0, 0.1);
            border-radius: 7px;
            text-align: center;
            position: relative;
            overflow: hidden;
            padding: 40px 25px 20px;
            height: 100%;
        }

        .c-dashboardInfo__title,
        .c-dashboardInfo__subInfo {
            color: #6c6c6c;
            font-size: 1.18em;
        }

        .c-dashboardInfo span {
            display: block;
        }

        .c-dashboardInfo__count {
            font-weight: 600;
            font-size: 2.5em;
            line-height: 64px;
            color: #323c43;
        }

        .c-dashboardInfo .wrap:after {
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 10px;
            content: "";
        }

        .c-dashboardInfo:nth-child(1) .wrap:after {
            background: linear-gradient(82.59deg, #00c48c 0%, #00a173 100%);
        }

        .c-dashboardInfo:nth-child(2) .wrap:after {
            background: linear-gradient(81.67deg, #0084f4 0%, #1a4da2 100%);
        }

        .c-dashboardInfo:nth-child(3) .wrap:after {
            background: linear-gradient(69.83deg, #0084f4 0%, #00c48c 100%);
        }

        .c-dashboardInfo:nth-child(4) .wrap:after {
            background: linear-gradient(81.67deg, #ff647c 0%, #1f5dc5 100%);
        }

        .c-dashboardInfo__title svg {
            color: #d7d7d7;
            margin-left: 5px;
        }

        .MuiSvgIcon-root-19 {
            fill: currentColor;
            width: 1em;
            height: 1em;
            display: inline-block;
            font-size: 24px;
            transition: fill 200ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
            user-select: none;
            flex-shrink: 0;
        }
    </style>
@endsection
