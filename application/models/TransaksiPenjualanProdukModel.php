<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class TransaksiPenjualanProdukModel extends CI_Model
{
    private $table = 'transaksipenjualanproduks';

    public $kode_penjualan_produk;
    public $id_hewan;
    public $id_cs;
    public $id_kasir;
    public $subtotal;
    public $diskon;
    public $total;
    public $status_transaksi;
    public $tanggal_lunas;
    public $createLog_at;
    public $updateLog_at;

    public $rule = [];

    public function Rules() { return $this->rule; }
   
    public function getAllAktif() {
        return $this->db->get('transaksipenjualanproduks')->result();
    } 

    public function store($request) { 
        $date_now = date('dmy');
        $this->db->select_max('kode_penjualan_produk');
        $this->db->like('kode_penjualan_produk', 'PR-'.$date_now, 'after');
        $query = $this->db->get('transaksipenjualanproduks');
        $lastdata = $query->row();
        $last_id = $lastdata->kode_penjualan_produk;
        $last_count = substr($last_id, 10, 2);
        $next_count = $last_count+1;
        $next_id = 'PR-'.$date_now.'-'.sprintf('%02s', $next_count);

        $this->kode_penjualan_produk = $next_id;
        $this->id_cs = $request->id_cs;
        $this->id_hewan = $request->id_hewan;
        $this->subtotal = $request->subtotal;
        $this->diskon = $request->diskon;
        $this->total = $request->total;
        $this->status_transaksi = 'Menunggu Pembayaran';
        if($this->db->insert($this->table, $this)){
            //$temp = $this->updateTotal($next_id, $request->diskon);
            return ['msg'=>$next_id,'error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function storeReturnObject($request) { 
        $date_now = date('dmy');
        $this->db->select_max('kode_penjualan_produk');
        $this->db->like('kode_penjualan_produk', 'PR-'.$date_now, 'after');
        $query = $this->db->get('transaksipenjualanproduks');
        $lastdata = $query->row();
        $last_id = $lastdata->kode_penjualan_produk;
        $last_count = substr($last_id, 10, 2);
        $next_count = $last_count+1;
        $next_id = 'PR-'.$date_now.'-'.sprintf('%02s', $next_count);

        $this->kode_penjualan_produk = $next_id;
        $this->id_cs = $request->id_cs;
        $this->id_hewan = $request->id_hewan;
        $this->subtotal = $request->subtotal;
        $this->diskon = $request->diskon;
        $this->total = $request->total;
        $this->status_transaksi = 'Menunggu Pembayaran';
        if($this->db->insert($this->table, $this)){
            //$temp = $this->updateTotal($next_id, $request->diskon);
            $obj = $this->db->get_where('transaksipenjualanproduks', ["kode_penjualan_produk" => $next_id])->row();
            return ['msg'=>$obj,'error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function update($request, $kode_penjualan_produk) {
        $updateData = [
            'id_hewan' => $request->id_hewan,
            'subtotal' => $request->subtotal,
            'diskon' => $request->diskon,
            'total' => $request->total,
            'updateLog_at' => date('Y-m-d H:i:status_transaksi')
        ];
        $data = $this->db->get_where('transaksipenjualanproduks',['kode_penjualan_produk'=>$kode_penjualan_produk, 'status_transaksi'=> 'Menunggu Pembayaran'])->row();
        if($data){
            $this->db->where(['kode_penjualan_produk'=>$kode_penjualan_produk, 'status_transaksi'=> 'Menunggu Pembayaran'])->update($this->table, $updateData);
            //$temp = $this->updateTotal($kode_penjualan_produk, $request->diskon);
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function updateStatus($request, $kode_penjualan_produk) {
        $updateData = [
            'id_kasir' => $request->id_kasir,
            'status_transaksi' => 'Lunas',
            'tanggal_lunas' => date('Y-m-d H:i:status_transaksi'),
            'updateLog_at' => date('Y-m-d H:i:status_transaksi')
        ];

        $data = $this->db->get_where('transaksipenjualanproduks',['kode_penjualan_produk'=>$kode_penjualan_produk, 'status_transaksi'=> 'Menunggu Pembayaran'])->row();
        if($data!=null){
            $this->db->where(['kode_penjualan_produk'=>$kode_penjualan_produk, 'status_transaksi'=> 'Menunggu Pembayaran'])->update('transaksipenjualanproduks', $updateData);
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function updateTotal($kode_penjualan_produk, $diskon) {
        //$transdata =$this->db->get_where('transaksipenjualanproduks', ['kode_penjualan_produk'=>$kode_penjualan_produk])->row();
        $this->db->select_sum('total_harga');
        $this->db->where('kode_penjualan_produk', $kode_penjualan_produk);
        $pricedata = $this->db->get('detailtransaksiproduks')->row();
        if($pricedata->total_harga==null || $pricedata->total_harga<=$diskon)
        {
            $updateData = [
                'subtotal' => $pricedata->total_harga, 
                'total' => 0
            ];
            if($pricedata->total_harga==null){
                $updateData['subtotal'] = 0;
            }
        }else{
            if($diskon==null){
                $updateData = [
                    'subtotal' => $pricedata->total_harga, 
                    'total' => $pricedata->total_harga
                ];
            }else{
                $updateData = [
                    'subtotal' => $pricedata->total_harga, 
                    'total' => $pricedata->total_harga-$diskon
                ];
            }
        }
        
        if($this->db->where('kode_penjualan_produk',$kode_penjualan_produk)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    
    public function destroy($id){
        if (empty($this->db->select('*')->where(array('kode_penjualan_produk' => $id))->get($this->table)->row())) 
            return ['msg'=>'Id tidak ditemukan','error'=>true];
        
        $data = $this->db->get_where($this->table, array('kode_penjualan_produk' => $id))->row();
        $detail = $this->db->get_where('detailtransaksiproduks', array('kode_penjualan_produk' => $id))->result();
        if($data!=null && $data->kode_penjualan_produk==$id){
            $this->db->trans_start();
            foreach($detail as $item){
                $this->tambahStokProduk($item->id_produk, $item->jumlah);
            }
            $this->db->delete('detailtransaksiproduks', ['kode_penjualan_produk' => $id]);
            $this->db->delete($this->table, ['kode_penjualan_produk' => $id]);
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

    public function kurangStokProduk($id_produk, $qty){
        $data = $this->db->get_where('produks', array('id_produk' => $id_produk))->row();
        $new_sum = $data->stok_produk-$qty;
        $updateData = [
            'stok_produk' => $new_sum
        ];
        $this->db->where('id_produk',$data->id_produk)->update('produks', $updateData);
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