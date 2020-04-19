<?php 

include_once $_SERVER['DOCUMENT_ROOT']."/shop/lib/Database.php";
include_once $_SERVER['DOCUMENT_ROOT']."/shop/helpers/Format.php";
?>
<?php 
class Product
{
	
	private $db;
	private $fm;

	public function __construct()
	{
		$this->db = new Database;
		$this->fm = new Format;
	}
	public function productInsert($data,$file){

		$productName = mysqli_real_escape_string($this->db->link,$data['productName']);
		$catId = mysqli_real_escape_string($this->db->link,$data['catId']);
		$brandId = mysqli_real_escape_string($this->db->link,$data['brandId']);
		$body = mysqli_real_escape_string($this->db->link,$data['body']);
		$price = mysqli_real_escape_string($this->db->link,$data['price']);
		$type = mysqli_real_escape_string($this->db->link,$data['type']);

		$permited  = array('jpg', 'jpeg', 'png', 'gif');
	    $file_name = $file['image']['name'];
	    $file_size = $file['image']['size'];
	    $file_temp = $file['image']['tmp_name'];

	    $div = explode('.', $file_name);
	    $file_ext = strtolower(end($div));
	    $unique_image = substr(md5(time()), 0, 10).'.'.$file_ext;
	    $uploaded_image = "upload/".$unique_image;
	    if ($productName == "" || $catId == "" || $brandId == "" || $body == "" || $price == "" || $file_name == "" || $type == "") {
	    	$msg = "<span class='error'> Fields must not be empty </span>";
	    	return $msg;
	    }elseif ($file_size >1048567) {
	     
	     echo "<span class='error'>Image Size should be less then 1MB!
	     </span>";

	    } elseif (in_array($file_ext, $permited) === false) {
	     
	     echo "<span class='error'>You can upload only:-"
	     .implode(', ', $permited)."</span>";

	    } else{
	    	move_uploaded_file($file_temp, $uploaded_image);
	    	$query = "INSERT into tbl_product(productName,catId,brandId,body,price,image,type) VALUES('$productName','$catId','$brandId','$body','$price','$uploaded_image','$type')";
	    $productinsert = $this->db->insert($query);
			if ($productinsert) {
				$msg = "<span class='success'>Product inserted successfully</span>";
				return $msg;
			}else{
				$msg = "<span class='error'>Product Not inserted </span>";
				return $msg;
			}
	    }
		
	}
	public function getAllProduct(){
		/*$query = "SELECT p.*, c.catName, b.brandName 
			from tbl_product as p, tbl_category as c, tbl_brand as b where p.catId = c.catId and p.brandId=b.brandId order by p.productId desc";*/

		$query = "SELECT tbl_product.*,
		tbl_category.catName, tbl_brand.brandName 
		FROM tbl_product 
		INNER JOIN tbl_category 
		ON tbl_product.catId = tbl_category.catId 
		INNER JOIN tbl_brand 
		ON tbl_product.brandId = tbl_brand.brandId 
		ORDER BY tbl_product.productId DESC";


		$result = $this->db->select($query);
		return $result;
	}
	public function getProById($id){
		$query = "SELECT * from tbl_product where productId = '$id'";
		$result = $this->db->select($query);
		return $result;
	}
	public function productUpdate($data,$file,$id){
		$productName = mysqli_real_escape_string($this->db->link,$data['productName']);
		$catId = mysqli_real_escape_string($this->db->link,$data['catId']);
		$brandId = mysqli_real_escape_string($this->db->link,$data['brandId']);
		$body = mysqli_real_escape_string($this->db->link,$data['body']);
		$price = mysqli_real_escape_string($this->db->link,$data['price']);
		$type = mysqli_real_escape_string($this->db->link,$data['type']);

		$permited  = array('jpg', 'jpeg', 'png', 'gif');
	    $file_name = $file['image']['name'];
	    $file_size = $file['image']['size'];
	    $file_temp = $file['image']['tmp_name'];

	    $div = explode('.', $file_name);
	    $file_ext = strtolower(end($div));
	    $unique_image = substr(md5(time()), 0, 10).'.'.$file_ext;
	    $uploaded_image = "upload/".$unique_image;
	    if ($productName == "" || $catId == "" || $brandId == "" || $body == "" || $price == "" || $type == "") {
	    	$msg = "<span class='error'> Fields must not be empty </span>";
	    	return $msg;
	    }else{
	    	if (!empty($file_name)) {
			    if ($file_size >1048567) {
			     
			     echo "<span class='error'>Image Size should be less then 1MB!
			     </span>";

			    } elseif (in_array($file_ext, $permited) === false) {
			     
			     echo "<span class='error'>You can upload only:-"
			     .implode(', ', $permited)."</span>";

			    } else{
			    	move_uploaded_file($file_temp, $uploaded_image);
			    	$query = "UPDATE tbl_product
			    	set
			    	productName = '$productName',
			    	catId       = '$catId',
			    	brandId     = '$brandId',
			    	body        = '$body',
			    	price       = '$price',
			    	image       = '$uploaded_image',
			    	type        = '$type'
			    	where productId = '$id'";
			    	
			    $productupdate = $this->db->update($query);
					if ($productupdate) {
						$msg = "<span class='success'>Product updated successfully</span>";
						return $msg;
					}else{
						$msg = "<span class='error'>Product Not updated </span>";
						return $msg;
					}
			    }
			}else{
			    	$query = "UPDATE tbl_product
			    	set
			    	productName = '$productName',
			    	catId       = '$catId',
			    	brandId     = '$brandId',
			    	body        = '$body',
			    	price       = '$price',
			    	type        = '$type'
			    	where productId = '$id'";
			    	
			    $productupdate = $this->db->update($query);
					if ($productupdate) {
						$msg = "<span class='success'>Product updated successfully</span>";
						return $msg;
					}else{
						$msg = "<span class='error'>Product Not updated </span>";
						return $msg;
					}
			    }
		    
		}
	}
	public function delProById($id){
		$query = "SELECT * from tbl_product where productId ='$id'";
		$getData = $this->db->select($query);
		if ($getData) {
			while ($delImg = $getData->fetch_assoc()) {
				$dellink = $delImg['image'];
				unlink($dellink);
			}
		}
		$delquery = "delete from tbl_product where productId = '$id'";
		$delData = $this->db->delete($delquery);
		if ($delData) {
			$msg = "<span class='success'>Product deleted successfully </span>";
				return $msg;
		}else{
			$msg = "<span class='error'> Product not deleted </span>";
				return $msg;
		}
	}
	public function getFeaturedProduct(){
		$query = "SELECT * from tbl_product where type ='0' order by ProductId desc limit 4";
		$result = $this->db->select($query);
		return $result;
	}
	public function getNewProduct(){
		$query = "SELECT * from tbl_product order by ProductId desc limit 4";
		$result = $this->db->select($query);
		return $result;
	}
	public function getSingleProduct($id){
		$query = "SELECT p.*, c.catName, b.brandName 
			from tbl_product as p, tbl_category as c, tbl_brand as b where p.catId = c.catId and p.brandId=b.brandId and p.productId = '$id'";
		$result = $this->db->select($query);
		return $result;
	}
	public function latestFromIphone(){
		$query = "SELECT * from tbl_product where brandId = '2' order by ProductId desc limit 1";
		$result = $this->db->select($query);
		return $result;
	}
	public function latestFromSamsung(){
		$query = "SELECT * from tbl_product where brandId = '3' order by ProductId desc limit 1";
		$result = $this->db->select($query);
		return $result;
	}
	public function latestFromAcer(){
		$query = "SELECT * from tbl_product where brandId = '1' order by ProductId desc limit 1";
		$result = $this->db->select($query);
		return $result;
	}
	public function latestFromCanon(){
		$query = "SELECT * from tbl_product where brandId = '5' order by ProductId desc limit 1";
		$result = $this->db->select($query);
		return $result;
	}
	public function productByCat($id){
		$query = "SELECT * from tbl_product where catId = '$id'";
		$result = $this->db->select($query);
		return $result;
	}
	public function insertCompareData($cmprid,$cmrId){
		$productId = mysqli_real_escape_string($this->db->link,$cmprid);
		$cmrId = mysqli_real_escape_string($this->db->link,$cmrId);
		$cquery = "SELECT * from tbl_compare where cmrId = '$cmrId' and productId = '$productId'";
		$check = $this->db->select($cquery);
		if ($check) {
			$msg = "<span class='error'> Already Added </span>";
			return $msg;
		}
		$query = "SELECT * from tbl_product where productId = '$productId'";
		$result = $this->db->select($query)->fetch_assoc();
		if ($result) {
			$productId = $result['productId'];
			$productName = $result['productName'];
			$price = $result['price'];
			$image = $result['image'];
		$query = "INSERT into tbl_compare(cmrId,productId,productName,price,image) values('$cmrId','$productId','$productName','$price','$image')";
		$insert_row = $this->db->insert($query);
			if ($insert_row) {
				$msg = "<span class='success'>Added !! Check compare page...</span>";
					return $msg;
			}else{
				$msg = "<span class='error'> Not added </span>";
					return $msg;
			}
		}
	}
	public function getCompareData($cmrId){
		$query = "SELECT * from tbl_compare where cmrId = '$cmrId' order by id desc";
		$result = $this->db->select($query);
		return $result;
	}
	public function delCompareData($cmrId){
		$query = "delete from tbl_compare where cmrId = '$cmrId'";
		$delData = $this->db->delete($query);
	}
	public function saveWishlistData($id,$cmrId){
		$pquery = "SELECT * from tbl_product where productId = '$id'";
		$result = $this->db->select($pquery)->fetch_assoc();
		if ($result) {
			$productId = $result['productId'];
			$productName = $result['productName'];
			$price = $result['price'];
			$image = $result['image'];
		$cquery = "SELECT * from tbl_wlist where cmrId = '$cmrId' and productId = '$id'";
		$check = $this->db->select($cquery);
		if ($check) {
			$msg = "<span class='error'> Already Added </span>";
			return $msg;
		}
		$query = "INSERT into tbl_wlist(cmrId,productId,productName,price,image) values('$cmrId','$productId','$productName','$price','$image')";
		$insert_row = $this->db->insert($query);
		if ($insert_row) {
				$msg = "<span class='success'>Added !! Check WishList...</span>";
					return $msg;
			}else{
				$msg = "<span class='error'> Not added </span>";
					return $msg;
			}
		}
	}
	public function getWlistData($cmrId){
		$query = "SELECT * from tbl_wlist where cmrId = '$cmrId' order by id desc";
		$result = $this->db->select($query);
		return $result;
	}
	public function delWlistById($cmrId,$id){
		$delquery="DELETE from tbl_wlist where productId='$id' and cmrId='$cmrId' ";
		$result = $this->db->delete($delquery);
		return $result;
	}
}
?>