<?php include 'inc/header.php' ?> 
<?php 
	$login = Session::get("cuslogin");
	if ($login == false) {
		header("Location:login.php");
	}
?> 
<style type="text/css">
table.tblone img{height: 90px;width: 100px;}
</style>
<?php 
if (isset($_GET['delWlistid'])) {
	$id = $_GET['delWlistid'];
	$delWlistid = $pd->delWlistById($cmrId, $id);
}
?>
<div class="main">
    <div class="content">
    	<div class="cartoption">		
			<div class="cartpage">
		    	<h2>WishList</h2>
				<table class="tblone">
					<tr>
						<th>SL</th>
						<th>Product Name</th>
						<th>Price</th>
						<th>Image</th>
						<th>Action</th>
					</tr>
					<?php 
					$getPd = $pd->getWlistData($cmrId);
					if ($getPd) {
						$i = 0;
						while ($result = $getPd->fetch_assoc()) {
						$i++; ?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $result['productName']; ?></td>
						<td>$<?php echo $result['price']; ?></td>
						<td><img src="admin/<?php echo $result['image']; ?>" alt=""/></td>
						<td>
							<a href="details.php?proid=<?php echo $result['productId']; ?>"> Buy Now </a> ||
							<a onclick="return confirm('are u sure to delete?')" href="?delWlistid=<?php echo $result['productId'];?>"> Remove </a>
						</td>	
					</tr>
					<?php } } ?>
				</table>		
			</div>
			<div class="shopping" style="width: 100%;text-align: center;">
				<div class="shopleft">
					<a href="index.php"> <img src="images/shop.png" alt="" /></a>
				</div>
			</div>
    	</div>  	
       <div class="clear"></div>
    </div>
 </div>
<?php include 'inc/footer.php' ?>