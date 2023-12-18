@extends('layouts.app4')

@section('title', 'Dashboard')

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0-rc.1/chartjs-plugin-datalabels.min.js"
        integrity="sha512-+UYTD5L/bU1sgAfWA0ELK5RlQ811q8wZIocqI7+K0Lhh8yVdIoAMEs96wJAIbgFvzynPm36ZCXtkydxu1cs27w=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    {{-- placement --}}
    <script>
        var placement = <?php echo json_encode($placementArr); ?>;
        var placementLabel = <?php echo json_encode($placementLabelArr); ?>;

        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: 'pie',
            data: {
                {{-- labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'], --}}
                labels: placementLabel,
                datasets: [{
                    label: 'Jumlah Karyawan ',
                    data: placement,
                    borderWidth: 1,

                    {{-- datalabels: {
                        color: 'white',
                    },
                    formatter: function(value, ctx) {
                        return context.chart.data.label[ctx.dataIndex];
                    } --}}
                }]
            },
            plugins: [ChartDataLabels],
            options: {
                layout: {
                    padding: 20
                },

                plugins: {
                    legend: {
                        display: true
                    },
                    datalabels: {
                        color: 'white',

                        formatter: function(value, context) {
                            {{-- return context.chart.data.labels[context.dataIndex] + ' : ' + context.chart.data
                                .datasets[0].data[context.dataIndex] --}}
                            return context.chart.data
                                .datasets[0].data[context.dataIndex]
                        }
                    },

                },

            },



        });
    </script>
    {{-- Company --}}
    <script>
        var companyArr = <?php echo json_encode($companyArr); ?>;
        var companyLabelArr = <?php echo json_encode($companyLabelArr); ?>;

        const ctx1 = document.getElementById('chart_company');

        new Chart(ctx1, {
            type: 'pie',
            data: {
                {{-- labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'], --}}
                labels: companyLabelArr,
                datasets: [{
                    label: 'Jumlah Karyawan ',
                    data: companyArr,

                    datalabels: {

                        anchor: 'center',
                        display: true,
                        align: 'center',

                    },

                }]
            },
            plugins: [ChartDataLabels],
            options: {
                layout: {
                    padding: 20
                },
                plugins: {
                    legend: {
                        display: true
                    },
                    datalabels: {
                        color: 'white',

                        formatter: function(value, context) {
                            {{-- return context.chart.data.labels[context.dataIndex] + ' : ' + context.chart.data
                                .datasets[0].data[context.dataIndex] --}}
                            return context.chart.data
                                .datasets[0].data[context.dataIndex]
                        }
                    },

                },

            },



        });
    </script>

    {{-- Jumlah karyawan pria wanita --}}
    <script>
        var jumlah_karyawanArr = <?php echo json_encode($jumlah_karyawanArr); ?>;
        var jumlah_karyawan_labelArr = <?php echo json_encode($jumlah_karyawan_labelArr); ?>;

        const ctx3 = document.getElementById('jumlah_karyawan');

        new Chart(ctx3, {
            type: 'pie',
            data: {
                {{-- labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'], --}}
                labels: jumlah_karyawan_labelArr,
                datasets: [{
                    label: 'Jumlah Karyawan ',
                    data: jumlah_karyawanArr,
                    borderWidth: 1,

                    {{-- datalabels: {
                        color: 'white',
                    },
                    formatter: function(value, ctx3) {
                        return context.chart.data.label[ctx.dataIndex];
                    } --}}
                }]
            },
            plugins: [ChartDataLabels],
            options: {
                layout: {
                    padding: 20
                },

                plugins: {
                    legend: {
                        display: true
                    },
                    datalabels: {
                        color: 'white',

                        formatter: function(value, context) {
                            {{-- return context.chart.data.labels[context.dataIndex] + ' : ' + context.chart.data
                                .datasets[0].data[context.dataIndex] --}}
                            return context.chart.data
                                .datasets[0].data[context.dataIndex]
                        }
                    },
                },
            },
        });
    </script>
@endsection

@section('content')
    <div class="pt-5">
    </div>



    {{-- Dashboard device = {{ isDesktop() }} --}}
    <div id="root">
        <div class="container pt-5">
            <div class="row align-items-stretch">
                <div class="c-dashboardInfo col-lg-3 col-md-6">
                    <div class="wrap">
                        <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">
                            {{ __('Karyawan Baru MTD') }}
                        </h4><span class="hind-font caption-12 c-dashboardInfo__count">{{ $karyawan_baru_mtd }}</span>

                    </div>
                </div>
                <div class="c-dashboardInfo col-lg-3 col-md-6">
                    <div class="wrap">
                        <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">
                            {{ __('Karyawan Resigned MTD') }}</h4>
                        <span class="hind-font caption-12 c-dashboardInfo__count">{{ $karyawan_resigned_mtd }}</span>
                        {{-- <span
                            class="hind-font caption-12 c-dashboardInfo__subInfo">Last month: €30</span> --}}
                    </div>
                </div>
                <div class="c-dashboardInfo col-lg-3 col-md-6">
                    <div class="wrap">
                        <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title text-center">
                            {{ __('Karyawan Blacklist MTD') }}</h4><span
                            class="hind-font caption-12 c-dashboardInfo__count">{{ $karyawan_blacklist_mtd }}</span>
                    </div>
                </div>
                <div class="c-dashboardInfo col-lg-3 col-md-6">
                    <div class="wrap">
                        <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">
                            {{ __('Karyawan Aktif MTD') }}
                        </h4><span
                            class="hind-font caption-12 c-dashboardInfo__count">{{ number_format($karyawan_aktif_mtd) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Department --}}
    <div class="d-flex gap-3 px-2 flex-column flex-xl-row justify-evenly mt-3">
        {{-- Jumlah karyawan Pria wanita --}}
        <div>
            <div class="h-3 rounded-t-lg bg-teal-500">
            </div>
            <div class="bg-teal-100 w-96  rounded-b-lg shadow p-3  ">
                <p class="text-center text-lg mb-3 ">{{ __('Jumlah Karyawan') }}</p>
                <h1 class="text-center font-semibold text-xl">{{ $jumlah_total_karyawan }}</h1>
                <div style="width:350px;">
                    <canvas id="jumlah_karyawan">
                    </canvas>
                </div>
            </div>
        </div>

        {{-- Departement --}}
        <div>
            <div class="h-3 rounded-t-lg bg-green-500">
            </div>
            <div class="bg-green-200 w-96  rounded-b-lg shadow p-3  ">
                <p class="text-center text-lg mb-3 ">{{ __('Department') }}</p>
                <div class="flex gap-3 justify-evenly">
                    <div class="flex flex-column gap-2">
                        <h2 class="text-center   text-gray-600">{{ __('BD') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('Engineering') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('EXIM') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('Finance Accounting') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('GA') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('Gudang') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('HR') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('Legal') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('Procurement') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('Produksi') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('Quality Control') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('Board of Director') }}</h2>


                    </div>
                    <div class="flex flex-column gap-2">
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($department_BD) }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($department_Engineering) }}
                        </h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($department_EXIM) }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">
                            {{ number_format($department_Finance_Accounting) }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($department_GA) }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($department_Gudang) }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($department_HR) }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($department_Legal) }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($department_Procurement) }}
                        </h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($department_Produksi) }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">
                            {{ number_format($department_Quality_Control) }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">
                            {{ number_format($department_Board_of_Director) }}</h2>
                    </div>
                </div>
            </div>
        </div>
        {{-- Jabatan --}}
        <div>
            <div class="h-3 rounded-t-lg bg-red-500">
            </div>

            <div class="bg-red-200 w-96 h-96 shadow p-3 rounded-b-lg overflow-y-auto ">
                <p class="text-center text-lg mb-3">{{ __('Jabatan') }}</p>
                <div class="flex gap-3 justify-evenly">
                    <div class="flex flex-column gap-2">
                        <h2 class="text-center   text-gray-600">{{ __('Admin') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('Asisten Direktur') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('Asisten Kepala') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('Asisten Manager') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('Asisten Pengawas') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('Asisten Wakil_Presiden') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('Design grafis') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('Director') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('Kepala') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('Manager') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('Pengawas') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('President') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('Senior staff') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('Staff') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('Supervisor') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('Vice President') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('Satpam') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('Koki') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('Dapur Kantor') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('Dapur Pabrik') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('QC Aging') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('Driver') }}</h2>

                    </div>
                    <div class="flex flex-column gap-2">
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($jabatan_Admin) }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">
                            {{ number_format($jabatan_Asisten_Direktur) }}
                        </h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($jabatan_Asisten_Kepala) }}
                        </h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($jabatan_Asisten_Manager) }}
                        </h2>
                        <h2 class="text-center  font-semibold text-gray-600">
                            {{ number_format($jabatan_Asisten_Pengawas) }}
                        </h2>
                        <h2 class="text-center  font-semibold text-gray-600">
                            {{ number_format($jabatan_Asisten_Wakil_Presiden) }}
                        </h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($jabatan_Design_grafis) }}
                        </h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($jabatan_Director) }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($jabatan_Kepala) }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($jabatan_Manager) }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($jabatan_Pengawas) }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($jabatan_President) }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($jabatan_Senior_staff) }}
                        </h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($jabatan_Staff) }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($jabatan_Supervisor) }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($jabatan_Vice_President) }}
                        </h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($jabatan_Satpam) }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($jabatan_Koki) }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($jabatan_Dapur_Kantor) }}
                        </h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($jabatan_Dapur_Pabrik) }}
                        </h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($jabatan_QC_Aging) }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($jabatan_Driver) }}</h2>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div
        class="flex py-3  px-2 mt-5 flex-col flex-xl-row items-center justify-evenly bg-blue-100 col-xl-10 mx-auto rounded-xl shadow ">
        <div>
            <div class="h-3 rounded-t-lg bg-blue-500">
            </div>
            <div class="bg-blue-200 h-96 rounded-b-lg w-96 shadow-md p-3 ">
                <p class="text-center text-lg mb-3">{{ __('Jumlah Karyawan') }}</p>
                <div class="flex gap-3 justify-evenly">
                    <div class="flex flex-column gap-2">
                        <h2 class="text-center   text-gray-600">{{ __('Pabrik 1') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('Pabrik 2') }}</h2>
                        <h2 class="text-center   text-gray-600">{{ __('Kantor') }}</h2>
                        <h2 class="text-center font-semibold  text-gray-600 mb-2 text-lg">{{ __('Total') }}</h2>
                        <h2 class="text-center   text-gray-600">ASB</h2>
                        <h2 class="text-center   text-gray-600">DPA</h2>
                        <h2 class="text-center   text-gray-600">YCME</h2>
                        <h2 class="text-center   text-gray-600">YEV</h2>
                        <h2 class="text-center   text-gray-600">YIG</h2>
                        <h2 class="text-center   text-gray-600">YSM</h2>
                        <h2 class="text-center font-semibold  text-gray-600 text-lg">{{ __('Total') }}</h2>
                    </div>
                    <div class="flex flex-column gap-2">
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($jumlah_Pabrik_1) }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($jumlah_Pabrik_2) }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($jumlah_Kantor) }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600 mb-2 text-lg">
                            {{ number_format($jumlah_placement) }}
                        </h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($jumlah_ASB) }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($jumlah_DPA) }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($jumlah_YCME) }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($jumlah_YEV) }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($jumlah_YIG) }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600">{{ number_format($jumlah_YSM) }}</h2>
                        <h2 class="text-center  font-semibold text-gray-600 text-lg">{{ number_format($jumlah_company) }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts --}}


        <div class="w-96 xl:w-1/3">
            {{-- <div style="width: 450px"> --}}
            <canvas id="myChart"></canvas>
        </div>
        <div class="w-96 xl:w-1/3">
            {{-- <div style="width: 450px"> --}}
            <canvas id="chart_company"></canvas>
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
