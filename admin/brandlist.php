<?php include 'inc/header.php';?>
<?php include 'inc/sidebar.php';?>
<?php  include $_SERVER['DOCUMENT_ROOT']."/shop/classes/Brand.php";?>
<?php 
$brand = new Brand();
if (isset($_GET['delbrand'])) {
	$id = $_GET['delbrand'];
	$delbrand = $brand->delBrandById($id);
}
?>
        <div class="grid_10">
            <div class="box round first grid">
                <h2>Category List</h2>
                <div class="block">    
                <?php if (isset($delbrand)) { echo $delbrand; } ?>    
                    <table class="data display datatable" id="example">
					<thead>
						<tr>
							<th>Serial No.</th>
							<th>Category Name</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$getbrand = $brand->getAllBrand();
						if ($getbrand) {
							$i = 0;
							while ($result = $getbrand->fetch_assoc()) {
								$i++; ?>
						<tr class="odd gradeX">
							<td><?php echo $i ?></td>
							<td><?php echo $result['brandName'] ?></td>
							<td><a href="brandedit.php?brandid=<?php echo $result['brandId'] ?>">Edit</a> || <a onclick="return confirm('are u sure to delete?')" href="?delbrand=<?php echo $result['brandId'] ?>">Delete</a></td>
						</tr>
						<?php } } ?>
					</tbody>
				</table>
               </div>
            </div>
        </div>
<script type="text/javascript">
	$(document).ready(function () {
	    setupLeftMenu();

	    $('.datatable').dataTable();
	    setSidebarHeight();
	});
</script>
<?php include 'inc/footer.php';?>