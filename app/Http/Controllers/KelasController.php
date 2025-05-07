<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {

        $data = [
            'title' => 'Kelas',
            'breadcrumb' => [
                [
                    'name' => 'Kelas',
                    'url' => route('kelas'),
                    'active' => true,
                ],
            ]
        ];
        return view('pages.kelas', $data);
    }
}
