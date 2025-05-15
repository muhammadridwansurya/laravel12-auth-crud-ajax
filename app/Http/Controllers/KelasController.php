<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Kelas;

class KelasController extends Controller
{
    public function index()
    {

        $data = [
            'title' => 'Kelas',
            'url_menu' => [
                'url' => route('kelas'),
                'url_json' => route('kelas.json_data'),
            ],
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
                $btn_edit = '<a href="javascript:void(0);" class="btn btn-warning btn-edit" data-id="'. $item->id .'">Edit</i></a>';
                $btn_hapus = '<a href="javascript:void(0);" data-id="'. $item->id .'" class="btn btn-danger btn-delete">Hapus</a>';

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

    public function getDataById($idKelas) // function untuk mengambil data berdasarkan id
    {
        $kelas = Kelas::getKelasById($idKelas);
        if(!$kelas) {
            return response()->json([
                'status' => false,
                'message' => 'data tidak ditemukan',
            ])->header('Content-Type', 'application/json')->setStatusCode(404);
        }

        return response()->json([
            'status' => true,
            'data' => $kelas,
            'message' => 'data berhasil ditemukan',
        ])->header('Content-Type', 'application/json')->setStatusCode(200);
    }

    public function insertData(Request $request)
    {
        $data = $request->only(['nama_kelas', 'status']);

        $validator = Validator::make($data, [
            'nama_kelas' => ['required', 'unique:kelas', 'min:3', 'max:255'],
            'status' => ['required', 'in:1,0'],
        ],[
            'required' => ':attribute tidak boleh kosong.',
            'unique' => ':attribute telah dipakai.',
            'min' => ':attribute minimal :min.',
            'max' => ':attribute maksimal :max.',
            'in' => ':attribute tidak valid, hanya boleh data yang tersedia.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 422);
        }

        Kelas::insertKelas($data);

        return response()->json([
            'status' => true,
            'message' => 'data berhasil ditambahkan',
        ])->header('Content-Type', 'application/json')->setStatusCode(201);
    }

    public function updateData(Request $request, $idKelas)
    {
        $kelas = Kelas::getKelasById($idKelas);
        if(!$kelas) {
            return response()->json([
                'status' => false,
                'message' => 'data tidak ditemukan',
            ])->header('Content-Type', 'application/json')->setStatusCode(404);
        }

        $data = $request->only(['nama_kelas', 'status']);

        $validator = Validator::make($data, [
            'nama_kelas' => ['required', 'min:3', 'max:255', 'unique:kelas,nama_kelas,' . $kelas->id],
            'status' => ['required', 'in:1,0'],
        ],[
            'required' => ':attribute tidak boleh kosong.',
            'unique' => ':attribute telah dipakai.',
            'min' => ':attribute minimal :min.',
            'max' => ':attribute maksimal :max.',
            'in' => ':attribute tidak valid, hanya boleh data yang tersedia.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 422);
        }

        Kelas::updateKelas($data, $kelas->id);

        return response()->json([
            'status' => true,
            'message' => 'data berhasil diubah',
        ])->header('Content-Type', 'application/json')->setStatusCode(200);
    }

    public function deleteData(Request $request, $idKelas)
    {
        $kelas = Kelas::getKelasById($idKelas);
        if(!$kelas) {
            return response()->json([
                'status' => false,
                'message' => 'data tidak ditemukan',
            ])->header('Content-Type', 'application/json')->setStatusCode(404);
        }

        Kelas::deleteKelas($kelas->id);

        return response()->json([
            'status' => true,
            'message' => 'data berhasil dihapus',
        ])->header('Content-Type', 'application/json')->setStatusCode(200);
    }
}
