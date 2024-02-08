<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AbsensiModel;
use App\Models\KegiatanModel;
use App\Models\MemberModel;

class Absensi extends BaseController
{
    protected $AbsensiModel;
    protected $MemberModel;
    protected $KegiatanModel;

    public function __construct()
    {
        $this->AbsensiModel = new AbsensiModel();
        $this->MemberModel = new MemberModel();
        $this->KegiatanModel = new KegiatanModel();
    }

    public function index()
    {
        if ($this->request->getPost()) {
            $tgl_mulai = $this->request->getPost('tgl_mulai');
            $tgl_selesai = $this->request->getPost('tgl_selesai');
            $kegiatan = $this->KegiatanModel->getKegiatanByTanggal($tgl_mulai, $tgl_selesai);
        } else {
            $kegiatan = $this->KegiatanModel->get();
        }
        return view('admin/absensi/v_index', [
            'title' => 'Laporan Absensi',
            'subtitle' => '',
            'anggota' => $this->MemberModel->getAnggota()->getResultArray(),
            // 'kegiatan' => $this->KegiatanModel->where('status_kegiatan', 2)->get()->getResultArray(),
            'kegiatan' => $kegiatan
        ]);
    }

    public function cetak_laporan($tgl_mulai = null, $tgl_selesai = null)
    {
        if ($tgl_mulai && $tgl_selesai) {
            $kegiatan = $this->KegiatanModel->getKegiatanByTanggal($tgl_mulai, $tgl_selesai);
        } else {
            $kegiatan = $this->KegiatanModel->get();
        }
        return view('admin/absensi/cetak_laporan', [
            'title' => 'Cetak Laporan Absensi',
            'anggota' => $this->MemberModel->getAnggota()->getResultArray(),
            'kegiatan' => $kegiatan,
            'tgl_mulai' => $tgl_mulai,
            'tgl_selesai' => $tgl_selesai,
        ]);
    }

    public function absenManual()
    {
        if ($this->request->isAJAX()) {
            $id_kegiatan = $this->request->getPost('id_kegiatan');
            $id_anggota = $this->request->getPost('id_anggota');
            $status_absen = $this->request->getPost('status_absen');
            try {
                $cekAbsensi = $this->AbsensiModel->getAbsensiAnggota($id_anggota, $id_kegiatan)->getRowArray();
                if ($cekAbsensi) {
                    $this->AbsensiModel->update($cekAbsensi['id'], [
                        'status_absen' => $status_absen,
                        'tanggal_absen' => date('Y-m-d H:i:s')
                    ]);
                    $json = [
                        'success' => "Ubah absensi anggota dengan nama " . $cekAbsensi['nama'] . " berhasil dilakukan"
                    ];
                } else {
                    $this->AbsensiModel->insert([
                        'id_kegiatan' => $id_kegiatan,
                        'id_anggota' => $id_anggota,
                        'status_absen' => $status_absen,
                        'tanggal_absen' => date('Y-m-d H:i:s'),
                        'oleh' => "Sistem" // 1 adalah sistem
                    ]);
                    $json = [
                        'success' => "Absensi anggota berhasil dilakukan"
                    ];
                }
            } catch (\Throwable $th) {
                $json = [
                    'error' => "Terdapat kesalahan pada sistem !"
                ];
            }
            echo json_encode($json);
        } else {
            exit("Tidak dapat diproses");
        }
    }

    public function modalAnggota()
    {
        if ($this->request->isAJAX()) {
            $id_anggota = $this->request->getPost('id_anggota');
            $cekAnggota = $this->MemberModel->getAnggota($id_anggota)->getRowArray();
            $kegiatan = $this->AbsensiModel->getKegiatanAnggota($id_anggota)->getResultArray();
            if ($cekAnggota) {
                $json = [
                    'data' => view('admin/absensi/modalanggota', [
                        'title' => 'Detail Anggota',
                        'anggota' => $cekAnggota,
                        'kegiatan' => $kegiatan
                    ])
                ];
            } else {
                $json = [
                    'error' => 'Anggota tidak ditemukan'
                ];
            }
            echo json_encode($json);
        } else {
            exit("Tidak dapat diproses");
        }
    }
}
