<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
    	// $hariini = date("Y-m-d");
    	// $bulanini = date("m"); //1 atau Januari
    	// $tahunini = date("y"); // 2024
        $hariini = date("Y-m-d");
        $bulanini = date("m") * 1; // January as 01
        $tahunini = date("Y"); // 2024
        $nik = Auth::guard('karyawan')
        ->user()
        ->nik;
        $presensihariini = DB::table('presensi')
        ->where('nik',$nik)
        ->where('tgl_presensi', $hariini)->first();
        $historibulanini = DB::table('presensi')
        ->where('nik',$nik)
        ->whereMonth('tgl_presensi', $bulanini)
        ->whereYear('tgl_presensi', $tahunini)
        ->orderBy('tgl_presensi')
        ->get();
        $rekappresensi = DB::table('presensi')
        ->selectRaw('COUNT(nik) as jmlhadir, SUM(CASE WHEN jam_in > "08:00:00" THEN 1 ELSE 0 END) as jmlterlambat')
        ->where('nik', $nik)
        ->whereMonth('tgl_presensi', $bulanini)
        ->whereYear('tgl_presensi', $tahunini)
        ->first();
        // dd($rekappresensi);
        $leaderboard = DB::table('presensi')
        ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
        ->where('tgl_presensi', $hariini)
        ->orderBy('jam_in')
        ->get();
        $namabulan = ["", "januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        // dd($namabulan[$bulanini]);
        // $historibulanini = DB::table('presensi')
        // ->whereRaw('MONTH(tgl_presensi)="'. $bulanini .'"')
        // ->whereRaw('YEAR(tgl_presensi)="' . $tahunini .'"' )
        // ->orderBy('tgl_presensi')
        // ->get();

    	  // dd($presensihariini);
        return view('dashboard.dashboard', compact('presensihariini', 'historibulanini','namabulan', 'bulanini', 'tahunini', 'rekappresensi', 'leaderboard'));
    }
}
