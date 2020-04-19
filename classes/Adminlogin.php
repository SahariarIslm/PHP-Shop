<?php 
	include $_SERVER['DOCUMENT_ROOT']."/shop/lib/Session.php";
	Session::checkLogin();
	include $_SERVER['DOCUMENT_ROOT']."/shop/lib/Database.php";
	include $_SERVER['DOCUMENT_ROOT']."/shop/helpers/Format.php";
?>

<?php 
class Adminlogin
{
	private $db;
	private $fm;

	public function __construct()
	{
		$this->db = new Database;
		$this->fm = new Format;
	}
	public function adminLogin($adminUser,$adminPass){
		$adminUser = $this->fm->validation($adminUser);
		$adminPass = $this->fm->validation($adminPass);

		$adminUser = mysqli_real_escape_string($this->db->link,$adminUser);
		$adminPass = mysqli_real_escape_string($this->db->link,$adminPass);
		if (empty($adminUser)||empty($adminPass)) {
			$loginmsg = "username or password must not be empty";
			return $loginmsg;
		}else{
			$query = "select * from tbl_admin where adminUser = '$adminUser' and adminPass='$adminPass'";
			$result = $this->db->select($query);
			if ($result != false) {
				$value = $result->fetch_assoc();
				Session::set("adminlogin",true);
				Session::set("adminId",$value['adminId']);
				Session::set("adminUser",$value['adminUser']);
				Session::set("adminName",$value['adminName']);
				header("Location:dashboard.php");

			}else{
				$loginmsg = "username or password not match";
			}
		}
	}
}  
?>