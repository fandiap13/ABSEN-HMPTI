<?php

namespace App\Models;

use CodeIgniter\Model;

class MemberModel extends Model
{
    protected $table            = 'h_member';
    protected $primaryKey       = 'nim';
    protected $allowedFields    = [];

    public function getAnggota($id = null)
    {
        if ($id !== null) {
            return $this->table($this->table)->select($this->table . ".*, h_jabatan.nama_jabatan, h_divisi.nama_divisi")
                ->join('h_jabatan', $this->table . ".id_jabatan=h_jabatan.id_jabatan")
                ->join('h_divisi', "h_jabatan.id_divisi=h_divisi.id_divisi")
                ->where($this->table . ".nim", $id)
                ->where('aktif', 1)->get();
        }
        return $this->table($this->table)->select($this->table . ".*, h_jabatan.nama_jabatan, h_divisi.nama_divisi")
            ->join('h_jabatan', $this->table . ".id_jabatan=h_jabatan.id_jabatan")
            ->join('h_divisi', "h_jabatan.id_divisi=h_divisi.id_divisi")
            ->where('aktif', 1)
            ->orderBy($this->table . ".nama", 'ASC')->get();
    }

    public function getAnggotaEmail($email)
    {
        return $this->table($this->table)->select($this->table . ".*, h_jabatan.nama_jabatan, h_divisi.nama_divisi")
            ->join('h_jabatan', $this->table . ".id_jabatan=h_jabatan.id_jabatan")
            ->join('h_divisi', "h_jabatan.id_divisi=h_divisi.id_divisi")
            ->where($this->table . ".email", $email)
            ->where('aktif', 1)->get();
    }
}
