<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UkuranHewanModel extends CI_Model
{
    private $table = 'ukuranhewans';

    public $id_ukuranHewan;
    public $id_pegawai_fk;
    public $nama_ukuranHewan;
    public $cretaeLog_by;
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
        return $this->db->get('ukuranhewans')->result();
    }

    public function store($request){
        $this->nama_ukuranHewan = $request->nama_ukuranHewan;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $id_ukuranHewan){
        $updateData = ['nama_ukuranHewan' => $request->nama_ukuranHewan];
        if($this->db->where('id_ukuranHewan', $id_ukuranHewan)->update($this->table, $updateData)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function destroy($id_ukuranHewan){
        if(empty($this->db->select('*')->where(array('id_ukuranHewan' => $id_ukuranHewan))->get($this->table)->row()))
            return ['msg' => 'Id tidak ditemukan', 'error' => true];

        if($this->db->delete($this->table, array('id_ukuranHewan' => $id_ukuranHewan))){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }
}
?>