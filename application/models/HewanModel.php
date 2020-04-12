<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HewanModel extends CI_Model
{
    private $table = 'hewans';

    public $id_hewan;
    public $nama_hewan;
    public $tglLahir_hewan;
    public $id_costumer;
    public $id_jenisHewan;
    public $updateLog_by;
    public $createLog_at = '';
    public $updateLog_at = '';
    public $deleteLog_at = '';

    public $rule = [
        [
            'field' => 'id_hewan',
            'label' => 'id_hewan',
            'rules' => 'required'
        ],
        [
            'field' => 'nama_hewan',
            'label' => 'nama_hewan',
            'rules' => 'required'
        ],
        [
            'field' => 'tglLahir_hewan',
            'label' => 'tglLahir_hewan',
            'rules' => 'required'
        ],
        [
            'field' => 'id_costumer',
            'label' => 'id_costumer',
            'rules' => 'required'
        ],
        [
            'field' => 'id_jenisHewan',
            'label' => 'id_jenisHewan',
            'rules' => 'required'
        ],
        [
            'field' => 'updateLog_by',
            'label' => 'updateLog_by',
            'rules' => 'required'
        ]
    ];

    public function Rules() { return $this->rule; }


    public function getAll() {
        return $this->db->get('hewans')->result();
    }

    public function store($request){
        $this->id_hewan = $request->id_hewan;
        $this->nama_hewan = $request->nama_hewan;
        $this->tglLahir_hewan = $request->tglLahir_hewan;
        $this->id_costumer = $request->id_costumer;
        $this->id_jenisHewan = $request->id_costumer;
        $this->updateLog_by = $request->updateLog_by;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $id_hewan){
        $updateData = 
        ['id_hewan' => $request->id_hewan,
         'nama_hewan' => $request->nama_hewan, 
         'tglLahir_hewan' => $request->tglLahir_hewan, 
         'id_costumer' => $request->id_costumer, 
         'id_jenisHewan' => $request->id_jenisHewan,
         'updateLog_by' => $request->updateLog_by];
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