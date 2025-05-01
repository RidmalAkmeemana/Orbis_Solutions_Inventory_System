<?php 
    require_once '../../API/Connection/config.php';

    // Fetch Company Name from the database
	$companyName = ""; // Default name if query fails

	$query = "SELECT * FROM tbl_company_info LIMIT 1"; 
	$result = mysqli_query($conn, $query);

	if ($result && mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$companyName = $row['Company_Name'];
        $companyAddress = $row['Company_Address'];
        $companyEmail = $row['Company_Email'];
        $companyTel1 = $row['Company_Tel1'];
        $companyTel2 = $row['Company_Tel2'];
        $companyTel3 = $row['Company_Tel3'];
	}

    $invoiceNo = $_REQUEST['Invoice_No'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title><?php echo($companyName); ?> - Invoice</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">

    <!-- Feathericon CSS -->
    <link rel="stylesheet" href="assets/css/feathericon.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <style>

        /* Styling for the signature lines */
        .signature-line {
            border: none;
            border-top: 1px solid #e8e8e8; /* Adjust color as needed */
            width: 150px; /* Increase this value to adjust width */
            margin-top: 20px;
        }

        /* Styling for the signature labels */
        .signature-label {
            text-align: center; /* Center the text under the line */
            margin-top: 5px; /* Adjust space between line and text */
        }

        /* Ensure proper alignment of elements */
        .mt-4 {
            margin-top: 1.5rem;
        }
        
        /* Print styles */
        @media print {
            .no-print {
                display: none;
            }
        }
		/* Black Back Button */
        .btn-back {
            background-color: black;
            color: white;
            border: none;
        }
        .btn-back:hover {
            background-color: #333;
            color: white;
        }
    </style>

    <!--[if lt IE 9]>
        <script src="assets/js/html5shiv.min.js"></script>
        <script src="assets/js/respond.min.js"></script>
    <![endif]-->
</head>

<body>
            <!-- Invoice Container -->
            <div class="invoice-container">
                <div class="row align-items-center"> <!-- Added align-items-center to vertically align content -->
                    <div class="m-b-20" style="margin-left:10px;">
                        <img alt="Logo" class="inv-logo img-fluid" src="assets/img/Print-logo.png">
                    </div>
                    <div class="mt-2 m-b-20" style="margin-left:20px;"> <!-- Adjusted margin-top to reduce space -->
                        <div class="invoice-details text-left">
                            <h1 class="text-uppercase font-weight-bold mb-1" style="font-family: Arial;">
                                <span><?php echo($companyName); ?></span>
                            </h1>
                            <h5 class="font-weight-bold mb-1"  style="font-family: Arial;">
                                <span>No: <?php echo($companyAddress); ?></span>
                            </h5>
                            <h6 class="font-weight-bold mb-1"  style="font-family: Arial;">
                                <?php $telNumbers = array_filter([$companyTel1, $companyTel2, $companyTel3]); ?>
                                <span>Email: <?php echo($companyEmail); ?>, <?php echo "Tel: " . implode(", ", $telNumbers); ?></span>
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-sm-12 m-b-20 d-flex justify-content-center"> <!-- Added d-flex and justify-content-center -->
                        <div class="invoice-details">
                            <h3 class="text-uppercase font-weight-bold mb-1">
                                <span  style="font-family: Arial;">Invoice</span>
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-sm-6 col-lg-7 col-xl-8 m-b-20">
                        <h6 class="mb-1">
                            <span class="font-weight-bold"  style="font-family: Arial; font-size: 17px;">Invoice No: </span>
                            <span id="invoice_No"  style="font-family: Arial; font-size: 17px;"></span>
                        </h6>
                        <h6 class="mb-1">
                            <span class="font-weight-bold"  style="font-family: Arial; font-size: 17px;">Invoice Date: </span>
                            <span id="invoice_Date"  style="font-family: Arial; font-size: 17px;"></span>
                        </h6>
                        <br>
                        <h6 class="mb-1">
                            <span class="font-weight-bold"  style="font-family: Arial; font-size: 17px;">Invoice To: </span>
                            <span id="customer_Name"  style="font-family: Arial; font-size: 17px;"></span>
                        </h6>
                    </div>
                    <div class="col-sm-6 col-lg-5 col-xl-4 m-b-20">
                        <h6 class="text-right">
                            <span class="font-weight-bold mb-1"  style="font-family: Arial; font-size: 17px;">Invoice By: </span>
                            <span  style="font-family: Arial; font-size: 17px;" id="user_Name"></span>
                        </h6>
                        <br>
                        <h6 class="text-right">
                            <span class="font-weight-bold mb-1"  style="font-family: Arial; font-size: 17px;">Sale Type: </span>
                            <span  style="font-family: Arial; font-size: 17px;" id="sale_Type"></span>
                        </h6>
                        <h6 class="text-right">
                            <span class="font-weight-bold mb-1"  style="font-family: Arial; font-size: 17px;">Status: </span>
                            <span  style="font-family: Arial; font-size: 17px;" id="status"></span>
                        </h6>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th  style="font-family: Arial; font-size: 17px;">Product Id</th>
                                <th  style="font-family: Arial; font-size: 17px;">Product Name</th>
                                <th  style="font-family: Arial; font-size: 17px;" class="text-center">Qty</th>
                                <th  style="font-family: Arial; font-size: 17px;" class="text-nowrap text-right">Unit Price</th>
                                <th  style="font-family: Arial; font-size: 17px;" class="text-nowrap text-right">Discount Price</th>
                                <th  style="font-family: Arial; font-size: 17px;" class="text-right">Total Price</th>
                            </tr>
                        </thead>
                        <tbody id="product_list"  style="font-family: Arial; font-size: 17px;">
                            <tr></tr>
                        </tbody>
                    </table>
                </div>

                <div class="row invoice-payment">
                    <div class="col-sm-12">
                        <div class="m-b-20">
                            <div class="table-responsive no-border">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th style="font-family: Arial; font-size: 17px;">
                                            Discount (LKR): <span id="discount_Total"></span>
                                        </th>
                                        <th style="font-family: Arial; font-size: 17px; float:right;">
                                            Sub Total (LKR): <span id="sub_Total"></span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="font-family: Arial; font-size: 17px;">
                                            Service Charge <span id="service_Charge"></span>
                                        </th>
                                        <th style="font-family: Arial; font-size: 17px; float:right;">
                                            GRAND TOTAL (LKR): <span id="grand_Total"></span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="font-family: Arial; font-size: 17px;">
                                            Tax <span id="tax"></span>
                                        </th>
                                        <th style="font-family: Arial; font-size: 17px; float:right;">
                                            Paid Amount (LKR): <span id="paid_Amount"></span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="font-family: Arial; font-size: 17px;">
                                            Vat <span id="vat"></span>
                                        </th>
                                        <th style="font-family: Arial; font-size: 17px; float:right;">
                                            Due (LKR): <span id="due_Amount"></span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="font-family: Arial; font-size: 17px;">
                                            Delivery Charge <span id="delivery_Charge"></span>
                                        </th>
                                        <th style="font-family: Arial; font-size: 17px; float:right;">
                                            Balance (LKR): <span id="balance_Amount"></span>
                                        </th>
                                    </tr>
                                    <tr></tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Left Column -->
                    <div class="col-sm-6 col-lg-6 m-b-20">
                        <h6 class="text-left">
                            <span  style="font-family: Arial; font-size: 17px;" class="font-weight-bold mb-1">Item Count: </span>
                            <span  style="font-family: Arial; font-size: 17px;" id="item_Count"></span>
                        </h6>
                        <h6 class="text-left">
                            <span  style="font-family: Arial; font-size: 17px;" class="font-weight-bold mb-1">Payment Method: </span>
                            <span  style="font-family: Arial; font-size: 17px;" id="payment_Type"></span>
                        </h6>
                        <h6 class="text-left">
                            <span  style="font-family: Arial; font-size: 17px;" class="font-weight-bold mb-1">Last Payment Date: </span>
                            <span  style="font-family: Arial; font-size: 17px;" id="payment_Date"></span>
                        </h6>
                    </div>

                    <!-- Right Column with Shop Lines and Authorized By & Received By -->
                    <div class="col-sm-6 col-lg-6 m-b-20">
                        <!-- Authorized By & Received By -->
                        <div class="mt-4">
                            <div class="d-flex justify-content-between">
                                <div class="text-left">
                                    <hr class="signature-line">
                                    <div  style="font-family: Arial; font-size: 17px;" class="signature-label">Authorized By</div>
                                </div>
                                <div class="text-right">
                                    <hr class="signature-line">
                                    <div  style="font-family: Arial; font-size: 17px;" class="signature-label">Received By</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="invoice-info">
                    <h5  style="font-family: Arial; font-size: 17px;" class="font-weight-bold mb-1">Description</h5>
                    <p  style="font-family: Arial; font-size: 17px;" id="invoice_Description" class="mb-0"></p>
                </div>

                <div class="row mt-4">
                    <div class="col-sm-12 m-b-20 d-flex justify-content-center"> <!-- Added d-flex and justify-content-center -->
                        <div class="invoice-details">
                            <p class="text-muted mb-0 text-center ">
                                <span style="font-family: Arial;">System Design By Orbis Solutions</span>
                            </p>
                            <p class="text-muted mb-0 text-center">
                                <span style="font-family: Arial;">Hotline: 077 369 7070, 071 209 8989, 076 857 6851</span>
                            </p>
                        </div>
                    </div>
                </div>

				<!-- Print Button -->
				<div class="no-print" style="text-align: center; margin-top:50px;">
					<button onclick="window.print();" class="btn btn-primary"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
   				</div>
            </div>
            <!-- /Invoice Container -->

    <!-- jQuery -->
    <script src="assets/js/jquery-3.2.1.min.js"></script>

    <!-- Bootstrap Core JS -->
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <!-- Slimscroll JS -->
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom JS -->
    <script src="assets/js/script.js"></script>

    <script>
        // Fetch invoice data from viewInvoiceCustomerCopy.php
        function fetchInvoiceData(invoiceId) {
            $.ajax({
                url: '../../API/Public/viewInvoiceDataCopy.php', // Update with actual path
                type: 'GET',
                data: { Invoice_Id: invoiceId },
                dataType: 'json',
                success: function(response) {
                    if (response.success === 'false') {
                        window.location.reload();
                    } else {
                        populateInvoiceData(response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ', status, error);
                }
            });
        }

        // Function to populate invoice data
        function populateInvoiceData(data) {
            const invoice = data.InvoiceData;
            const paymentDate = invoice.Payment_Date ? invoice.Payment_Date : 'N/A';
            const invoiceDecription = invoice.Description ? invoice.Description : 'N/A';

            document.title = 'Orbis Solutions - Invoice | ' + invoice.Invoice_No;
            document.getElementById('invoice_No').innerText = invoice.Invoice_No;
            document.getElementById('invoice_Date').innerText = invoice.Invoice_Date;
            document.getElementById('customer_Name').innerText = invoice.Customer_Name;
            document.getElementById('user_Name').innerText = invoice.First_Name + ' ' + invoice.Last_Name;
            document.getElementById('sale_Type').innerText = invoice.Sale_Type;
            document.getElementById('status').innerText = invoice.Status;
            document.getElementById('sub_Total').innerText = invoice.Sub_Total;
            document.getElementById('discount_Total').innerText = invoice.Discount_Total;


                            //Service Charge
                            if (invoice.ServiceCharge_IsPercentage === "1") {
                                
                                var serviceCharge = parseFloat(invoice.ServiceCharge);  // Ensure serviceCharge is treated as a float
                                
                                // Set the formatted service charge text in the #serviceCharge element
                                document.getElementById('service_Charge').innerText = invoice.ServiceCharge;
                                $('#service_Charge').text('(%): '+serviceCharge.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                })).show();

                            } else {

                                var serviceCharge = parseFloat(response.ServiceCharge);  // Ensure serviceCharge is treated as a float
                        
                                // Set the formatted service charge text in the #serviceCharge element
                                $('#service_Charge').text('(LKR): '+serviceCharge.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                })).show();
                            }

                            //Tax Charge
                            if (invoice.Tax_IsPercentage === "1") {
                                
                                var tax = parseFloat(invoice.Tax);  // Ensure tax is treated as a float
                                
                                // Set the formatted tax charge text in the #tax element
                                document.getElementById('tax').innerText = invoice.Tax;
                                $('#tax').text('(%): '+tax.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                })).show();

                            } else {

                                var tax = parseFloat(invoice.Tax);  // Ensure tax is treated as a float
                        
                                // Set the formatted tax charge text in the #tax element
                                $('#tax').text('(LKR): '+tax.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                })).show();
                            }

                            //Vat Charge
                            if (invoice.Vat_IsPercentage === "1") {
                                
                                var vat = parseFloat(invoice.Vat);  // Ensure vat is treated as a float
                                
                                // Set the formatted vat charge text in the #vat element
                                document.getElementById('vat').innerText = invoice.Tax;
                                $('#vat').text('(%): '+vat.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                })).show();

                            } else {

                                var vat = parseFloat(invoice.Vat);  // Ensure vat is treated as a float
                        
                                // Set the formatted vat charge text in the #vat element
                                $('#vat').text('(LKR): '+vat.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                })).show();
                            }

                            //Delivery Charge
                            if (invoice.Delivery_IsPercentage === "1") {
                                
                                var delivery = parseFloat(invoice.Delivery);  // Ensure delivery is treated as a float
                                
                                // Set the formatted delivery charge text in the #delivery_Charge element
                                document.getElementById('delivery_Charge').innerText = invoice.Delivery;
                                $('#delivery_Charge').text('(%): '+delivery.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                })).show();

                            } else {

                                var delivery = parseFloat(invoice.Delivery);  // Ensure delivery is treated as a float
                        
                                // Set the formatted delivery charge text in the #delivery_Charge element
                                $('#delivery_Charge').text('(LKR): '+delivery.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                })).show();
                            }

            document.getElementById('grand_Total').innerText = invoice.Grand_Total;
            document.getElementById('paid_Amount').innerText = invoice.Paid_Amount;
            document.getElementById('balance_Amount').innerText = invoice.Balance_Total;
            document.getElementById('due_Amount').innerText = invoice.Due_Total;
            document.getElementById('item_Count').innerText = invoice.Item_Count;
            document.getElementById('payment_Type').innerText = invoice.Payment_Type;
            document.getElementById('payment_Date').innerText = paymentDate;
            document.getElementById('invoice_Description').innerText = invoiceDecription;

            // Populate products
            const products = data.products;
            const productTable = document.getElementById('product_list');
            productTable.innerHTML = ''; // Clear previous rows

            products.forEach((product, index) => {
                const row = `
                    <tr>
                        <td>${product.Product_Id}</td>
                        <td>${product.Product_Name}</td>
                        <td class="text-center">${product.Qty}</td>
                        <td class="text-right">LKR: ${product.Unit_Price}</td>
                        <td class="text-right">LKR: ${product.Unit_Discount}</td>
                        <td class="text-right">LKR: ${product.Product_Total_Price}</td>
                    </tr>
                    <tr></tr>
                `;
                productTable.innerHTML += row;
            });
        }

        // Example: Fetch and populate invoice data for a specific Invoice_Id
        const invoiceId = '<?php echo $invoiceNo; ?>'; // Replace with actual Invoice_Id
        fetchInvoiceData(invoiceId);
    </script>
</body>

</html>
