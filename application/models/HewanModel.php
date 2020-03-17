<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HewanModel extends CI_Model
{
    private $table = 'hewans';

    public $id_hewan;
    public $nama_hewan;
    public $tglLahir_hewan;
    public $nama_customer;
    public $nama_cs;
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
        $this->nama_hewan = $request->nama_hewan;
        $this->tglLahir_hewan = $request->tglLahir_hewan;
        $this->nama_customer = $request->nama_customer;
        $this->nama_cs = $request->nama_cs;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $id_hewan){
        $updateData = ['nama_hewan' => $request->nama_hewan, 'tglLahir_hewan' => $request->tglLahir_hewan, 'nama_customer' => $request->nama_customer, 'nama_cs' => $request->nama_cs];
        if($this->db->where('id_hewan', $id_hewan)->update($this->table, $updateData)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function destroy($id_hewan){
        if(empty($this->db->select('*')->where(array('id_hewan' => $id_hewan))->get($this->table)->row()))
            return ['msg' => 'Id tidak ditemukan', 'error' => true];

        if($this->db->delete($this->table, array('id_hewan' => $id_hewan))){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }
}
?>