<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        // $password = Hash::make('fajar123');
        // dd($password);
        // var_dump($request->session()->has('id'));
        if ($request->session()->has('id')) 
        {
            return redirect("/");
        }
        else
        {
            return view ("pages.login");
        }
    }

    public function postLogin(Request $request){
        $this->validate($request,[
            'username' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
            'password' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
        ]);
        $username = $request->input('username');
        $password = $request->input('password');
        $data = DB::table('tbl_user')->where('username',$username)->first();
        // dd($data);
        if(empty($data)){
            return back()->with('pesan','Username Atau Password Salah!');
        }else{
        $cek = Hash::check($password, $data->password);
        if($cek == false)
        {
            return back()->with('pesan','Username Atau Password Salah!');
        }
        else
        {
        	$request->session()->put("id", $data->id_user);
			$request->session()->put("nama", $data->nama_user);
			$request->session()->put("level", $data->level);
            $request->session()->put("cabang", $data->id_cabang);
            // login pimpinan
				if($data->level == "1")
		        {
		        	return redirect('/');
                }
                // login kepala cabang atau super admin
                elseif ($data->level == "2")
		        {
		        	return redirect('/');
                }
                // login amdin cabang atau admin
                elseif ($data->level == "3")
		        {
		        	return redirect('/');
		        }
		        elseif ($data->level == "4")
		        {
		        	return redirect('/');
		        } 
        }
    }
    }

    public function postLogout(Request $request)
    {
    	$request->session()->forget('id');
    	$request->session()->forget('nama');
    	$request->session()->forget('level');
    	$request->session()->forget('cabang');
    	return redirect("/login");
    }
}
