<?php
//require (APPPATH.'/libraries/REST_Controller.php');
use Restserver \Libraries\REST_Controller ;

Class Produk extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('ProdukModel');
        $this->load->library('form_validation');
    }

    public function index_get(){
        return $this->returnData($this->db->get_where('produks', ["aktif" => 1])->result(), false);
    }

    public function nonAktif_get(){
        return $this->returnData($this->db->get_where('produks', ["aktif" => 0])->result(), false);
    }

    public function all_get(){
        return $this->returnData($this->db->get('produks')->result(), false);
    }

    public function search_get($id_produk){
        return $this->returnData($this->db->get_where('produks', ["id_produk" => $id_produk])->row(), false);
    }

    public function underMinStok_get() {
        return $this->returnData($this->db->query('select * from produks where stok_produk < min_stok_produk')->result(), false);
    }

    public function index_post($id_produk = null){
        $validation = $this->form_validation;
        $rule = $this->ProdukModel->rules();
        if($id_produk == null){
            array_push($rule,
                [
                    'field' => 'nama_produk',
                    'label' => 'nama_produk',
                    'rules' => 'required|is_unique[produks.nama_produk]'
                ],
                [
                    'field' => 'harga_produk',
                    'label' => 'harga_produk',
                    'rules' => 'required|integer|greater_than[0]'
                ],
                [
                    'field' => 'stok_produk',
                    'label' => 'stok_produk',
                    'rules' => 'required|integer|greater_than_equal_to[0]'
                ],
                [
                    'field' => 'min_stok_produk',
                    'label' => 'min_stok_produk',
                    'rules' => 'required|integer|greater_than_equal_to[0]'
                ],
                [
                    'field' => 'satuan_produk',
                    'label' => 'satuan_produk',
                    'rules' => 'required'
                ],
                [
                    'field' => 'updateLog_by',
                    'label' => 'updateLog_by',
                    'rules' => 'required',
                ]
            );
        }
        $validation->set_rules($rule);
        if(!$validation->run()){
            return $this->returnData($this->form_validation->error_array(), true);
        }
        $user = new ProdukData();
        $user->nama_produk = $this->post('nama_produk');
        $user->harga_produk = $this->post('harga_produk');
        $user->stok_produk = $this->post('stok_produk');
        $user->min_stok_produk = $this->post('min_stok_produk');
        $user->satuan_produk = $this->post('satuan_produk');
        $user->gambar = $this->post('gambar');
        $user->updateLog_by = $this->post('updateLog_by');

        if($id_produk == null){
            $response = $this->ProdukModel->store($user);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function update_post($id_produk = null){
        $validation = $this->form_validation;
        $rule = $this->ProdukModel->rules();
        if($id_produk == null){
            array_push($rule,
                [
                    'field' => 'nama_produk',
                    'label' => 'nama_produk',
                    'rules' => 'required|is_unique[produks.nama_produk]'
                ],
                [
                    'field' => 'harga_produk',
                    'label' => 'harga_produk',
                    'rules' => 'required|integer|greater_than[0]'
                ],
                [
                    'field' => 'stok_produk',
                    'label' => 'stok_produk',
                    'rules' => 'required|integer|greater_than_equal_to[0]'
                ],
                [
                    'field' => 'min_stok_produk',
                    'label' => 'min_stok_produk',
                    'rules' => 'required|integer|greater_than_equal_to[0]'
                ],
                [
                    'field' => 'satuan_produk',
                    'label' => 'satuan_produk',
                    'rules' => 'required'
                ],
                [
                    'field' => 'updateLog_by',
                    'label' => 'updateLog_by',
                    'rules' => 'required',
                ]
            );
        }
        $validation->set_rules($rule);
        if (!$validation->run()) {
            return $this->returnData($this->form_validation->error_array(), true);
        }
        $user = new ProdukData();
        $user->nama_produk = $this->post('nama_produk');
        $user->harga_produk = $this->post('harga_produk');
        $user->stok_produk = $this->post('stok_produk');
        $user->min_stok_produk = $this->post('min_stok_produk');
        $user->satuan_produk = $this->post('satuan_produk');
        $user->gambar = $this->post('gambar');
        $user->updateLog_by = $this->post('updateLog_by');
        if($id_produk != null){
            $response = $this->ProdukModel->update($user,$id_produk);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function delete_post($id_produk = null){
        $validation = $this->form_validation;
        $rule = $this->ProdukModel->rules();
        
        $validation->set_rules($rule);
        if (!$validation->run()) {
            return $this->returnData($this->form_validation->error_array(), true);
        }
        $user = new ProdukData();
        if($id_produk != null){
            $response = $this->ProdukModel->softDelete($user,$id_produk);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    // public function index_delete($id_produk = null){
    //     if($id_produk == null){
    //         return $this->returnData('Parameter Id Tidak Ditemukan', true);
    //     }
    //     $response = $this->ProdukModel->destroy($id_produk);
    //     return $this->returnData($response['msg'], $response['error']);
    // }

    public function returnData($msg, $error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }
}

Class ProdukData{
    public $id_produk;
    public $nama_produk;
    public $harga_produk;
    public $stok_produk;
    public $min_stok_produk;
    public $satuan_produk;
    public $gambar;
    public $updateLog_by;
    public $createLog_at;
    public $updateLog_at;
    public $deleteLog_at;
    public $aktif;
}