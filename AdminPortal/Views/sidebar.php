<?php
    $currentPage = basename($_SERVER['PHP_SELF']); // Get the current page name

    $query = mysqli_query($conn, "SELECT * FROM `tbl_user` WHERE `username` = '$_SESSION[user]'") or die(mysqli_error());
    $fetch = mysqli_fetch_array($query);
    $role = $fetch['Status'];

    function hasPermission($role, $screen, $conn) {
        $screenQuery = mysqli_query($conn, "SELECT Screen_Id FROM `tbl_screens` WHERE `Screen_Name` = '$screen'") or die(mysqli_error());
        if (mysqli_num_rows($screenQuery) > 0) {
            $screenFetch = mysqli_fetch_array($screenQuery);
            $screenId = $screenFetch['Screen_Id'];

            $permissionQuery = mysqli_query($conn, "
                SELECT * 
                FROM `tbl_screen_permissions` 
                WHERE `Role` = '$role' AND `Screen_Id` = '$screenId'
            ") or die(mysqli_error());

            return mysqli_num_rows($permissionQuery) > 0;
        }
        return false;
    }
?>

<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="<?php echo ($currentPage == 'home.php') ? 'active' : ''; ?>"> 
                    <a href="home.php"><i class="fa fa-tachometer"></i> <span>Dashboard</span></a>
                </li>

                <p class="menu-title" style="color:#949494;"><span>Main Pages</span></p>

                <?php if (hasPermission($role, 'add_suppliers.php', $conn)) { ?>
                <li class="<?php echo ($currentPage == 'add_suppliers.php' || $currentPage == 'view_supplier.php') ? 'active' : ''; ?>"> 
                    <a href="add_suppliers.php"><i class="fa fa-users"></i> <span>Suppliers</span></a>
                </li>
                <?php } ?>

                <?php if (hasPermission($role, 'add_brand.php', $conn)) { ?>
                <li class="<?php echo ($currentPage == 'add_brand.php' || $currentPage == 'view_brand.php') ? 'active' : ''; ?>"> 
                    <a href="add_brand.php"><i class="fa fa-ravelry" aria-hidden="true"></i> <span>Brands</span></a>
                </li>
                <?php } ?>

                <?php if (hasPermission($role, 'add_categories.php', $conn)) { ?>
                <li class="<?php echo ($currentPage == 'add_categories.php' || $currentPage == 'view_category.php') ? 'active' : ''; ?>"> 
                    <a href="add_categories.php"><i class="fa fa-sliders"></i> <span>Categories</span></a>
                </li>
                <?php } ?>

                <?php if (hasPermission($role, 'add_products.php', $conn)) { ?>
                <li class="<?php echo ($currentPage == 'add_products.php' || $currentPage == 'view_product.php') ? 'active' : ''; ?>"> 
                    <a href="add_products.php"><i class="fa fa-shopping-bag"></i> <span>Products</span></a>
                </li>
                <?php } ?>

                <?php if (hasPermission($role, 'add_product-history.php', $conn)) { ?>
                <li class="<?php echo ($currentPage == 'add_product-history.php' || $currentPage == 'view_product-details.php') ? 'active' : ''; ?>"> 
                    <a href="add_product-history.php"><i class="fa fa-history"></i> <span>Product Details</span></a>
                </li>
                <?php } ?>

                <?php if (hasPermission($role, 'add_expenses_types.php', $conn)) { ?>
                <li class="<?php echo ($currentPage == 'add_expenses_types.php' || $currentPage == 'view_expenses_types.php') ? 'active' : ''; ?>"> 
                    <a href="add_expenses_types.php"><i class="fa fa-usd"></i> <span>Expenses Types</span></a>
                </li>
                <?php } ?>

                <?php if (hasPermission($role, 'add_customers.php', $conn)) { ?>
                <li class="<?php echo ($currentPage == 'add_customers.php' || $currentPage == 'view_customer.php') ? 'active' : ''; ?>"> 
                    <a href="add_customers.php"><i class="fa fa-users"></i> <span>Customers</span></a>
                </li>
                <?php } ?>

                <?php if (hasPermission($role, 'add_roles.php', $conn)) { ?>
                <li class="<?php echo ($currentPage == 'add_roles.php' || $currentPage == 'view_role.php') ? 'active' : ''; ?>"> 
                    <a href="add_roles.php"><i class="fa fa-id-card-o"></i> <span>User Roles</span></a>
                </li>
                <?php } ?>

                <?php if (hasPermission($role, 'add_users.php', $conn)) { ?>
                <li class="<?php echo ($currentPage == 'add_users.php' || $currentPage == 'view_user.php') ? 'active' : ''; ?>"> 
                    <a href="add_users.php"><i class="fa fa-user-secret"></i> <span>Users</span></a>
                </li>
                <?php } ?>

                <p class="menu-title" style="color:#949494;"><span>POS System</span></p>

                <?php if (hasPermission($role, 'pos.php', $conn)) { ?>
                <li class="<?php echo ($currentPage == 'pos.php' || $currentPage == 'pos2.php') ? 'active' : ''; ?>"> 
                    <a href="pos.php"><i class="fa fa-money"></i> <span>POS</span></a>
                </li>
                <?php } ?>

                <p class="menu-title" style="color:#949494;"><span>Expenses</span></p>

                <?php if (hasPermission($role, 'add_expenses.php', $conn)) { ?>
                <li class="<?php echo ($currentPage == 'add_expenses.php' || $currentPage == 'view_expenses.php') ? 'active' : ''; ?>"> 
                    <a href="add_expenses.php"><i class="fa fa-tags"></i> <span>Expenses</span></a>
                </li>
                <?php } ?>

                <?php if (hasPermission($role, 'reverse_expense.php', $conn)) { ?>
                <li class="<?php echo ($currentPage == 'reverse_expense.php') ? 'active' : ''; ?>"> 
                    <a href="reverse_expense.php"><i class="fa fa-undo"></i> <span>Reverse Expenses</span></a>
                </li>
                <?php } ?>

                <?php if (hasPermission($role, 'expense_reverse-history.php', $conn)) { ?>
                <li class="<?php echo ($currentPage == 'expense_reverse-history.php') ? 'active' : ''; ?>"> 
                    <a href="expense_reverse-history.php"><i class="fa fa-history"></i> <span>Reversal History</span></a>
                </li>
                <?php } ?>

                <p class="menu-title" style="color:#949494;"><span>Invoices</span></p>

                <?php if (hasPermission($role, 'sales_invoice.php', $conn)) { ?>
                <li class="<?php echo ($currentPage == 'sales_invoice.php' || $currentPage == 'view_invoice.php') ? 'active' : ''; ?>"> 
                    <a href="sales_invoice.php"><i class="fa fa-file"></i> <span>Sales Invoice</span></a>
                </li>
                <?php } ?>

                <?php if (hasPermission($role, 'reverse_payment.php', $conn)) { ?>
                <li class="<?php echo ($currentPage == 'reverse_payment.php') ? 'active' : ''; ?>"> 
                    <a href="reverse_payment.php"><i class="fa fa-undo"></i> <span>Reverse Payments</span></a>
                </li>
                <?php } ?>

                <?php if (hasPermission($role, 'reverse-history.php', $conn)) { ?>
                <li class="<?php echo ($currentPage == 'reverse-history.php') ? 'active' : ''; ?>"> 
                    <a href="reverse-history.php"><i class="fa fa-history"></i> <span>Reversal History</span></a>
                </li>
                <?php } ?>

                <?php if (hasPermission($role, 'return_invoice.php', $conn)) { ?>
                <li class="<?php echo ($currentPage == 'return_invoice.php') ? 'active' : ''; ?>"> 
                    <a href="return_invoice.php"><i class="fa fa-file"></i> <span>Return Invoice</span></a>
                </li>
                <?php } ?>

                <p class="menu-title" style="color:#949494;"><span>Cash Flow</span></p>

                <?php if (hasPermission($role, 'cash_flow.php', $conn)) { ?>
                <li class="<?php echo ($currentPage == 'cash_flow.php') ? 'active' : ''; ?>"> 
                    <a href="cash_flow.php"><i class="fa fa-sitemap"></i> <span>Cash Flow</span></a>
                </li>
                <?php } ?>

                <p class="menu-title" style="color:#949494;"><span>Reports</span></p>

                <?php if (hasPermission($role, 'inventory_report.php', $conn)) { ?>
                <li class="<?php echo ($currentPage == 'inventory_report.php') ? 'active' : ''; ?>"> 
                    <a href="inventory_report.php"><i class="fa fa-file"></i> <span>Inventory Report</span></a>
                </li>
                <?php } ?>

                <?php if (hasPermission($role, 'sales_report.php', $conn)) { ?>
                <li class="<?php echo ($currentPage == 'sales_report.php') ? 'active' : ''; ?>"> 
                    <a href="sales_report.php"><i class="fa fa-file"></i> <span>Sales Report</span></a>
                </li>
                <?php } ?>

                <p class="menu-title" style="color:#949494;"><span>Configuration</span></p>

                <?php if (hasPermission($role, 'settings.php', $conn)) { ?>
                <li class="<?php echo ($currentPage == 'settings.php') ? 'active' : ''; ?>"> 
                    <a href="settings.php"><i class="fa fa-cogs"></i> <span>System Information</span></a>
                </li>
                <?php } ?>

            </ul>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const activeItem = document.querySelector('.sidebar-menu li.active');
        if (activeItem) {
            const sidebarInner = document.querySelector('.sidebar-inner');
            const offsetTop = activeItem.offsetTop;
            sidebarInner.scrollTop = offsetTop - 100; // Adjust as needed
        }
    });
</script>
