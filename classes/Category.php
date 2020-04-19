<?php 
include_once $_SERVER['DOCUMENT_ROOT']."/shop/lib/Database.php";
include_once $_SERVER['DOCUMENT_ROOT']."/shop/helpers/Format.php";
?>
<?php 
/**
 * Category class
 */
class Category
{
	private $db;
	private $fm;

	public function __construct()
	{
		$this->db = new Database;
		$this->fm = new Format;
	}
	public function catInsert($catName){
		$catName = $this->fm->validation($catName);
		$catName = mysqli_real_escape_string($this->db->link,$catName);
		if (empty($catName)) {
			$msg = "<span class='error'>Category field must not be empty </span>";
			return $msg;
		}else{
			$query = "insert into tbl_category(catName) values('$catName')";
			$catinsert = $this->db->insert($query);
			if ($catinsert) {
				$msg = "<span class='success'>Category inserted successfully</span>";
				return $msg;
			}else{
				$msg = "<span class='error'>Category Not inserted </span>";
				return $msg;
			}
		}
	}
	public function getAllCat(){
		$query = "select * from tbl_category order by catId desc";
		$result = $this->db->select($query);
		return $result;
	}
	public function getCatById($id){
		$query = "select * from tbl_category where catId = '$id'";
		$result = $this->db->select($query);
		return $result;
	}
	public function catUpdate($catName, $id){
		$catName = $this->fm->validation($catName);
		$catName = mysqli_real_escape_string($this->db->link,$catName);
		$id = mysqli_real_escape_string($this->db->link,$id);
		if (empty($catName)) {
			$msg = "<span class='error'>Category field must not be empty </span>";
			return $msg;
		}else{
			$query = "UPDATE tbl_category
					set 
					catName = '$catName' 
					where catId = $id";
			$updated_row = $this->db->update($query);
			if ($updated_row) {
				$msg = "<span class='success'>Category updated successfully</span>";
				return $msg;
			}else{
				$msg = "<span class='error'>Category not updated </span>";
				return $msg;
			}
		}
	}
	public function delCatById($id){
		$query = "delete from tbl_category where catId = '$id'";
		$delcat = $this->db->delete($query);
		if ($delcat) {
			$msg = "<span class='success'>Category deleted successfully</span>";
				return $msg;
		}else{
			$msg = "<span class='error'>Category not deleted </span>";
				return $msg;
		}
	}
}


 ?>