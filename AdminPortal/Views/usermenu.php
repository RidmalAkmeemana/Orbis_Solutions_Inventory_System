<?php 
    require_once '../../API/Connection/validator.php';
    require_once '../../API/Connection/config.php';

    $query = mysqli_query($conn, "SELECT * FROM `tbl_user` WHERE `username` = '$_SESSION[user]'") or die(mysqli_error());
    $fetch = mysqli_fetch_array($query);
            		
?>
	<li class="nav-item dropdown has-arrow">
		<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
			<span class="user-img"><img class="rounded-circle" src="<?php echo $fetch['Img']; ?>" width="31" alt="User Image"></span>
		</a>
		<div class="dropdown-menu">
			<div class="user-header">
				<div class="avatar avatar-sm">
					<img src="<?php echo $fetch['Img']; ?>" alt="User Image" class="avatar-img rounded-circle" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;">
				</div>
				<div class="user-text">
					<h6><?php echo $fetch['First_Name']; ?> <?php echo $fetch['Last_Name']; ?></h6>
					<p class="text-muted mb-0"><?php echo $fetch['Status']; ?></p>
				</div>
			</div>
				<a class="dropdown-item" href="profile.php">My Profile</a>	
				<a class="dropdown-item" href="logout.php">Logout</a>
		</div>
	</li>