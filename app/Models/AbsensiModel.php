<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsensiModel extends Model
{
    protected $table            = 'h_absensi';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['id_anggota', 'id_kegiatan', 'tanggal_absen', 'status_absen', 'oleh'];

    public function getAbsensiAnggota($id_anggota, $id_kegiatan)
    {
        return $this->table($this->table)->select($this->table . ".*, h_member.nama")
            ->join('h_member', $this->table . ".id_anggota=h_member.nim")
            ->getWhere([
                'id_anggota' => $id_anggota,
                'id_kegiatan' => $id_kegiatan,
            ]);
    }

    public function getKegiatanAnggota($id_anggota)
    {
        return $this->table($this->table)->select($this->table . ".*, h_kegiatan.*, h_anggota.nama, h_sekertaris.nama as nama_sekertaris")
            ->join('h_kegiatan', $this->table . ".id_kegiatan=h_kegiatan.id")
            ->join('h_member as h_anggota', $this->table . ".id_anggota=h_anggota.nim")
            ->join('h_member as h_sekertaris', "h_kegiatan.id_sekertaris=h_sekertaris.nim")
            ->where('id_anggota', $id_anggota)
            ->orderBy('tgl_kegiatan', 'ASC')->get();
    }

    public function getKegiatanAnggotaPagination($id_anggota, $limit, $offset)
    {
        return $this->table($this->table)->select($this->table . ".*, h_kegiatan.*, h_anggota.nama, h_sekertaris.nama as nama_sekertaris")
            ->join('h_kegiatan', $this->table . ".id_kegiatan=h_kegiatan.id")
            ->join('h_member as h_anggota', $this->table . ".id_anggota=h_anggota.nim")
            ->join('h_member as h_sekertaris', "h_kegiatan.id_sekertaris=h_sekertaris.nim")
            ->where('id_anggota', $id_anggota)
            ->orderBy('tgl_kegiatan', 'ASC')
            ->limit($limit, $offset)->get();
    }

    public function getScanAbsensi($id_anggota, $kode_unik)
    {
        return $this->table($this->table)->select($this->table . ".*, h_kegiatan.kode_unik")
            ->join('h_kegiatan', $this->table . ".id_kegiatan=h_kegiatan.id")
            ->getWhere([
                'h_absensi.id_anggota' => $id_anggota,
                'h_kegiatan.kode_unik' => $kode_unik,
            ]);
    }
}
