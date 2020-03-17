<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class JenisHewan extends CI_Model
{
    private $table = 'jenishewans';

    public $id_jenisHewan;
    public $id_pegawai_fk;
    public $nama_jenisHewan;
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
        return $this->db->get('hewans')->result();
    }

    public function store($request){
        $this->nama_jenisHewan = $request->nama_jenisHewan;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $id_hewan){
        $updateData = ['nama_jenisHewan' => $request->nama_jenisHewan];
        if($this->db->where('id_jenisHewan', $id_jenisHewan)->update($this->table, $updateData)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function destroy($id_hewan){
        if(empty($this->db->select('*')->where(array('id_jenisHewan' => $id_jenisHewan))->get($this->table)->row()))
            return ['msg' => 'Id tidak ditemukan', 'error' => true];

        if($this->db->delete($this->table, array('id_jenisHewan' => $id_jenisHewan))){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }
}
?>