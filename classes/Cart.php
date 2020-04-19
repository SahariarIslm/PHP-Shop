<?php
include_once $_SERVER['DOCUMENT_ROOT']."/shop/lib/Database.php";
include_once $_SERVER['DOCUMENT_ROOT']."/shop/helpers/Format.php";
?>
<?php 
class Cart
{
	
	private $db;
	private $fm;

	public function __construct()
	{
		$this->db = new Database;
		$this->fm = new Format;
	}
	public function addToCart($quantity, $id){
		$quantity = $this->fm->validation($quantity);
		$quantity = mysqli_real_escape_string($this->db->link,$quantity);
		$productId = mysqli_real_escape_string($this->db->link,$id);
		$sId = session_id();
		$squery = "select * from tbl_product where productId = '$productId'";
		$result = $this->db->select($squery)->fetch_assoc();
		$productName = $result['productName'];
		$price = $result['price'];
		$image = $result['image'];
		$chquery = "select * from tbl_cart where productId = '$productId' and sId = '$sId'";
		$getpro = $this->db->select($chquery);
		if ($getpro) {
			$msg = "Product already added";
			return $msg;
		}else{
			$query = "INSERT into tbl_cart(sId,productId,productName,price,quantity,image) values('$sId','$productId','$productName','$price','$quantity','$image')";
			$insert_row = $this->db->insert($query);
			if ($insert_row) {
				header("Location:cart.php");
			}else{
				header("Location:404.php");
			}
		}
	}
	public function getCartProduct(){
		$sId = session_id();
		$query = "SELECT * from tbl_cart where SId = '$sId'";
		$result = $this->db->select($query);
		return $result;
	}
	public function updateCartQuantity($cartId, $quantity){
		$cartId = mysqli_real_escape_string($this->db->link,$cartId);
		$quantity = mysqli_real_escape_string($this->db->link,$quantity);
		$query = "update tbl_cart
					set 
					quantity = '$quantity' 
					where cartId = $cartId";
		$updated_row = $this->db->update($query);
		if ($updated_row) {
			header("Location:cart.php");
		}else{
			$msg = "<span class='error'>Quantity not updated </span>";
			return $msg;
		}
	}
	public function delProductByCart($delid){
		$delid = mysqli_real_escape_string($this->db->link,$delid);
		$query = "delete from tbl_cart where cartId = '$delid'";
		$delcart = $this->db->delete($query);
		if ($delcart) {
			echo "<script>window.location:'cart.php';</script>";
		}else{
			$msg = "<span class='error'>Cart not deleted </span>";
				return $msg;
		}
	}
	public function checkCartTable(){
		$sId = session_id();
		$query = "SELECT * from tbl_cart where SId = '$sId'";
		$result = $this->db->select($query);
		return $result;
	}
	public function delCustomerCart(){
		$sId = session_id();
		$query = "delete from tbl_cart where sId='$sId'";
		$this->db->delete($query);
	}
	public function orderProduct($cmrId){
		$sId = session_id();
		$query = "SELECT * from tbl_cart where SId = '$sId'";
		$getPro = $this->db->select($query);
		if ($getPro) {
			while ($result = $getPro->fetch_assoc()) {
				$productId = $result['productId'];
				$productName = $result['productName'];
				$quantity = $result['quantity'];
				$price = $result['price']*$quantity;
				$image = $result['image'];
			$query = "INSERT into tbl_order(cmrId,productId,productName,quantity,price,image) values('$cmrId','$productId','$productName','$quantity','$price','$image')";
			$insert_row = $this->db->insert($query);
			}
		}
	}
	public function payableAmount($cmrId){
		$query = "SELECT price from tbl_order where cmrId = '$cmrId' and date = now()";
		$result = $this->db->select($query);
		return $result;
	}
	public function getOrderedProduct($cmrId){
		$query = "SELECT * from tbl_order where cmrId = '$cmrId' order by productId desc";
		$result = $this->db->select($query);
		return $result;
	}
	public function chkOrder($cmrId){
		$query = "SELECT * from tbl_order where cmrId = '$cmrId'";
		$result = $this->db->select($query);
		return $result;
	}
	public function getAllOrderProduct(){
		$query = "SELECT * from tbl_order order by date desc";
		$result = $this->db->select($query);
		return $result;
	}
	public function productShifted($id,$date,$price){
		$id    = mysqli_real_escape_string($this->db->link,$id);
		$date  = mysqli_real_escape_string($this->db->link,$date);
		$price = mysqli_real_escape_string($this->db->link,$price);
		$query = "UPDATE tbl_order
					set 
					status = '1' 
					where cmrId = $id and date='$date' and price='$price'";
		$updated_row = $this->db->update($query);
		if ($updated_row) {
			$msg = "<span class='success'> updated successfully</span>";
			return $msg;
		}else{
			$msg = "<span class='error'>Not updated</span>";
			return $msg;
		}
	}
	public function delProductShifted($id,$date,$price){
		$id    = mysqli_real_escape_string($this->db->link,$id);
		$date  = mysqli_real_escape_string($this->db->link,$date);
		$price = mysqli_real_escape_string($this->db->link,$price);

		$query = "delete from tbl_order where cmrId = $id and date='$date' and price='$price'";
		$delcat = $this->db->delete($query);
		if ($delcat) {
			$msg = "<span class='success'>Data deleted successfully</span>";
				return $msg;
		}else{
			$msg = "<span class='error'>Data not deleted </span>";
				return $msg;
		}
	}
	public function productShiftConfirm($id,$date,$price){
		$id    = mysqli_real_escape_string($this->db->link,$id);
		$date  = mysqli_real_escape_string($this->db->link,$date);
		$price = mysqli_real_escape_string($this->db->link,$price);
		$query = "UPDATE tbl_order
					set 
					status = '2' 
					where cmrId = $id and date='$date' and price='$price'";
		$updated_row = $this->db->update($query);
		if ($updated_row) {
			$msg = "<span class='success'> updated successfully</span>";
			return $msg;
		}else{
			$msg = "<span class='error'>Not updated</span>";
			return $msg;
		}
	}
}
?>