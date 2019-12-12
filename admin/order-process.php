<?php
	ob_start();
	session_start();
	require_once '../config/connect.php';
	if(!isset($_SESSION['email']) & empty($_SESSION['email'])){
		header('location: login.php');
	}
?>
<?php include 'inc/header.php'; ?>
<?php include 'inc/nav.php'; ?>
<?php
if(isset($_POST) & !empty($_POST)){
		$status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
		$message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
		$id = filter_var($_POST['orderid'], FILTER_SANITIZE_NUMBER_INT);

			echo $ordprcsql = "INSERT INTO ordertracking (orderid, status, message) VALUES ('$id', '$status', '$message')";
			$ordprcres = mysqli_query($connection, $ordprcsql) or die(mysqli_error($connection));
			if($ordprcres){
				$ordupd = "UPDATE orders SET orderstatus='$status' WHERE id=$id";
				if(mysqli_query($connection, $ordupd)){
					header('location: orders.php');
				}
			}
}
?>


	<!-- SHOP CONTENT -->
	<section id="content">
		<div class="content-blog">
					<div class="page_header text-center">
						<h2>Admin - Order Processing</h2>
						<!-- <p>Do you want to cancel Order?</p> -->
					</div>
<form method="post">
<div class="container">
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<div class="billing-details">
						<h2 class="uppercase">Order Processing</h2>
						<br />

						<table class="cart-table account-table table table-bordered">
						<thead>
							<tr>
								<th>Product Name</th>
								<th>Quantity</th>
								<th>Price</th>
								<th>Total Price</th>
							</tr>
						</thead>
						<tbody>

							<?php
								if(isset($_GET['id']) & !empty($_GET['id'])){
									$oid = $_GET['id'];
								}else{
									header('location: orders.php');
								}
								$ordsql = "SELECT * FROM orders WHERE id='$oid'";
								$ordres = mysqli_query($connection, $ordsql);
								while($ordr = mysqli_fetch_assoc($ordres))

								$orditmsql = "SELECT * FROM orderitems o JOIN products p WHERE o.orderid='$oid' AND o.pid=p.id";
								$orditmres = mysqli_query($connection, $orditmsql);
								while($orditmr = mysqli_fetch_assoc($orditmres)){
							?>
							<tr>
								<td>
									<a href="single.php?id=<?php echo $orditmr['pid']; ?>"><?php echo substr($orditmr['name'], 0, 25); ?></a>
								</td>
								<td>
									<?php echo $orditmr['pquantity']; ?>
								</td>
								<td>
									ZAR <?php echo $orditmr['productprice']; ?>/-
								</td>
								<td>
									ZAR <?php echo $orditmr['productprice']*$orditmr['pquantity']; ?>/-
								</td>
							</tr>
							<?php } ?>
				<table class="cart-table account-table table table-bordered">
				<thead>
					<tr>
						<th>Order</th>
						<th>Date</th>
						<th>Status</th>
						<th>Payment Mode</th>
						<th>Total</th>
					</tr>
				</thead>
				<tbody>

				<?php
					if(isset($_GET['id']) & !empty($_GET['id'])){
						$oid = $_GET['id'];
					}else{
						header('location: orders.php');
					}
					$ordsql = "SELECT * FROM orders WHERE id='$oid'";
					$ordres = mysqli_query($connection, $ordsql);
					while($ordr = mysqli_fetch_assoc($ordres)){
				?>
					<tr>
						<td>
							<?php echo $ordr['id']; ?>
						</td>
						<td>
							<?php echo $ordr['timestamp']; ?>
						</td>
						<td>
							<?php echo $ordr['orderstatus']; ?>
						</td>
						<td>
							<?php echo $ordr['paymentmode']; ?>
						</td>
						<td>
							ZAR <?php echo $ordr['totalprice']; ?>/-
						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>

						<div class="space30"></div>
							<label class="">Order Status </label>
							<select name="status" class="form-control">
								<option value="">Select Status</option>
								<option value="In Progress">In Progress</option>
								<option value="Dispatched">Dispatched</option>
								<option value="Delivered">Delivered</option>
							</select>

							<div class="clearfix space20"></div>
							<label>Message :</label>
							<textarea class="form-control" name="message" cols="10"> </textarea>

					<input type="hidden" name="orderid" value="<?php echo $_GET['id']; ?>">
						<div class="space30"></div>
					<input type="submit" class="button btn-lg" value="Update Order Status">
					</div>
				</div>

			</div>

		</div>
</form>
		</div>
	</section>

<?php include 'inc/footer.php' ?>
