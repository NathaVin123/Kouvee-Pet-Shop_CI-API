<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class LaporanModel extends CI_Model
{
    public function PendapatanBulananProduk() {
        // $this->db->select('produk.nama "nama_produk"');
        // $this->db->select_sum('detail_transaksi_produk.total_harga', 'harga');
        // $this->db->from('detail_transaksi_produk');
        // $this->db->join('produk','detail_transaksi_produk.id_produk=produk.id_produk');
        // $this->db->where('month(detail_transaksi_produk.created_at)','month(sysdate())-2');
        // $this->db->group_by('produk.nama');
        //return $this->db->get()->result();

        return $this->db->query('select B.nama_produk "nama_produk", sum(A.total_harga) "harga" from detailtransaksiproduks A
        inner join produks B on A.id_produk=B.id_produk where month(A.createLog_at)=month(sysdate())-1
        group by B.nama_produk')->result();
    }

    public function PendapatanBulananLayanan(){
        return $this->db->query('select concat(C.nama_layanan," ", D.nama_layanan) "nama_layanan", sum(A.total_harga) "harga" from detailtransaksilayanans A
        join layanahargas B on A.id_layananHarga=B.id_layananHarga
        join layanans C on B.id_layanan=.C.id_layanan
        join ukuranhewans D on B.id_ukuranHewan=D.id_ukuraHewan
        where month(A.createLog_at)=month(sysdate())-1
        group by B.id_layananHarga')->result();
    }
}
?>