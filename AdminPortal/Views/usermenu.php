<?php 
    require_once '../../API/Connection/validator.php';
    require_once '../../API/Connection/config.php';
	include '../../API/Connection/uploadurl.php';

    $query = mysqli_query($conn, "SELECT * FROM `tbl_user` WHERE `username` = '$_SESSION[user]'") or die(mysqli_error());
    $fetch = mysqli_fetch_array($query);
            		
?>
	<li class="nav-item">
		<a href="javascript:void(0);" id="darkModeToggle" class="nav-link">
			<i id="darkModeIcon" class="fa fa-moon-o"></i>
		</a>
	</li>
	<li class="nav-item dropdown has-arrow">
		<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
			<span class="user-img"><img class="rounded-circle" src="<?php echo $base_url.$fetch['Img']; ?>" style="width: 31px; height: 31px; object-fit: cover; border-radius: 50%;" alt="User Image"></span>
		</a>
		<div class="dropdown-menu">
			<div class="user-header">
				<div class="avatar avatar-sm">
					<img src="<?php echo $base_url.$fetch['Img']; ?>" alt="User Image" class="avatar-img rounded-circle" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;">
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

	<script>
// ----------------------
// DARK MODE INITIAL LOAD
// ----------------------
document.addEventListener("DOMContentLoaded", function () {

    const bodyEl = document.body;
    const iconEl = document.getElementById("darkModeIcon");

    // Check saved mode
    const savedMode = localStorage.getItem("darkMode");

    if (savedMode === "enabled") {
        bodyEl.classList.add("dark-mode");
        iconEl.classList.remove("fa-moon-o");
        iconEl.classList.add("fa-sun-o");
    }

    // -----------------------
    // DARK MODE TOGGLE BUTTON
    // -----------------------
    $("#darkModeToggle").on("click", function () {
        
        bodyEl.classList.toggle("dark-mode");

        if (bodyEl.classList.contains("dark-mode")) {
            localStorage.setItem("darkMode", "enabled");
            iconEl.classList.remove("fa-moon-o");
            iconEl.classList.add("fa-sun-o");
        } else {
            localStorage.setItem("darkMode", "disabled");
            iconEl.classList.remove("fa-sun-o");
            iconEl.classList.add("fa-moon-o");
        }
    });

});
</script>
