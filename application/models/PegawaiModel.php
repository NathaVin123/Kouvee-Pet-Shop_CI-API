<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class PegawaiModel extends CI_Model
{
    private $table = 'pegawais';

    public $NIP;
    public $nama_pegawai;
    public $alamat_pegawai;
    public $tglLahir_pegawai;
    public $noTelp_pegawai;
    public $stat;
    public $password;
    public $gambar;
    public $updateLog_by;
    public $createLog_at;
    public $updateLog_at;
    public $deleteLog_at;
    public $aktif;

    public $rule = [];

    public function Rules() { return $this->rule; }

    public function getAllAktif() {
        return $this->db->get_where('pegawais', ["aktif" => 1])->result();
    }

    public function getAllNonAktif() {
        return $this->db->get_where('pegawais', ["aktif" => 0])->result();
    }

    public function store($request){
        $this->NIP = $request->NIP;
        $this->nama_pegawai = $request->nama_pegawai;
        $this->alamat_pegawai = $request->alamat_pegawai;
        $this->tglLahir_pegawai = $request->tglLahir_pegawai;
        $this->noTelp_pegawai = $request->noTelp_pegawai;
        $this->stat = $request->stat;
        $this->password = password_hash($request->password, PASSWORD_BCRYPT);
        // $this->gambar = $this->uploadImage();
        $this->gambar = $request->gambar;
        $this->updateLog_by = $request->updateLog_by;
        $this->aktif=1;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $NIP){
        $updateData = 
        ['NIP' => $request->NIP, 
        'nama_pegawai' => $request->nama_pegawai, 
        'alamat_pegawai' => $request->alamat_pegawai, 
        'tglLahir_pegawai' => $request->tglLahir_pegawai, 
        'noTelp_pegawai' => $request->noTelp_pegawai, 
        'stat' => $request->stat,
        'password' => $request->password,
        'updateLog_by' => $request->updateLog_by,
        'updateLog_at' => date('Y-m-d H:i:s')];

        if($this->db->where('NIP', $NIP)->update($this->table, $updateData)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function softDelete($request, $NIP){
        $updateData = [
            'aktif' => 0,
            'delete_at' => date('Y-m-d H:i:s')
        ];
        if($this->db->where('NIP',$NIP)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    // public function destroy($NIP){
    //     if(empty($this->db->select('*')->where(array('NIP' => $NIP))->get($this->table)->row()))
    //         return ['msg' => 'Id tidak ditemukan', 'error' => true];

    //     if($this->db->delete($this->table, array('NIP' => $NIP))){
    //         return ['msg' => 'Berhasil', 'error' => false];
    //     }
    //     return ['msg' => 'Gagal', 'error' => true];
    // }

}
?>