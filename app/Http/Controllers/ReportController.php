<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
// use Maatwebsite\Excel\Excel;
use Illuminate\Http\Request;
use App\Exports\BankReportExcel;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index () {

        return view('reports.index');
    }

    public function createExcel (Request $request) {

        $nama_file = "";
        switch($request->selectedCompany) {

            case '1' : $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->orderBy('id_karyawan', 'asc')->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                $nama_file="semua_karyawan_Bank.xlsx";
                 break;
            case '2' : $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->orderBy('id_karyawan', 'asc')->where('placement', 'YCME')->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                $nama_file="Pabrik-1_Bank.xlsx";
                break;
            case '3' : $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->orderBy('id_karyawan', 'asc')->where('placement', 'YEV')->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                $nama_file="Pabrik-2_Bank.xlsx";
                break;
            case '4' : $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->orderBy('id_karyawan', 'asc')->where('placement', 'YIG')->orWhere('placement', 'YSM')->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                $nama_file="Kantor_Bank.xlsx";
                break;
            case '5' : $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->orderBy('id_karyawan', 'asc')->where('company', 'ASB')->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                $nama_file="ASB_Bank.xlsx";
                break;
            case '6' : $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->orderBy('id_karyawan', 'asc')->where('company', 'DPA')->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                $nama_file="DPA_Bank.xlsx";
                break;
            case '7' : $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->orderBy('id_karyawan', 'asc')->where('company', 'YCME')->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                $nama_file="YCME_Bank.xlsx";
                break;
            case '8' : $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->orderBy('id_karyawan', 'asc')->where('company', 'YEV')->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                $nama_file="YEV_Bank.xlsx";
                break;
            case '9' : $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->orderBy('id_karyawan', 'asc')->where('company', 'YIG')->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                $nama_file="YIG_Bank.xlsx";
                break;
            case '10' : $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->orderBy('id_karyawan', 'asc')->where('company', 'YSM')->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                $nama_file="YSM_Bank.xlsx";
                break;
        }


        return Excel::download(new BankReportExcel($payroll), $nama_file);

    }
}
