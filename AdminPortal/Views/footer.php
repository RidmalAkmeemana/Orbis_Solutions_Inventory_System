<?php 
    require_once '../../API/Connection/config.php';

	// Fetch Company Name from the database
	$companyName = ""; // Default name if query fails

	$query = "SELECT Company_Name FROM tbl_company_info LIMIT 1"; 
	$result = mysqli_query($conn, $query);

	if ($result && mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$companyName = $row['Company_Name'];
	}
?>

<footer class="text-center py-3">
	<hr></hr>
    <p class="mt-4">Copyright &copy; <?php echo date("Y"); ?> <?php echo($companyName); ?> | Powered by Orbis Solutions</p>
</footer>