<?php 
use Restserver \Libraries\REST_Controller ; 

Class auth extends REST_Controller{ 
    
    public function __construct(){
        header('Access-Control-Allow-Origin: *');         
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");         
        header("Access-Control-Allow-Headers: Content-Type, ContentLength, Accept-Encoding");         
        parent::__construct();         
        $this->load->model('LoginModel');         
        $this->load->library('form_validation');     
        $this->load->helper(['jwt', 'authorization']);   
    }    

    public $rule = [       
        [                     
            'field' => 'password',                     
            'label' => 'password',                     
            'rules' => 'required'                 
        ],                 
        [                     
            'field' => 'NIP',                     
            'label' => 'NIP',                     
            'rules' => 'required|valid_email'                 
        ]  
    ];     
    
    public function Rules() { return $this->rule; }     

    
    public function index_post(){
        $validation = $this->form_validation;         
        $rule = $this->Rules();            
        $validation->set_rules($rule);         
        if (!$validation->run()) {             
            return $this->response($this->form_validation->error_array());         
        }        

        $user = new LoginData();
        $user->password = $this->post('password');
        $user->NIP = $this->post('NIP');

        if($result= $this->LoginModel->verify($user)){
            
            $token = AUTHORIZATION::generateToken(['NIP' => $result['NIP'],'nama_pegawai' => $result['nama_pegawai']]);
            $status = parent::HTTP_OK;
            $response = ['status' => $status, 'token' => $token];
            return $this->response($response, $status);
    
        }
        else
        {
            return $this->response('Gagal');
        }
    }
} 

Class LoginData{
    public $NIP;
    public $nama_pegawai;
    public $password;
}