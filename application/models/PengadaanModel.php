<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class PengadaanModel extends CI_Model
{
    private $table = 'pengadaans';

    public $no_order;
    public $id_supplier;
    public $total_harga;
    public $status_pengadaan;
    public $createLog_at;
    public $updateLog_at;

    public $rule = [];

    public function Rules() { return $this->rule; }

    public function getAllAktif() {
        return $this->db->get('pengadaans')->result();
    } 

    public function store($request){
        $date_now = date('Y-m-d');
        $this->db->select_max('no_order');
        $this->db->like('no_order', 'PO-'.$date_now, 'after');
        $query = $this->db->get('pengadaans');
        $lastdata = $query->row();
        $last_id = $lastdata->no_order;
        $last_count = substr($last_id, 14, 2);
        $next_count = $last_count+1;
        $next_id = 'PO-'.$date_now.'-'.sprintf('%02s', $next_count);

        $this->no_order = $next_id;
        $this->id_supplier = $request->id_supplier;
        $this->total_harga = $request->total_harga;
        $this->status_pengadaan = 'Belum Datang';
        if($this->db->insert($this->table, $this)){
            //$temp = $this->updateTotal($next_id, $request->diskon);
            return ['msg'=>$next_id,'error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function storeReturnObject($request) { 
        $date_now = date('Y-m-d');
        $this->db->select_max('no_order');
        $this->db->like('no_order', 'PO-'.$date_now, 'after');
        $query = $this->db->get('pengadaans');
        $lastdata = $query->row();
        $last_id = $lastdata->no_order;
        $last_count = substr($last_id, 14, 2);
        $next_count = $last_count+1;
        $next_id = 'PO-'.$date_now.'-'.sprintf('%02s', $next_count);

        $this->no_order = $next_id;
        $this->id_supplier = $request->id_supplier;
        $this->total_harga = $request->total_harga;
        $this->status_pengadaan = 'Belum Datang';
        if($this->db->insert($this->table, $this)){
            //$temp = $this->updateTotal($next_id, $request->diskon);
            $obj = $this->db->get_where('pengadaans', ["no_order" => $next_id])->row();
            return ['msg'=>$obj,'error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function update($request, $no_order){
        $updateData = 
        ['no_order' => $request->no_order, 
        'id_supplier' => $request->id_supplier, 
        'status_pengadaan' => $request->status_pengadaan, 
        'total_harga' => $request->total_harga, 
        'updateLog_at' => date('Y-m-d H:i:s'),
        ];
        if($this->db->where('no_order', $no_order)->update($this->table, $updateData)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function updateStatusToProses($request, $no_order) {
        $updateData = [
            'status_pengadaan' => 'Pesanan Diproses',
            'updateLog_at' => date('Y-m-d H:i:s'),
        ];

        $data = $this->db->get_where('pengadaans',['no_order'=>$no_order, 'status_pengadaan'=> 'Belum Datang'])->row();
        if($data!=null){
            $this->db->where(['no_order'=>$no_order, 'status_pengadaan'=> 'Belum Datang'])->update($this->table, $updateData);
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function updateStatusToSelesai($request, $no_order) {
        $updateData = [
            'status_pengadaan' => 'Pesanan Selesai',
            'updateLog_at' => date('Y-m-d H:i:s'),
        ];

        $data = $this->db->get_where('pengadaans',['no_order'=>$no_order, 'status_pengadaan'=> 'Pesanan Diproses'])->row();
        if($data!=null){
            $this->db->trans_start();
            $this->db->where(['no_order'=>$no_order, 'status_pengadaan'=> 'Pesanan Diproses'])->update($this->table, $updateData);
            $detail = $this->db->get_where('detailpengadaans', array('no_order' => $no_order))->result();
            foreach ($detail as $item) {
                $this->tambahStokProduk($item->id_produk,$item->stok_produk);
            }
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                # Something went wrong.
                $this->db->trans_rollback();
                return ['msg'=>'Gagal','error'=>true];
            } 
            else {
                # Everything is Perfect. 
                # Committing data to the database.
                $this->db->trans_commit();
                return ['msg'=>'Berhasil','error'=>false];
            }
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function updateTotal($no_order) {
        $this->db->select_sum('total_harga');
        $this->db->where('no_order', $no_order);
        $pricedata = $this->db->get('detailpengadaans')->row();

        $updateData = [
            'total' => $pricedata->total_harga
        ];
        
        $this->db->where('no_order',$no_order)->update($this->table, $updateData);
    }

    public function destroy($no_order){
        if (empty($this->db->select('*')->where(['no_order' => $no_order, 'status_pengadaan' => 'Belum Datang'])->get($this->table)->row())) 
            return ['msg'=>'Id tidak ditemukan','error'=>true];
        
        $data = $this->db->get_where($this->table, ['no_order' => $no_order, 'status_pengadaan' => 'Belum Datang'])->row();
        $detail = $this->db->get_where('detailpengadaans', array('no_order' => $no_order))->result();
        if($data!=null && $data->no_order==$no_order){
            $this->db->trans_start();
            $this->db->delete('detailpengadaans', ['no_order' => $no_order]);
            $this->db->delete($this->table, ['no_order' => $no_order]);
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                # Something went wrong.
                $this->db->trans_rollback();
                return ['msg'=>'Gagal','error'=>true];
            } 
            else {
                # Everything is Perfect. 
                # Committing data to the database.
                $this->db->trans_commit();
                return ['msg'=>'Berhasil','error'=>false];
            }
            
        }
        return ['msg'=>'Id tidak ditemukan','error'=>true];
    }

    public function tambahStokProduk($id_produk, $qty){
        $data = $this->db->get_where('produks', array('id_produk' => $id_produk))->row();
        $new_sum = $data->stok_produk+$qty;
        $updateData = [
            'stok_produk' => $new_sum
        ];
        $this->db->where('id_produk',$data->id_produk)->update('produks', $updateData);
    }
}
?>