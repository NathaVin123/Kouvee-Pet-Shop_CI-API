<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class DetailPengadaanModel extends CI_Model
{
    private $table = 'detailpengadaans';

    public $id_detailpengadaan;
    public $no_order;
    public $id_produk;
    public $jumlah_stok_pengadaan;
    public $harga;
    public $total_harga;
    

    public $rule = [];

    public function Rules() { return $this->rule; }
   
    public function getAllAktif() {
        return $this->db->get('detailpengadaans')->result();
    } 

    public function store($request) {
        $this->no_order = $request->no_order;
        $this->id_produk = $request->id_produk;
        $this->jumlah_stok_pengadaan = $request->jumlah_stok_pengadaan;
        $this->harga = $request->harga;
        $this->total_harga = $request->jumlah_stok_pengadaan*$request->harga;
        
        if($this->db->insert($this->table, $this)){
            $this->updateTotal($request->no_order);
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function storeMultiple($request) {
        $jsondata = json_decode($request);
        $dataset = array();
        $no_order = 0;
        foreach($jsondata as $data){
            $no_order = $data->no_order;
            $dataset[] = 
                array(
                    'no_order' => $data->no_order,
                    'id_produk' => $data->id_produk,
                    'jumlah_stok_pengadaan' => $data->jumlah_stok_pengadaan,
                    'harga' => $data->harga,
                    'total_harga' => $data->jumlah_stok_pengadaan*$data->harga
                );
        }
        //echo count($dataset);
        if($this->db->insert_batch($this->table, $dataset)){
            $this->updateTotal($no_order);
            return ['msg'=>'Berhasil','error'=>false];
        }
        //$this->db->delete('transaksi_produk', array('id_transaksi_produk' => $id_transaksi_produk));
        return ['msg'=>'Gagal','error'=>true];
    }

    public function update($request, $id_detailpengadaan) {
        $updateData = [
            'id_produk' => $request->id_produk,
            'jumlah_stok_pengadaan' => $request->jumlah_stok_pengadaan,
            'harga' => $request->harga,
            'total_harga' => $request->jumlah_stok_pengadaan*$request->harga,
            
        ];
        $data = $this->db->get_where($this->table, array('id_detailpengadaan' => $id_detailpengadaan))->row();
        if($this->db->where('id_detailpengadaan',$id_detailpengadaan)->update($this->table, $updateData)){
            $this->updateTotal($data->no_order);
            return ['msg'=>'Berhasil','error'=>false];
        }
        $this->updateTotal($data->no_order);
        return ['msg'=>'Gagal','error'=>true];
    }

    public function updateMultiple($request) {
        $jsondata = json_decode($request);
        $no_order = 0;
        $this->db->trans_start();
        foreach($jsondata as $data){
            $id_detailpengadaan = $data->id_detailpengadaan;
            $no_order = $data->no_order;
            $updateData = [
                'id_produk' => $data->id_produk,
                'jumlah_stok_pengadaan' => $data->jumlah_stok_pengadaan,
                'harga' => $data->harga,
                'total_harga' => $data->jumlah_stok_pengadaan*$data->harga,
                
                
            ];
            $this->db->where('id_detailpengadaan',$id_detailpengadaan)->update($this->table, $updateData);
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            $this->updateTotal($no_order);
            return ['msg'=>'Gagal','error'=>true];
        } 
        else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
            $this->updateTotal($no_order);
            return ['msg'=>'Berhasil','error'=>false];
        }
    }

    public function updateTotal($no_order) {
        //$transdata = $this->db->get_where('transaksi_produk', ['id_transaksi_produk'=>$id_transaksi_produk])->row();
        $this->db->select_sum('total_harga');
        $this->db->where('no_order', $no_order);
        $pricedata = $this->db->get('detailpengadaans')->row();

        $updateData = [
            'total' => $pricedata->total_harga
        ];
        
        $this->db->where('no_order',$no_order)->update('pengadaans', $updateData);
    }
    
    public function destroy($id){
        if (empty($this->db->select('*')->where(array('id_detailpengadaan' => $id))->get($this->table)->row())) 
            return ['msg'=>'Id tidak ditemukan','error'=>true];
        
        $data = $this->db->get_where($this->table, array('id_detailpengadaan' => $id))->row();
        if($data!=null && $data->id_detailpengadaan==$id){
            if($this->db->delete($this->table, array('id_detailpengadaan' => $id))){
                $this->updateTotal($data->no_order);
                return ['msg'=>'Berhasil','error'=>false];
            }
            $this->updateTotal($data->no_order);
            return ['msg'=>'Gagal','error'=>true];
        }
        $this->updateTotal($data->no_order);
        return ['msg'=>'Id tidak ditemukan','error'=>true];
    }
    
    public function deleteMultiple($request){
        $jsondata = json_decode($request);

        $data_transaksi = $this->db->get_where($this->table, array('id_detailpengadaan' => $jsondata[0]))->row();
        $no_order = $data_transaksi->no_order;

        if($this->db->where_in('id_detailpengadaan', $jsondata)->delete($this->table)){
            $this->updateTotal($no_order);
            return ['msg'=>'Berhasil','error'=>false];
        } 
        $this->updateTotal($no_order);
        return ['msg'=>'Gagal','error'=>true];
    }
}
?>