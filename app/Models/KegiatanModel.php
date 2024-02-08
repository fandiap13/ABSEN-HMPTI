<?php

namespace App\Models;

use CodeIgniter\Model;

class KegiatanModel extends Model
{
    protected $table            = 'h_kegiatan';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nama_kegiatan', 'tgl_kegiatan', 'tgl_selesai', 'keterangan', 'kode_unik', 'id_sekertaris', 'status_kegiatan'];

    public function getKegiatan($id = null)
    {
        if ($id !== null) {
            return $this->table($this->table . ".*, h_member.nama")
                ->join('h_member', $this->table . '.id_sekertaris=h_member.nim')
                ->orderBy('tgl_kegiatan', 'DESC')
                ->where('h_kegiatan.id', $id)->get();
        }
        return $this->table($this->table . ".*, h_member.nama")
            ->join('h_member', $this->table . '.id_sekertaris=h_member.nim')
            ->orderBy('tgl_kegiatan', 'DESC')->get();
    }

    public function getKegiatanByTanggal($tgl_mulai, $tgl_selesai)
    {
        return $this->table($this->table . ".*, h_member.nama")
            ->join('h_member', $this->table . '.id_sekertaris=h_member.nim')
            ->where("DATE_FORMAT(tgl_kegiatan, '%Y-%m-%d') BETWEEN '" . $tgl_mulai . "' and '" . $tgl_selesai . "'")
            ->orderBy('tgl_kegiatan', 'DESC')->get();
    }
}
