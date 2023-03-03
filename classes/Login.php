<?php
require_once '../config.php';
class Login extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;

		parent::__construct();
		ini_set('display_error', 1);
	}
	public function __destruct(){
		parent::__destruct();
	}
	public function index(){
		echo "<h1>Access Denied</h1> <a href='".base_url."'>Go Back.</a>";
	}
	public function login(){
		extract($_POST);

		$qry = $this->conn->query("SELECT * from users where username = '$username' and password = '$password'");
		if($qry->num_rows > 0){
			foreach($qry->fetch_array() as $k => $v){
				if(!is_numeric($k) && $k != 'password'){
					$this->settings->set_userdata($k,$v);
				}

			}
			$this->settings->set_userdata('login_type',1);
		return json_encode(array('status'=>'success'));
		}else{
		return json_encode(array('status'=>'incorrect','last_qry'=>"SELECT * from users where username = '$username' and password = '$password'"));
		}
	}
	public function logout(){
		if($this->settings->sess_des()){
			redirect('admin/login.php');
		}
	}
	function clogin(){
		extract($_POST);

		$qry = $this->conn->query("SELECT * from users where username = '$username' and password = '$password'");
		if($qry->num_rows > 0){
			foreach($qry->fetch_array() as $k => $v){
				if(!is_numeric($k) && $k != 'password'){
					$this->settings->set_userdata($k,$v);
				}

			}
			$this->settings->set_userdata('login_type',1);

		return json_encode(array('status'=>'success'));
		redirect('index.php');
		}else{
		return json_encode(array('status'=>'incorrect','last_qry'=>"SELECT * from users where username = '$username' and password = '$password'"));
		}
	}
	public function clogout(){
		if($this->settings->sess_des()){
			redirect('index.php');
		}
	}
}
function registration(){
		extract($_POST);
		$data ="";
		foreach($_POST as $k => $v){
			if(!in_array($k,array('id','password'))){
				if(!empty($data)) $data.= ", ";
				$data.= " {$k} = '{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `users` set {$data}";
		}else{
			$sql = "UPDATE `users` set {$data} where id = {$id}";
		}
		$save =  $this->conn->query($sql);
		$this->capture_err();
		if($save){
			$resp['status']='success';
			$this->settings->set_flashdata('success',' Jar Type & Pricing successfully saved.');
		}
		return json_encode($resp);
}
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$auth = new Login();
switch ($action) {
	case 'login':
		echo $auth->login();
		break;
	case 'clogin':
		echo $auth->clogin();
		break;
	case 'logout':
		echo $auth->logout();
		break;
	case 'clogout':
		echo $auth->clogout();
		break;
		case 'registration':
		echo $auth->registration();
	default:
		echo $auth->index();
		break;
}

