<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index()
    {
        // cara menggil session
        $nama_user = session('nama_user');
        $email = session('detail.email');

        $data = [
            'title' => 'Halaman Login',
            'nama_user' => $nama_user,
            'email' => $email,
        ];
        return view('pages.dashboard', $data);
    }
}
