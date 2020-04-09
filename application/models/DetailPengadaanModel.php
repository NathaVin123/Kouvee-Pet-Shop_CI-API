<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DetailPengadaanModel extends CI_Model
{
    private $table = 'detailpengadaans';

    public $no_order;
    public $id_produk;
    public $jml_stok_pengadaan;
    public $status_pengadaan_produk;
    public $subTotal;

    public $rule = [
        [
            'field' => 'no_order',
            'label' => 'no_order',
            'rules' => 'required'
        ],
        [
            'field' => 'id_produk',
            'label' => 'id_produk',
            'rules' => 'required'
        ],
        [
            'field' => 'jml_stok_pengadaan',
            'label' => 'jml_stok_pengadaan',
            'rules' => 'required'
        ],
        [
            'field' => 'status_pengadaan_produk',
            'label' => 'status_pengadaan_produk',
            'rules' => 'required'
        ],
        [
            'field' => 'subTotal',
            'label' => 'subTotal',
            'rules' => 'required'
        ]
    ];

    public function Rules() { return $this->rule; }


    public function getAll() {
        return $this->db->get('detailpengadaans')->result();
    }

    public function store($request){
        $this->no_order = $request->no_order;
        $this->id_produk = $request->id_produk;
        $this->jml_stok_pengadaan = $request->jml_stok_pengadaan;
        $this->status_pengadaan_produk = $request->status_pengadaan_produk;
        $this->subTotal = $request->subTotal;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $no_order){
        $updateData = 
        ['no_order' => $request->no_order, 
        'id_produk' => $request->id_produk, 
        'jml_stok_pengadaan' => $request->jml_stok_pengadaan, 
        'status_pengadaan_produk' => $request->status_pengadaan_produk,
        'subTotal' => $request->subTotal];
        if($this->db->where('no_order', $no_order)->update($this->table, $updateData)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function destroy($no_order){
        if(empty($this->db->select('*')->where(array('no_order' => $no_order))->get($this->table)->row()))
            return ['msg' => 'Id tidak ditemukan', 'error' => true];

        if($this->db->delete($this->table, array('no_order' => $no_order))){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }
}
?>