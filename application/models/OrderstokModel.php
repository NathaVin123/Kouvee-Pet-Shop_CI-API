<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orderstok extends CI_Model
{
    private $table = 'orderstocks';

    public $id_stock;
    public $id_pegawai_fk;
    public $id_hewan_fk;
    public $nama_stock;
    public $satuan_stock;
    public $tgl_pesan;
    public $tgl_Cetak;
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
        return $this->db->get('orderstocks')->result();
    }

    public function store($request){
        $this->nama_stock = $request->nama_stock;
        $this->satuan_stock = $request->satuan_stock;
        $this->tgl_pesan = $request->tgl_pesan;
        $this->tgl_Cetak = $request->tgl_Cetak;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $id_stock){
        $updateData = ['nama_stock' => $request->nama_stock, 'satuan_stock' => $request->satuan_stock, 'tgl_pesan' => $request->tgl_pesan, 'tgl_Cetak' => $request->tgl_Cetak];
        if($this->db->where('id_stock', $id_stock)->update($this->table, $updateData)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function destroy($id_stock){
        if(empty($this->db->select('*')->where(array('id_stock' => $id_stock))->get($this->table)->row()))
            return ['msg' => 'Id tidak ditemukan', 'error' => true];

        if($this->db->delete($this->table, array('id_stock' => $id_stock))){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }
}
?>