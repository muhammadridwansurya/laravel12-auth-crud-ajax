<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class KelasController extends Controller
{
    protected $apiBaseUrl = 'http://127.0.0.1:1323/api/kelas';
    protected $token = 'secretkeyaja'; // Token Bearer

    private function withHeaders()
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ]);
    }

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

    public function getData()
    {
        $response = $this->withHeaders()->get($this->apiBaseUrl);
        $kelas = $response->json('data');
        $data = [];
        $no = 1;

        if ($kelas) {
            foreach ($kelas as $item) {
                $btn_edit = '<a href="javascript:void(0);" class="btn btn-warning btn-edit" data-id="' . $item['id'] . '">Edit</a>';
                $btn_hapus = '<a href="javascript:void(0);" data-id="' . $item['id'] . '" class="btn btn-danger btn-delete">Hapus</a>';

                $data[] = [
                    $no++,
                    $item['nama_kelas'],
                    ($item['status'] == "true" ? 'Aktif' : 'Tidak Aktif'),
                    $btn_edit . ' ' . $btn_hapus,
                ];
            }
        }

        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => 'data berhasil ditemukan',
        ]);
    }

    public function getDataById($idKelas)
    {
        $response = $this->withHeaders()->get($this->apiBaseUrl . '/' . $idKelas);

        if ($response->failed()) {
            return response()->json([
                'status' => false,
                'message' => 'data tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $response->json('data'),
            'message' => 'data berhasil ditemukan',
        ]);
    }

    public function insertData(Request $request)
    {
        $data = $request->only(['nama_kelas', 'status']);

        $response = $this->withHeaders()->post($this->apiBaseUrl, $data);

        if ($response->failed()) {
            $responseData = $response->json();

            // Cek apakah ada errors dari API
            if (isset($responseData['errors'])) {
                // Ambil pesan error dari API
                $errors = $responseData['errors'];

                // Bisa kirim balik ke client dengan struktur seperti ini
                return response()->json([
                    'status' => false,
                    'message' => $errors
                ], 422);
            }

            // Kalau gak ada errors detail, kembalikan pesan error umum
            return response()->json([
                'status' => false,
                'message' => $responseData['message'] ?? 'Terjadi kesalahan pada server'
            ], $response->status());
        }

        return response()->json([
            'status' => true,
            'message' => 'data berhasil ditambahkan',
        ], 201);
    }

    public function updateData(Request $request, $idKelas)
    {
        $data = $request->only(['nama_kelas', 'status']);
        $data['id'] = $idKelas;

        $response = $this->withHeaders()->put($this->apiBaseUrl . '/' . $idKelas, $data);
        if ($response->failed()) {
            $responseData = $response->json();

            // Cek apakah ada errors dari API
            if (isset($responseData['errors'])) {
                // Ambil pesan error dari API
                $errors = $responseData['errors'];

                // Bisa kirim balik ke client dengan struktur seperti ini
                return response()->json([
                    'status' => false,
                    'message' => $errors
                ], 422);
            }

            // Kalau gak ada errors detail, kembalikan pesan error umum
            return response()->json([
                'status' => false,
                'message' => $responseData['message'] ?? 'Terjadi kesalahan pada server'
            ], $response->status());
        }

        return response()->json([
            'status' => true,
            'message' => 'data berhasil diubah',
        ]);
    }

    public function deleteData($idKelas)
    {
        $response = $this->withHeaders()->delete($this->apiBaseUrl . '/' . $idKelas);

        if ($response->failed()) {
            $responseData = $response->json();

            // Kalau gak ada errors detail, kembalikan pesan error umum
            return response()->json([
                'status' => false,
                'message' => $responseData['message'] ?? 'Terjadi kesalahan pada server'
            ], $response->status());
        }
        return response()->json([
            'status' => true,
            'message' => 'data berhasil dihapus',
        ]);
    }
}
