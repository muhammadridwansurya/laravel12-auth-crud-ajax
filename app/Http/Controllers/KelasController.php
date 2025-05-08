<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;

class KelasController extends Controller
{
    public function index()
    {

        $data = [
            'title' => 'Kelas',
            'url_json' => route('kelas.json_data'),
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

    public function getData() // function untuk menampilkan data melalui json
    {
        $kelas = Kelas::getKelas();
        $data = [];
        $no = 1;
        if($kelas) {
            foreach($kelas as $item) {
                $btn_edit = '<a class="btn btn-warning" onclick='."btnEdit('". route('kelas.edit', ['siswa_id' => $item->id]) ."')".' >Edit</i></a>';
                $btn_hapus = '<a onclick='."btnDelete('". route('kelas.hapus', ['siswa_id' => $item->id]) ."')".' class="btn btn-danger">Hapus</a>';

                $data[] = [
                    $no++,
                    $item->nama_kelas,
                    ($item->status == 1 ? 'Aktif' : 'Tidak Aktif'),
                    $btn_edit . ' ' . $btn_hapus,
                ];
            }
        }
        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => 'data berhasil ditemukan',
        ], 200, ['Content-Type' => 'application/json; charset=utf-8'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    }

    public function insertData(Request $request)
    {

    }

    public function updateData(Request $request, $siswa_id)
    {

    }

    public function deleteData(Request $request, $siswa_id)
    {

    }
}
