<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class JenisHewanModel extends CI_Model
{
    private $table = 'jenishewans';

    public $id_jenisHewan;
    public $nama_jenisHewan;
    public $updateLog_by;
    public $createLog_at = '';
    public $updateLog_at = '';
    public $deleteLog_at = '';

    public $rule = [
        // [
        //     'field' => 'id_jenisHewan',
        //     'label' => 'id_jenisHewan',
        //     'rules' => 'required'
        // ],
        [
            'field' => 'nama_jenisHewan',
            'label' => 'nama_jenisHewan',
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
        return $this->db->get('jenishewans')->result();
    }

    public function store($request){
        // $this->id_jenisHewan = $request->id_jenisHewan;
        $this->nama_jenisHewan = $request->nama_jenisHewan;
        $this->updateLog_by = $request->updateLog_by;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $id_jenisHewan){
        $updateData = 
        [//'id_jenisHewan' => $request->id_jenisHewan,
         'nama_jenisHewan' => $request->nama_jenisHewan, 
         'updateLog_by' => $request->updateLog_by];
        if($this->db->where('id_jenisHewan', $id_jenisHewan)->update($this->table, $updateData)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function destroy($id_jenisHewan){
        if(empty($this->db->select('*')->where(array('id_jenisHewan' => $id_jenisHewan))->get($this->table)->row()))
            return ['msg' => 'Id tidak ditemukan', 'error' => true];

        if($this->db->delete($this->table, array('id_jenisHewan' => $id_jenisHewan))){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }
}
?>