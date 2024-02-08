<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AbsensiModel;
use App\Models\KegiatanModel;
use App\Models\MemberModel;

class Kegiatan extends BaseController
{
    protected $KegiatanModel;
    protected $MemberModel;
    protected $AbsensiModel;

    public function __construct()
    {
        $this->KegiatanModel = new KegiatanModel();
        $this->MemberModel = new MemberModel();
        $this->AbsensiModel = new AbsensiModel();
    }

    public function index()
    {
        if ($this->request->getPost()) {
            $tgl_mulai = $this->request->getPost('tgl_mulai');
            $tgl_selesai = $this->request->getPost('tgl_selesai');
            $kegiatan = $this->KegiatanModel->getKegiatanByTanggal($tgl_mulai, $tgl_selesai)->getResultArray();
        } else {
            $kegiatan = $this->KegiatanModel->getKegiatan()->getResultArray();
        }
        return view('admin/kegiatan/v_index', [
            'title' => 'Daftar Kegiatan',
            'subtitle' => '',
            'kegiatan' => $kegiatan
        ]);
    }

    public function modalKegiatan()
    {
        if ($this->request->isAJAX()) {
            $json = [
                'data' => view('admin/kegiatan/modaltambah', [
                    'title' => 'Tambah Kegiatan'
                ])
            ];
            echo json_encode($json);
        } else {
            exit('Tidak dapat diproses');
        }
    }

