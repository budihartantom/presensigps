<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class PresensiController extends Controller
{
	public function create()
	{
		$hariini = date('Y-m-d');
		$nik = Auth::guard('karyawan')->user()->nik;
		$cek = DB::table('presensi')->where('tgl_presensi', $hariini)->where('nik', $nik)->count();
		return view('presensi.create', compact('cek'));
	}

	public function histori()
	{
		$namabulan = ["", "januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
		return view('presensi.histori', compact('namabulan'));
	}
	public function gethistori(Request $request)
	{
		$bulan = $request->bulan;
		$tahun = $request->tahun;
		$nik = Auth::guard('karyawan')->user()->nik;

		$histori = Db::table('presensi')
		->whereMonth('tgl_presensi', $bulan)
		->whereYear('tgl_presensi', $tahun)
		->where('nik', $nik)
		->orderBy('tgl_presensi')
		->get();

		// dd($histori);
		return view('presensi.gethistori', compact('histori'));

        // echo $bulan . "dan" . $tahun;
	}

	public function store(Request $request)
	{
		$nik = Auth::guard('karyawan')->user()->nik;
		$tgl_presensi = date('Y-m-d');
		$jam = date('H:i:s');	
		$latitudekantor = -5.44535890578211;
		$longitudekantor = 105.0157902658827;
		$lokasi = $request->lokasi;
		$lokasiuser = explode(",", $lokasi);
		$latitudeuser = $lokasiuser[0];
		$longitudeuser = $lokasiuser[1];
		// dd($lokasi);
		
		$jarak = $this->distance($latitudekantor, $longitudekantor, $latitudeuser, $longitudekantor);
		$radius = round($jarak['meters']);
		// dd($radius);
		$cek = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->count();

		if ($cek  > 0){
			$ket = 'out';
		} else {
			$ket = 'in';
		}
		$image = $request->image;
       // echo $image;
       // die;
		$folderPath = 'public/uploads/absensi/';
		$formatName = $nik . "-" . $tgl_presensi . "-" . $ket;
		$image_parts = explode(';base64', $image);
		$image_base64 = base64_decode($image_parts[1]);
		$fileName = $formatName . '.png';
		$file = $folderPath . $fileName;
		
		if($radius > 20 ){
			echo "error|Maaf Anda  Berada Diluar Radius, Jarak Anda " . $radius . " meter dari Kantor|";
		}else{
			if ($cek > 0){
				$data_pulang = [
					'jam_out' => $jam,
					'foto_out' => $fileName,
					'location' => $lokasi
				];
				$update = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->update($data_pulang);
				if ($update) {
					echo 0;
					Storage::put($file, $image_base64); 
				} else {
					echo 1;
				}
			} else {
				$data = [
					'nik' => $nik,
					'tgl_presensi' => $tgl_presensi,
					'jam_in' => $jam,
					'foto_in' => $fileName,
					'location' => $lokasi
				];

				$simpan = DB::table('presensi')->insert($data);
				if ($simpan) {
					echo 0;
					Storage::put($file, $image_base64); 
				} else {
					echo '1';
				}
			}
		}
	}


	//Menghitung Jarak
	function distance($lat1, $lon1, $lat2, $lon2)
	{
		$theta = $lon1 - $lon2;
		$miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
		$miles = acos($miles);
		$miles = rad2deg($miles);
		$miles = $miles * 60 * 1.1515;
		$feet = $miles * 5280;
		$yards = $feet / 3;
		$kilometers = $miles * 1.609344;
		$meters = $kilometers * 1000;
		return compact('meters');
	}


}



