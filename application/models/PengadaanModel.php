<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PengadaanModel extends CI_Model
{
    private $table = 'pengadaans';

    public $no_order;
    public $tgl_pesan;
    public $tgl_Cetak;
    public $nama_stock;
    public $satuan_stock;
    public $id_supplier;
    public $status_pengadaan;
    public $total_harga;
    public $createLog_at;
    public $updateLog_at;

    public $rule = [
        [
            'field' => 'no_order',
            'label' => 'no_order',
            'rules' => 'required'
        ],
        [
            'field' => 'tgl_pesan',
            'label' => 'tgl_pesan',
            'rules' => 'required'
        ],
        [
            'field' => 'tgl_Cetak',
            'label' => 'tgl_Cetak',
            'rules' => 'required'
        ],
        [
            'field' => 'nama_stock',
            'label' => 'nama_stock',
            'rules' => 'required'
        ],
        [
            'field' => 'satuan_stock',
            'label' => 'satuan_stock',
            'rules' => 'required'
        ],
        [
            'field' => 'satuan_stock',
            'label' => 'satuan_stock',
            'rules' => 'required'
        ],
        [
            'field' => 'id_supplier',
            'label' => 'id_supplier',
            'rules' => 'required'
        ],
        [
            'field' => 'status_pengadaan',
            'label' => 'status_pengadaan',
            'rules' => 'required'
        ],
        [
            'field' => 'total_harga',
            'label' => 'total_harga',
            'rules' => 'required'
        ],
    ];

    public function Rules() { return $this->rule; }


    public function getAll() {
        return $this->db->get('pengadaans')->result();
    }

    public function store($request){
        $this->no_order = $request->no_order;
        $this->tgl_pesan = $request->tgl_pesan;
        $this->tgl_Cetak = $request->tgl_Cetak;
        $this->nama_stock = $request->nama_stock;
        $this->satuan_stock = $request->satuan_stock;
        $this->id_supplier = $request->id_supplier;
        $this->status_pengadaan = $request->status_pengadaan;
        $this->total_harga = $request->total_harga;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $no_order){
        $updateData = 
        ['no_order' => $request->no_order, 
        'tgl_pesan' => $request->tgl_pesan, 
        'tgl_Cetak' => $request->tgl_Cetak, 
        'nama_stock' => $request->nama_stock,
        'satuan_stock' => $request->satuan_stock,
        'id_supplier' => $request->id_supplier,
        'status_pengadaan' => $request->status_pengadaan,
        'total_harga' => $request->total_harga,];
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