<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AbsensiModel;
use App\Models\KegiatanModel;
use CodeIgniter\Pager\Pager;

class Anggota extends BaseController
{
    protected $AbsensiModel;
    protected $KegiatanModel;

    public function __construct()
    {
        $this->AbsensiModel = new AbsensiModel();
        $this->KegiatanModel = new KegiatanModel();
    }

    public function index()
    {
        $nim = session('LoggedUserData')['nim'];
        $pager = service('pager');

        $page = (int) ($this->request->getGet('page') ?? 1); // jika request get page tidak ditemukan maka nilai otomatis 1

        // dd($page);

        $perPage = 5;
        $offset = (1 + (($page - 1) * $perPage)) - 1; // (1 + ((1-1) * 10)) - 1 = 0
        $limit = $perPage;

        // dd($limit . ", " . $offset);

        $total = $this->AbsensiModel->getKegiatanAnggota($nim)->getNumRows();
        $history = $this->AbsensiModel->getKegiatanAnggotaPagination($nim, $limit, $offset)->getResultArray();

        // dd($pager);

        $pager_links = $pager->makeLinks($page, $perPage, $total, 'paging_history');
        $page_count = $pager->getPageCount();

        // dd($history);

        return view('anggota/v_index', [
            'title' => 'Home',
            'pager_links' => $pager_links,
            'history' => $history,
            'page_count' => $page_count
        ]);
    }

    // public function index()
    // {
    //     $history = $this->AbsensiModel->getKegiatanAnggota(session('LoggedUserData')['nim']);

    //     return view('anggota/v_index', [
    //         'title' => 'Home',
    //         'history' => $history->getResultArray(),
    //     ]);
    // }

    public function absensi()
    {
        return view('anggota/v_absensi', [
            'title' => 'Absensi'
        ]);
    }

    public function scanAbsensi()
    {
        if ($this->request->isAJAX()) {
            $id_anggota = $this->request->getPost('id_anggota');
            $kode_unik = $this->request->getPost('kode_unik');
            $cekAbsensi = $this->AbsensiModel->getScanAbsensi($id_anggota, $kode_unik)->getRowArray();
            // cek apakah user sudah melakukan absensi
            if ($cekAbsensi) {
                $json = [
                    'error' => 'Anda sudah melakukan absensi !'
                ];
            } else {
                // cek kode unik pada tabel kegiatan
                $cekKodeUnik = $this->KegiatanModel->where('kode_unik', $kode_unik)->get()->getRowArray();
                if ($cekKodeUnik) {
                    try {
                        $this->AbsensiModel->insert([
                            'id_anggota' => $id_anggota,
                            'id_kegiatan' => $cekKodeUnik['id'],
                            'tanggal_absen' => date('Y-m-d H:i:s'),
                            'status_absen' => 1,
                            'oleh' => "User"
                        ]);
                        $json = [
                            'success' => 'Absensi berhasil'
                        ];
                    } catch (\Throwable $th) {
                        $json = [
                            'error' => 'Terdapat kesalahan pada sistem !'
                        ];
                    }
                } else {
                    $json = [
                        'error' => 'Kode tidak valid !'
                    ];
                }
            }
            echo json_encode($json);
        } else {
            exit("Maaf tidak dapat diproses !");
        }
    }
}
