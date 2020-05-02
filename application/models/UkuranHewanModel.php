<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UkuranHewanModel extends CI_Model
{
    private $table = 'ukuranhewans';

    public $id_ukuranHewan;
    public $nama_ukuranHewan;
    public $updateLog_by;
    public $createLog_at  = '';
    public $updateLog_at  = '';
    public $deleteLog_at  = '';

    public $rule = [
        // [
        //     'field' => 'id_ukuranHewan',
        //     'label' => 'id_ukuranHewan',
        //     'rules' => 'required'
        // ],
        [
            'field' => 'nama_ukuranHewan',
            'label' => 'nama_ukuranHewan',
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
        return $this->db->get('ukuranhewans')->result();
    }

    public function store($request){
        // $this->id_ukuranHewan = $request->id_ukuranHewan;
        $this->nama_ukuranHewan = $request->nama_ukuranHewan;
        $this->updateLog_by = $request->updateLog_by;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $id_ukuranHewan){
        $updateData = 
        [//'id_ukuranHewan' => $request->id_ukuranHewan,
         'nama_ukuranHewan' => $request->nama_ukuranHewan, 
         'updateLog_by' => $request->updateLog_by];
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