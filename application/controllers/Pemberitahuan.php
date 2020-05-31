<?php
//require (APPPATH.'/libraries/REST_Controller.php');
use Restserver \Libraries\REST_Controller ;

Class Pemberitahuan extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('PemberitahuanModel');
        $this->load->library('form_validation');
	}
	
	public function getWithJoin_get() {
		$this->db->select('pemberitahuans.id_produk, produks.id_produk, produks.nama_produk, produks.gambar, pemberitahuans.createLog_at');
		$this->db->from('pemberitahuans');
		$this->db->join('produks', 'pemberitahuans.id_produk = produks.id_produk');
		$this->db->order_by('pemberitahuans.createLog_at ASC');
		return $this->returnData($this->db->get()->result(), false);
	}

    public function index_get(){
        return $this->returnData($this->db->get('pemberitahuans')->result(), false);
    }

    public function all_get(){
        return $this->returnData($this->db->get('pemberitahuans')->result(), false);
    }

    public function allOrderAsc_get(){
        $query = $this->db->from('pemberitahuans')->order_by('createLog_at', 'ASC')->get();
        return $this->returnData($query->result(), false);
    }

    public function allOrderDesc_get(){
        $query = $this->db->from('pemberitahuans')->order_by('createLog_at', 'DESC')->get();
        return $this->returnData($query->result(), false);
    }

    public function new_get(){
        return $this->returnData($this->db->get_where('pemberitahuans', ["status" => 0])->result(), false);
    }

    public function newOrderAsc_get(){
        $query = $this->db->from('pemberitahuans')->where(["status" => 0])->order_by('createLog_at', 'ASC')->get();
        return $this->returnData($query->result(), false);
    }

    public function newOrderDesc_get(){
        $query = $this->db->from('pemberitahuans')->where(["status" => 0])->order_by('createLog_at', 'DESC')->get();
        return $this->returnData($query->result(), false);
    }

    public function opened_get(){
        return $this->returnData($this->db->get_where('pemberitahuans', ["status" => 1])->result(), false);
    }

    public function openedOrderAsc_get(){
        $query = $this->db->from('pemberitahuans')->where(["status" => 1])->order_by('createLog_at', 'ASC')->get();
        return $this->returnData($query->result(), false);
    }

    public function openedOrderDesc_get(){
        $query = $this->db->from('pemberitahuans')->where(["status" => 1])->order_by('createLog_at', 'DESC')->get();
        return $this->returnData($query->result(), false);
    }

    public function search_get($id){
        return $this->returnData($this->db->get_where('pemberitahuans', ["id_notifikasi" => $id])->row(), false);
    }

    public function updateStatus_post($id_notifikasi = null){
        if($id_notifikasi == null){
            return $this->returnData('Parameter ID tidak ditemukan', true);
        }
        $response = $this->NotifikasiModel->updateStatusToOpened($id_notifikasi);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg,$error){
        $response['error']=$error;
        $response['message']=$msg;
        return $this->response($response);
    }
}

Class PemberitahuanData{
    public $id_produk;
    public $status;
    public $createLog_at;
}