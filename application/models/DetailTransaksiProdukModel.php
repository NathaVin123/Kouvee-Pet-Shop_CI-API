<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class DetailTransaksiProdukModel extends CI_Model
{
    private $table = 'detailtransaksiproduks';

    public $id_detailproduk;
    public $kode_penjualan_produk;
    public $id_produk;
    public $jml_transaksi_produk;
    public $createLog_at;
    public $updateLog_at;

    public $rule = [];

    public function Rules() { return $this->rule; }

    public function getAllAktif() {
        return $this->db->get('detailtransaksiproduks')->result();
    }

    public function store($request){
        $this->kode_penjualan_produk = $request->kode_penjualan_produk;
        $this->id_produk = $request->id_produk;
        $this->jml_transaksi_produk = $request->jml_transaksi_produk;
        $this->total_harga = $request->total_harga;
        if($this->db->insert($this->table, $this)){
            $this->kurangStokProduk($this->id_produk, $this->jml_transaksi_produk);
            $this->updateTotal($request->kode_penjualan_produk);
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function storeMultiple($request) {
        $jsondata = json_decode($request);
        $dataset = array();
        $kode_penjualan_produk = 0;
        foreach($jsondata as $data){
            $kode_penjualan_produk = $data->id_detailproduk;
            $dataset[] = 
                array(
                    'kode_penjualan_produk' => $data->kode_penjualan_produk,
                    'id_produk' => $data->id_produk,
                    'jml_transaksi_produk' => $data->jml_transaksi_produk,
                    'total_harga' => $data->total_harga,
                );
        }
        //echo count($dataset);
        if($this->db->insert_batch($this->table, $dataset)){
            foreach($jsondata as $data){
                $this->kurangStokProduk($data->id_produk, $data->jml_transaksi_produk);
            }
            $this->updateTotal($kode_penjualan_produk);
            return ['msg'=>'Berhasil','error'=>false];
        }
        //$this->db->delete('transaksipenjualanproduks', array('kode_penjualan_produk' => $kode_penjualan_produk));
        return ['msg'=>'Gagal','error'=>true];
    }

    public function update($request, $id_detailproduk){
        $updateData = 
        [
        'id_produk' => $request->id_produk, 
        'jml_transaksi_produk' => $request->jml_transaksi_produk, 
        'total_harga' => $request->total_harga,
        'updateLog_at' => date('Y-m-d H:i:s'),
        ];
        $data = $this->db->get_where($this->table, array('id_detailproduk' => $id_detailproduk))->row();
        $new_sum_change = $request->jml_transaksi_produk-$data->jml_transaksi_produk;
        if($this->db->where('id_detailproduk',$id_detailproduk)->update($this->table, $updateData)){
            $this->kurangStokProduk($data->id_produk, $new_sum_change);
            $this->updateTotal($data->kode_penjualan_produk);
            return ['msg'=>'Berhasil','error'=>false];
        }
        $this->updateTotal($data->kode_penjualan_produk);
        return ['msg'=>'Gagal','error'=>true];
    }

    public function updateMultiple($request) {
        $jsondata = json_decode($request);
        $kode_penjualan_produk = 0;
        $this->db->trans_start();
        foreach($jsondata as $data){
            $id_detailproduk = $data->id_detailproduk;
            $kode_penjualan_produk = $data->kode_penjualan_produk;
            $updateData = [
                'id_produk' => $data->id_produk,
                'jml_transaksi_produk' => $data->jml_transaksi_produk,
                'total_harga' => $data->total_harga,
                'updateLog_at' => date('Y-m-d H:i:s'),
            ];
            $data_before = $this->db->get_where($this->table, array('id_detailproduk' => $id_detailproduk))->row();
            $new_sum_change = $data->jml_transaksi_produk-$data_before->jml_transaksi_produk;
            $this->db->where('id_detailproduk',$id_detailproduk)->update($this->table, $updateData);
            $this->kurangStokProduk($data->id_produk, $new_sum_change);
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            $this->updateTotal($kode_penjualan_produk);
            return ['msg'=>'Gagal','error'=>true];
        } 
        else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
            $this->updateTotal($kode_penjualan_produk);
            return ['msg'=>'Berhasil','error'=>false];
        }
    }

    public function updateTotal($kode_penjualan_produk) {
        $transdata = $this->db->get_where('transaksipenjualanproduks', ['kode_penjualan_produk'=>$kode_penjualan_produk])->row();
        $this->db->select_sum('total_harga');
        $this->db->where('kode_penjualan_produk', $kode_penjualan_produk);
        $pricedata = $this->db->get('detailtransaksiproduks')->row();
        if($pricedata->total_harga==null || $pricedata->total_harga<=$transdata->diskon)
        {
            $updateData = [
                'subtotal' => $pricedata->total_harga, 
                'total' => 0
            ];
            if($pricedata->total_harga==null){
                $updateData['subtotal'] = 0;
            }
        }else{
            if($transdata->diskon==null){
                $updateData = [
                    'subtotal' => $pricedata->total_harga, 
                    'total' => $pricedata->total_harga
                ];
            }else{
                $updateData = [
                    'subtotal' => $pricedata->total_harga, 
                    'total' => $pricedata->total_harga-$transdata->diskon
                ];
            }
        }
        $this->db->where('kode_penjualan_produk',$kode_penjualan_produk)->update('transaksipenjualanproduks', $updateData);
    }

    public function destroy($id_detailproduk){
        if (empty($this->db->select('*')->where(array('id_detailproduk' => $id_detailproduk))->get($this->table)->row())) 
            return ['msg'=>'Id tidak ditemukan','error'=>true];
        
        $data = $this->db->get_where($this->table, array('id_detailproduk' => $id_detailproduk))->row();
        if($data!=null && $data->id_detailproduk==$id_detailproduk){
            if($this->db->delete($this->table, array('id_detailproduk' => $id_detailproduk))){
                $this->tambahStokProduk($data->id_produk, $data->jumlah);
                $this->updateTotal($data->id_transaksi_produk);
                return ['msg'=>'Berhasil','error'=>false];
            }
            $this->updateTotal($data->id_transaksi_produk);
            return ['msg'=>'Gagal','error'=>true];
        }
        $this->updateTotal($data->id_transaksi_produk);
        return ['msg'=>'Id tidak ditemukan','error'=>true];
    }

    public function deleteMultiple($request){
        $jsondata = json_decode($request);

        $data_transaksi = $this->db->get_where($this->table, array('id_detailproduk' => $jsondata[0]))->row();
        $kode_penjualan_produk = $data_transaksi->kode_penjualan_produk;

        $this->db->select('*');
        $this->db->from('detailtransaksiproduks');
        $this->db->where_in('id_detailproduk', $jsondata);
        $result = $this->db->get()->result();
        if($this->db->where_in('id_detailproduk', $jsondata)->delete($this->table)){
            //add jml_transaksi_produk produks
            foreach($result as $trans){
                $this->tambahStokProduk($trans->id_produk, $trans->jml_transaksi_produk);
            }
            $this->updateTotal($kode_penjualan_produk);
            return ['msg'=>'Berhasil','error'=>false];
        } 
        $this->updateTotal($kode_penjualan_produk);
        return ['msg'=>'Gagal','error'=>true];
    }
    
    public function kurangStokProduk($id_produk, $qty){
        $data = $this->db->get_where('produks', array('id_produk' => $id_produk))->row();
        $new_sum = $data->jumlah_stok-$qty;
        $updateData = [
            'jumlah_stok' => $new_sum
        ];
        $this->db->where('id_produk',$data->id_produk)->update('produks', $updateData);
    }

    public function tambahStokProduk($id_produk, $qty){
        $data = $this->db->get_where('produks', array('id_produk' => $id_produk))->row();
        $new_sum = $data->jumlah_stok+$qty;
        $updateData = [
            'jumlah_stok' => $new_sum
        ];
        $this->db->where('id_produk',$data->id_produk)->update('produks', $updateData);
    }
}
?>