<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;


class AuthController extends Controller
{
    public function proseslogin(Request $request)
    {
    	// $pass = 123;
    	// echo Hash::make($pass);
    	if (Auth::guard('karyawan')->attempt(['nik' => $request->nik, 'password' => $request->password])){
    		// echo "Berhasil Login";
            return redirect('/dashboard');
    	} else {
    		// echo "Gagal Login";
           return redirect('/')->with(['warning' => 'Nik / Password Salah']); 
    	}

    }

    public function proseslogout()
    {
        if (Auth::guard('karyawan')->check()){
            Auth::guard('karyawan')->logout();
            return redirect('/');
        }
    }
}
