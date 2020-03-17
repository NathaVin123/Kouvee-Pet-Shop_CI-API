<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PegawaiModel extends CI_Model
{
    private $table = 'pegawais';

    public $id_pegawai;
    public $nama_pegawai;
    public $alamat_pegawai;
    public $tglLahir_pegawai;
    public $noTelp_pegawai;
    public $role_pegawai;
    public $username;
    public $password;
    public $createLog_by;
    public $updateLog_by;
    public $deleteLog_by;
    public $createLog_at;
    public $updateLog_at;
    public $deleteLog_at;

    public $rule = [
        [
            'field' => 'title',
            'label' => 'title',
            'rules' => 'required'
        ],
    ];

    public function Rules() { return $this->rule; }

    public function getAll() {
        return $this->db->get('layanans')->result();
    }

    public function store($request){
        $this->nama_pegawai = $request->nama_pegawai;
        $this->alamat_pegawai = $request->alamat_pegawai;
        $this->tglLahir_pegawai = $request->tglLahir_pegawai;
        $this->noTelp_pegawai = $request->noTelp_pegawai;
        $this->role_pegawai = $request->role_pegawai;
        $this->username = $request->username;
        $this->password = $request->password;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $id_pegawai){
        $updateData = ['nama_pegawai' => $request->nama_pegawai, 'alamat_pegawai' => $request->alamat_pegawai, 'tglLahir_pegawai' => $request->tglLahir_pegawai, 'noTelp_pegawai' => $request->noTelp_pegawai, 'role_pegawai' => $request->role_pegawai, 'username' => $request->username, 'password' => $request->password];
        if($this->db->where('id_layanan', $id_layanan)->update($this->table, $updateData)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function destroy($id_pegawai){
        if(empty($this->db->select('*')->where(array('id_pegawai' => $id_pegawai))->get($this->table)->row()))
            return ['msg' => 'Id tidak ditemukan', 'error' => true];

        if($this->db->delete($this->table, array('id_pegawai' => $id_pegawai))){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }
}
?>