    public function modalEdit()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getGet('id');
            $cekKegiatan = $this->KegiatanModel->find($id);
            if ($cekKegiatan) {
                $json = [
                    'data' => view('admin/kegiatan/modaledit', [
                        'title' => 'Edit Kegiatan',
                        'kegiatan' => $cekKegiatan
                    ])
                ];
            } else {
                $json = [
                    'error' => "Kegiatan dengan ID " . $id . " tidak ditemukan !"
                ];
            }
            echo json_encode($json);
        } else {
            exit("Maaf tidak dapat diproses !");
        }
    }

    public function simpanKegiatan()
    {
        if ($this->request->isAJAX()) {
            $input = $this->request->getPost();
            $validation = \Config\Services::validation();

            $valid = $this->validate([
                'tgl_kegiatan' => [
                    'label' => 'Tanggal Kegiatan',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong !'
                    ]
                ],
                'nama_kegiatan' => [
                    'label' => 'Nama Kegiatan',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong !'
                    ]
                ],
                'keterangan' => [
                    'label' => 'Keterangan',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong !'
                    ]
                ],
            ]);
            if ($valid) {
                try {
                    $input['kode_unik'] = md5(rand(0, 1000));
                    $input['id_sekertaris'] = session('LoggedUserData')['nim'];
                    $input['status_kegiatan'] = 1;
                    $this->KegiatanModel->insert($input);
                    $json = [
                        'success' => 'Kegiatan berhasil ditambahkan'
                    ];
                } catch (\Throwable $th) {
                    $json = [
                        'error' => 'Terdapat kesalahan pada sistem !'
                    ];
                }
            } else {
                $json = [
                    'errors' => [
                        'nama_kegiatan' => $validation->getError('nama_kegiatan'),
                        'tgl_kegiatan' => $validation->getError('tgl_kegiatan'),
                        'keterangan' => $validation->getError('keterangan'),
                    ]
                ];
            }
            echo json_encode($json);
        } else {
            exit('Tidak dapat diproses');
        }
    }

    public function ubahKegiatan()
    {
        if ($this->request->isAJAX()) {
            $input = $this->request->getPost();
            $validation = \Config\Services::validation();

            $valid = $this->validate([
                'nama_kegiatan' => [
                    'label' => 'Nama Kegiatan',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong !'
                    ]
                ],
                'tgl_kegiatan' => [
                    'label' => 'Tanggal Kegiatan',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong !'
                    ]
                ],
                'keterangan' => [
                    'label' => 'Keterangan',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong !'
                    ]
                ],
            ]);
            if ($valid) {
                try {
                    $input['id_sekertaris'] = session('LoggedUserData')['nim'];
                    $this->KegiatanModel->update($input['id'], $input);
                    $json = [
                        'success' => 'Data kegiatan berhasil diubah'
                    ];
                } catch (\Throwable $th) {
                    $json = [
                        'error' => 'Terdapat kesalahan pada sistem !'
                    ];
                }
            } else {
                $json = [
                    'errors' => [
                        'nama_kegiatan' => $validation->getError('nama_kegiatan'),
                        'tgl_kegiatan' => $validation->getError('tgl_kegiatan'),
                        'tgl_selesai' => $validation->getError('tgl_selesai'),
                        'keterangan' => $validation->getError('keterangan'),
                    ]
                ];
            }
            echo json_encode($json);
        } else {
            exit('Tidak dapat diproses');
        }
    }

    public function hapusKegiatan()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $cekKegiatan = $this->KegiatanModel->find($id);
            if ($cekKegiatan) {
                try {
                    $id_anggota = [];
                    $cekAbsen = $this->AbsensiModel->select('id_kegiatan, id')
                        ->where('id_kegiatan', $id)->get()->getResultArray();
                    foreach ($cekAbsen as $key => $value) {
                        $id_anggota[] = $value['id'];
                    }

                    // jika sudah ada yang absen
                    if (count($id_anggota) > 0) {
                        $this->AbsensiModel->delete($id_anggota);
                    }

                    $this->KegiatanModel->delete($id);

                    $json = [
                        'success' => 'Kegiatan berhasil dihapus!'
                    ];
                } catch (\Throwable $th) {
                    $json = [
                        'error' => 'Kegiatan tidak dapat dihapus!'
                    ];
                }
            } else {
                $json = [
                    'error' => 'Kegiatan tidak ditemukan!'
                ];
            }
            echo json_encode($json);
        } else {
            exit('Tidak dapat diproses');
        }
    }

    public function ambil_kode_unik()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id_kegiatan');
            $cekKegiatan = $this->KegiatanModel->find($id);
            if ($cekKegiatan) {
                $json = [
                    'kode_unik' => $cekKegiatan['kode_unik']
                ];
            } else {
                $json = [
                    'error' => "Kegiatan dengan ID " . $id . " tidak ditemukan !"
                ];
            }
            echo json_encode($json);
        } else {
            exit("Maaf tidak dapat diproses !");
        }
    }

    public function ubahKodeUnik()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id_kegiatan');
            $this->KegiatanModel->update($id, [
                'kode_unik' => md5(rand(0, 1000))
            ]);
        } else {
            exit("Maaf tidak dapat diproses !");
        }
    }

    public function detail($id)
    {
        $cekKegiatan = $this->KegiatanModel->getKegiatan($id)->getRowArray();
        if ($cekKegiatan) {
            return view('admin/kegiatan/v_detail', [
                'title' => "Daftar Kegiatan",
                'subtitle' => "Detail Kegiatan",
                'kegiatan' => $cekKegiatan,
                'member' => $this->MemberModel->getAnggota()->getResultArray(),
                'db' => \Config\Database::connect()
            ]);
        } else {
            return redirect()->to(base_url('admin/kegiatan'));
        }
    }

    public function status_kegiatan()
    {
        if ($this->request->isAJAX()) {
            $id_kegiatan = $this->request->getPost('id_kegiatan');
            $status_kegiatan = $this->request->getPost('status_kegiatan');
            $cekKegiatan = $this->KegiatanModel->find($id_kegiatan);
            if ($cekKegiatan) {
                if ($status_kegiatan == 1) {    // jika status kegiatan aktif
                    $tgl_selesai = NULL;
                } else {
                    $tgl_selesai = date('Y-m-d H:i:s');
                    // jika status kegiatan tidak aktif maka orang2 yang belum absen akan dihitung sebagai alfa
                    $this->setAlfaKeAnggota($id_kegiatan, $tgl_selesai);
                }
                try {
                    $this->KegiatanModel->update($id_kegiatan, [
                        'status_kegiatan' => $status_kegiatan,
                        'tgl_selesai' => $tgl_selesai,
                    ]);
                    $json = [
                        'success' => "Status kegiatan berhasil diubah"
                    ];
                } catch (\Throwable $th) {
                    $json = [
                        'error' => "Terdapat kesalahan pada sistem"
                    ];
                }
            } else {
                $json = [
                    'error' => "Kegiatan denga ID " . $id_kegiatan . " tidak ditemukan"
                ];
            }
            echo json_encode($json);
        } else {
            exit("Maaf tidak dapat diproses !");
        }
    }

    // otomatis mengalfa orang yang belum absen
    private function setAlfaKeAnggota($id_kegiatan, $tgl_selesai)
    {
        // ambil semua anggota aktif
        $anggota = $this->MemberModel->getAnggota()->getResultArray();
        foreach ($anggota as $a) {
            // cek apakah anggota ada yang sudah absen
            $cekAbsen = $this->AbsensiModel->getAbsensiAnggota($a['nim'], $id_kegiatan)->getRowArray();
            // jika ada yang tidak absen maka kita setting alfa
            if (!$cekAbsen) {
                $this->AbsensiModel->insert([
                    'id_anggota' => $a['nim'],
                    'id_kegiatan' => $id_kegiatan,
                    'status_absen' => 3, // status 3 adalah alfa
                    'oleh' => 'Sistem',
                    'tanggal_absen' => $tgl_selesai,
                ]);
            }
        }
    }
}
