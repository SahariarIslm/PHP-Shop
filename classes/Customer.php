<?php 

include_once $_SERVER['DOCUMENT_ROOT']."/shop/lib/Database.php";
include_once $_SERVER['DOCUMENT_ROOT']."/shop/helpers/Format.php";
?>

<?php 
class Customer
{
	
	private $db;
	private $fm;

	public function __construct()
	{
		$this->db = new Database;
		$this->fm = new Format;
	}
	public function customerRegistration($data){
		$name    = mysqli_real_escape_string($this->db->link,$data['name']);
		$address = mysqli_real_escape_string($this->db->link,$data['address']);
		$city    = mysqli_real_escape_string($this->db->link,$data['city']);
		$country = mysqli_real_escape_string($this->db->link,$data['country']);
		$zip     = mysqli_real_escape_string($this->db->link,$data['zip']);
		$phone   = mysqli_real_escape_string($this->db->link,$data['phone']);
		$email   = mysqli_real_escape_string($this->db->link,$data['email']);
		$password    = mysqli_real_escape_string($this->db->link,md5($data['password']));
		
		

		if ($name == ""  || $address == "" || $city == "" || $country == "" || $zip == "" || $phone == "" || $email=="" || $password=="") {
	    	$msg = "<span class='error'> Fields must not be empty </span>";
	    	return $msg;
	    }
	    $mailquery = "select * from tbl_customer where email = '$email'";
	    $mailchk = $this->db->select($mailquery);
	    if ($mailchk!= false) {
	    	$msg = "<span class='error'> Mail already exist</span>";
	    	return $msg;
	    }else{
	    	$query = "INSERT into tbl_customer(name,address,city,country,zip,phone,email,password) VALUES('$name','$address','$city','$country','$zip','$phone','$email','$password')";
		    $insert_row = $this->db->insert($query);
			if ($insert_row) {
				$msg = "<span class='success'>Customer data inserted successfully</span>";
				return $msg;
			}else{
				$msg = "<span class='error'>Customer data Not inserted </span>";
				return $msg;
			}
	    }
	}
	public function customerLogin($data){
		$email   = mysqli_real_escape_string($this->db->link,$data['email']);
		$password    = mysqli_real_escape_string($this->db->link,md5($data['password']));
		if (empty($email)||empty($password)) {
			$msg = "<span class='error'>Fields must not be empty</span>";
		}
		$query = "SELECT * from tbl_customer where email='$email' and password='$password'";
		$result = $this->db->select($query);
		if ($result!=false) {
			$value = $result->fetch_assoc();
			Session::set("cuslogin",true);
			Session::set("cmrId",$value['id']);
			Session::set("cmrName",$value['name']);
			header("Location:cart.php");
			}else{
				$msg = "<span class='error'>Email or Password not matched</span>";
			}
	}
	public function getCustomerData($id){
		$query = "SELECT * from tbl_customer where id = '$id'";
		$result = $this->db->select($query);
		return $result;
	}
	public function customerUpdate($data,$cmrId){
		$name    = mysqli_real_escape_string($this->db->link,$data['name']);
		$address = mysqli_real_escape_string($this->db->link,$data['address']);
		$city    = mysqli_real_escape_string($this->db->link,$data['city']);
		$country = mysqli_real_escape_string($this->db->link,$data['country']);
		$zip     = mysqli_real_escape_string($this->db->link,$data['zip']);
		$phone   = mysqli_real_escape_string($this->db->link,$data['phone']);
		$email   = mysqli_real_escape_string($this->db->link,$data['email']);

		if ($name == ""  || $address == "" || $city == "" || $country == "" || $zip == "" || $phone == "" || $email=="") {
	    	$msg = "<span class='error'> Fields must not be empty </span>";
	    	return $msg;
	    }else{
		  $query = "UPDATE tbl_customer
					set 
					name    = '$name', 
					address = '$address', 
					city    = '$city', 
					country = '$country', 
					zip     = '$zip', 
					phone   = '$phone', 
					email   = '$email' 
					where id = '$cmrId'";
			$updated_row = $this->db->update($query);
			if ($updated_row) {
				$msg = "<span class='success'>Customer data updated successfully</span>";
				return $msg;
			}else{
				$msg = "<span class='error'>Customer data not updated </span>";
				return $msg;
			}
	    }
	}
}
?>