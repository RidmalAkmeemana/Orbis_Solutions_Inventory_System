<?php
include '../../API/Connection/uploadurl.php';
require_once '../../API/Connection/validator.php';
require_once '../../API/Connection/config.php';
require_once '../../API/Connection/ScreenPermission.php';

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
    <title><?php echo ($companyName); ?> - Invoice</title>

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
            border-top: 1px solid #e8e8e8;
            /* Adjust color as needed */
            width: 150px;
            /* Increase this value to adjust width */
            margin-top: 20px;
        }

        /* Styling for the signature labels */
        .signature-label {
            text-align: center;
            /* Center the text under the line */
            margin-top: 5px;
            /* Adjust space between line and text */
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

        /* Full-Screen Loader */
        #pageLoader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        /* Spinner Animation */
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #be3235;
            border-top: 5px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Full-Screen Loader */
    </style>

    <!--[if lt IE 9]>
        <script src="assets/js/html5shiv.min.js"></script>
        <script src="assets/js/respond.min.js"></script>
    <![endif]-->
</head>

<body>

    <!-- Full-Screen Loader -->
    <div id="pageLoader">
        <div class="loader-content" style="display: flex; flex-direction: column; align-items: center;">
            <div class="spinner"></div>
            <div style="margin-top: 10px; font-size: 16px;">Loading . . .</div>
        </div>
    </div>
    <!-- /Full-Screen Loader -->

    <!-- Model Alerts -->
    <div class="modal fade" id="EmailSuccessModel" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-body mt-4">
                    <i class="fa fa-check-circle animate__animated animate__tada animate__infinite" style="font-size: 100px; margin-top:20px; color:#26af48;" aria-hidden="true"></i>
                    <h3 class="modal-title"><b>Success</b></h3>
                    <p>Customer Email Sent Successfully !</p>
                </div>
                <div class="modal-body">
                    <button style="width:20%;" type="button" class="btn btn-primary" id="OkBtn" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="SMSSuccessModel" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-body mt-4">
                    <i class="fa fa-check-circle animate__animated animate__tada animate__infinite" style="font-size: 100px; margin-top:20px; color:#26af48;" aria-hidden="true"></i>
                    <h3 class="modal-title"><b>Success</b></h3>
                    <p>Customer SMS Sent Successfully !</p>
                </div>
                <div class="modal-body">
                    <button style="width:20%;" type="button" class="btn btn-primary" id="OkBtn" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="EmailErrorModel" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-body mt-4">
                    <i class="fa fa-exclamation-circle animate__animated animate__tada animate__infinite" style="font-size: 100px; margin-top:20px; color:#e63c3c;" aria-hidden="true"></i>
                    <h3 class="modal-title"><b>Error</b></h3>
                    <p>Customer Email Not Available !</p>
                </div>
                <div class="modal-body">
                    <button style="width:20%;" type="button" class="btn btn-primary" id="OkBtn1" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="SentSMSFailedModel" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-body mt-4">
                    <i class="fa fa-exclamation-circle animate__animated animate__tada animate__infinite" style="font-size: 100px; margin-top:20px; color:#e63c3c;" aria-hidden="true"></i>
                    <h3 class="modal-title"><b>Error</b></h3>
                    <p>Customer SMS Sent Failed !</p>
                </div>
                <div class="modal-body">
                    <button style="width:20%;" type="button" class="btn btn-primary" id="OkBtn1" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="SMSAlreadySendModel" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-body mt-4">
                    <i class="fa fa-exclamation-circle animate__animated animate__tada animate__infinite" style="font-size: 100px; margin-top:20px; color:#e63c3c;" aria-hidden="true"></i>
                    <h3 class="modal-title"><b>Error</b></h3>
                    <p>Customer SMS Already Sent !</p>
                </div>
                <div class="modal-body">
                    <button style="width:20%;" type="button" class="btn btn-primary" id="OkBtn1" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="AlertErrorModel" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-body mt-4">
                    <i class="fa fa-exclamation-circle animate__animated animate__tada animate__infinite" style="font-size: 100px; margin-top:20px; color:#e63c3c;" aria-hidden="true"></i>
                    <h3 class="modal-title"><b>Error</b></h3>
                    <p>Invoice Data Is Not Loaded yet !</p>
                </div>
                <div class="modal-body">
                    <button style="width:20%;" type="button" class="btn btn-primary" id="OkBtn1" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="SentFailedModel" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-body mt-4">
                    <i class="fa fa-exclamation-circle animate__animated animate__tada animate__infinite" style="font-size: 100px; margin-top:20px; color:#e63c3c;" aria-hidden="true"></i>
                    <h3 class="modal-title"><b>Error</b></h3>
                    <p>Customer Email Sent Failed !</p>
                </div>
                <div class="modal-body">
                    <button style="width:20%;" type="button" class="btn btn-primary" id="OkBtn1" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Model Alerts -->

    <!-- Invoice Container -->
    <div class="invoice-container">
        <div class="row align-items-center"> <!-- Added align-items-center to vertically align content -->
            <div class="m-b-20" style="margin-left:10px;">
                <img alt="Logo" class="inv-logo img-fluid" src="assets/img/Print-logo.png">
            </div>
            <div class="mt-2 m-b-20" style="margin-left:20px;"> <!-- Adjusted margin-top to reduce space -->
                <div class="invoice-details text-left">
                    <h1 class="text-uppercase font-weight-bold mb-1" style="font-family: Arial;">
                        <span><?php echo ($companyName); ?></span>
                    </h1>
                    <h5 class="font-weight-bold mb-1" style="font-family: Arial;">
                        <span>No: <?php echo ($companyAddress); ?></span>
                    </h5>
                    <h6 class="font-weight-bold mb-1" style="font-family: Arial;">
                        <?php $telNumbers = array_filter([$companyTel1, $companyTel2, $companyTel3]); ?>
                        <span>Email: <?php echo ($companyEmail); ?>, <?php echo "Tel: " . implode(", ", $telNumbers); ?></span>
                    </h6>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-sm-12 m-b-20 d-flex justify-content-center"> <!-- Added d-flex and justify-content-center -->
                <div class="invoice-details">
                    <h3 class="text-uppercase font-weight-bold mb-1">
                        <span style="font-family: Arial;">Invoice</span>
                    </h3>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-sm-6 col-lg-7 col-xl-8 m-b-20">
                <h6 class="mb-1">
                    <span class="font-weight-bold" style="font-family: Arial; font-size: 17px;">Invoice No: </span>
                    <span id="invoice_No" style="font-family: Arial; font-size: 17px;"></span>
                </h6>
                <h6 class="mb-1">
                    <span class="font-weight-bold" style="font-family: Arial; font-size: 17px;">Invoice Date: </span>
                    <span id="invoice_Date" style="font-family: Arial; font-size: 17px;"></span>
                </h6>
                <br>
                <h6 class="mb-1">
                    <span class="font-weight-bold" style="font-family: Arial; font-size: 17px;">Invoice To: </span>
                    <span id="customer_Name" style="font-family: Arial; font-size: 17px;"></span>
                </h6>
            </div>
            <div class="col-sm-6 col-lg-5 col-xl-4 m-b-20">
                <h6 class="text-right">
                    <span class="font-weight-bold mb-1" style="font-family: Arial; font-size: 17px;">Invoice By: </span>
                    <span style="font-family: Arial; font-size: 17px;" id="user_Name"></span>
                </h6>
                <br>
                <h6 class="text-right">
                    <span class="font-weight-bold mb-1" style="font-family: Arial; font-size: 17px;">Sale Type: </span>
                    <span style="font-family: Arial; font-size: 17px;" id="sale_Type"></span>
                </h6>
                <h6 class="text-right">
                    <span class="font-weight-bold mb-1" style="font-family: Arial; font-size: 17px;">Status: </span>
                    <span style="font-family: Arial; font-size: 17px;" id="status"></span>
                </h6>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="font-family: Arial; font-size: 17px;">Product Id</th>
                        <th style="font-family: Arial; font-size: 17px;">Product Name</th>
                        <th style="font-family: Arial; font-size: 17px;" class="text-center">Qty</th>
                        <th style="font-family: Arial; font-size: 17px;" class="text-nowrap text-right">Unit Price</th>
                        <th style="font-family: Arial; font-size: 17px;" class="text-nowrap text-right">Discount Price</th>
                        <th style="font-family: Arial; font-size: 17px;" class="text-right">Total Price</th>
                    </tr>
                </thead>
                <tbody id="product_list" style="font-family: Arial; font-size: 17px;">
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
                    <span style="font-family: Arial; font-size: 17px;" class="font-weight-bold mb-1">Item Count: </span>
                    <span style="font-family: Arial; font-size: 17px;" id="item_Count"></span>
                </h6>
                <h6 class="text-left">
                    <span style="font-family: Arial; font-size: 17px;" class="font-weight-bold mb-1">Payment Method: </span>
                    <span style="font-family: Arial; font-size: 17px;" id="payment_Type"></span>
                </h6>
                <h6 class="text-left">
                    <span style="font-family: Arial; font-size: 17px;" class="font-weight-bold mb-1">Last Payment Date: </span>
                    <span style="font-family: Arial; font-size: 17px;" id="payment_Date"></span>
                </h6>
            </div>

            <!-- Right Column with Shop Lines and Authorized By & Received By -->
            <div class="col-sm-6 col-lg-6 m-b-20">
                <!-- Authorized By & Received By -->
                <div class="mt-4">
                    <div class="d-flex justify-content-between">
                        <div class="text-left">
                            <hr class="signature-line">
                            <div style="font-family: Arial; font-size: 17px;" class="signature-label">Authorized By</div>
                        </div>
                        <div class="text-right">
                            <hr class="signature-line">
                            <div style="font-family: Arial; font-size: 17px;" class="signature-label">Received By</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="invoice-info">
            <h5 style="font-family: Arial; font-size: 17px;" class="font-weight-bold mb-1">Description</h5>
            <p style="font-family: Arial; font-size: 17px;" id="invoice_Description" class="mb-0"></p>
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
            <button onclick="window.history.back();" class="btn btn-back"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button>
            <button onclick="window.print();" class="btn btn-primary"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
            <button onclick="sendInvoiceEmail(this)" class="btn btn-info"> <i class="fa fa-share" aria-hidden="true"></i> Send Email </button>
            <button onclick="sendInvoiceSMS(this)" class="btn btn-warning"> <i class="fa fa-commenting-o" aria-hidden="true"></i> Send SMS </button>
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
        // Global variable to store invoice data        
        let invoiceData = null;
        let CompanyName = '';
        let CompanyAddress = '';
        let CompanyEmail = '';
        let CompanyTel1 = 'N/A';
        let CompanyTel2 = 'N/A';
        let CompanyTel3 = 'N/A';

        function fetchCompanyDetails() {
            $.ajax({
                type: 'GET',
                url: '../../API/Admin/getCompanyDetails.php',
                dataType: 'json',
                success: function(response) {
                    CompanyName = response.Company_Name;
                    CompanyAddress = response.Company_Address;
                    CompanyEmail = response.Company_Email;
                    CompanyTel1 = response.Company_Tel1 && response.Company_Tel1.trim() !== '' ? response.Company_Tel1 : 'N/A';
                    CompanyTel2 = response.Company_Tel2 && response.Company_Tel2.trim() !== '' ? response.Company_Tel2 : 'N/A';
                    CompanyTel3 = response.Company_Tel3 && response.Company_Tel3.trim() !== '' ? response.Company_Tel3 : 'N/A';
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching company details:', status, error);
                }
            });
        }

        // Fetch invoice data from viewInvoiceDataCopy.php
        function fetchInvoiceData(invoiceId) {
            $.ajax({
                url: '../../API/Admin/viewInvoiceDataCopy.php', // Update with actual path
                type: 'GET',
                data: {
                    Invoice_Id: invoiceId
                },
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

        fetchCompanyDetails();

        // Utility function to clean and format numbers
        function formatAmount(value) {
            var cleanValue = value ? value.toString().replace(/,/g, '') : '0';
            return parseFloat(cleanValue).toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // Function to populate invoice data
        function populateInvoiceData(data) {

            invoiceData = data;

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


            // Service Charge
            if (invoice.ServiceCharge_IsPercentage === "1") {
                $('#service_Charge').text('(%): ' + formatAmount(invoice.ServiceCharge)).show();
            } else {
                $('#service_Charge').text('(LKR): ' + formatAmount(invoice.ServiceCharge)).show();
            }

            // Tax Charge
            if (invoice.Tax_IsPercentage === "1") {
                $('#tax').text('(%): ' + formatAmount(invoice.Tax)).show();
            } else {
                $('#tax').text('(LKR): ' + formatAmount(invoice.Tax)).show();
            }

            // VAT Charge
            if (invoice.Vat_IsPercentage === "1") {
                $('#vat').text('(%): ' + formatAmount(invoice.Vat)).show();
            } else {
                $('#vat').text('(LKR): ' + formatAmount(invoice.Vat)).show();
            }

            // Delivery Charge
            if (invoice.Delivery_IsPercentage === "1") {
                $('#delivery_Charge').text('(%): ' + formatAmount(invoice.Delivery)).show();
            } else {
                $('#delivery_Charge').text('(LKR): ' + formatAmount(invoice.Delivery)).show();
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

        function sendInvoiceEmail(buttonElement) {
            // Ensure invoiceData is fetched first
            if (!invoiceData) {
                $('#AlertErrorModel').modal('show');
                return;
            }

            // Get Invoice No dynamically from the button's data-invoice-no attribute
            const invoiceNo = invoiceData.InvoiceData.Invoice_No;
            const customerName = invoiceData.InvoiceData.Customer_Name;
            const customerEmail = invoiceData.InvoiceData.Customer_Email; // Example: dynamically fetched from invoiceData
            const invoiceLink = `<?php echo $base_url ?>Public/Views/emailInvoiceCustomerCopy.php?Invoice_No=${invoiceNo}`;

            // If no email address, show a message and stop sending
            if (!customerEmail) {
                $('#EmailErrorModel').modal('show');
                return;
            }

            // Email subject and content
            const emailSubject = `Customer Invoice - ${invoiceNo}`;
            const emailBody = `
                <b>Dear ${customerName},</b><br><br>
                Please click on the link below to download your invoice:<br>
                <a href="${invoiceLink}" target="_blank">${invoiceLink}</a><br><br>
                <b>Thanks & Regards,</b><br>
                <h4>${CompanyName}</h4>
                <b>Address: </b>${CompanyAddress}<br>
                <b>Email: </b>${CompanyEmail}<br>
                <b>Contact No: </b>${CompanyTel1} | ${CompanyTel2} | ${CompanyTel3}
            `;

            // Send email via AJAX (implement sendEmail on server)
            sendEmail(CompanyEmail, CompanyName, customerEmail, emailSubject, emailBody);
        }

        // Example sendEmail function using AJAX
        function sendEmail(from, name, to, subject, body) {

            $('#pageLoader').show(); // Show loader before sending

            $.ajax({
                url: '../../sendEmail.php', // Update to your actual email sending endpoint
                type: 'POST',
                data: {
                    from: from,
                    name: name,
                    to: to,
                    subject: subject,
                    body: body
                },
                success: function(response) {
                    if (response.success === true) {
                        $('#EmailSuccessModel').modal('show');
                    } else {
                        $('#SentFailedModel').modal('show');
                    }
                },
                error: function(xhr, status, error) {
                    $('#SentFailedModel').modal('show');
                },
                complete: function() {
                    $('#pageLoader').hide(); // Hide loader after response (success or error)
                }
            });
        }

        function sendInvoiceSMS(buttonElement) {

            if (!invoiceData && !CompanyName) {
                $('#AlertErrorModel').modal('show');
                return;
            }

            const invoiceNo = invoiceData.InvoiceData.Invoice_No;
            const customerName = invoiceData.InvoiceData.Customer_Name;
            const contactNo = invoiceData.InvoiceData.Customer_Contact;

            if (!contactNo) {
                $('#SentSMSFailedModel').modal('show');
                return;
            }

            const invoiceLink = `<?php echo $base_url ?>Public/Views/emailInvoiceCustomerCopy.php?Invoice_No=${invoiceNo}`;
            const smsKey = 'smsSent_' + invoiceNo;

            if (sessionStorage.getItem(smsKey)) {
                $('#SMSAlreadySendModel').modal('show');
                return;
            }

            const message =
                `Dear ${customerName},\n` +
                `Download your invoice here:\n` +
                `${invoiceLink}\n\n` +
                `${CompanyName}\n` +
                `Contact No: ${CompanyTel1} | ${CompanyTel2} | ${CompanyTel3}`;

            sendSMS(contactNo, message, smsKey);
        }

        // Example sendEmail function using AJAX
        function sendSMS(to, body, smsKey) {

            $('#pageLoader').show();

            $.ajax({
                url: '../../sendSMS.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    to: to,
                    body: body
                },
                success: function(response) {

                    console.log('SMS Response:', response);

                    if (response.success === true) {
                        sessionStorage.setItem(smsKey, '1');
                        $('#SMSSuccessModel').modal('show');
                    } else {
                        $('#SentSMSFailedModel').modal('show');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('SMS AJAX Error:', error);
                    $('#SentSMSFailedModel').modal('show');
                },
                complete: function() {
                    $('#pageLoader').hide();
                }
            });
        }

        // Example: Fetch and populate invoice data for a specific Invoice_Id
        const invoiceId = '<?php echo $invoiceNo; ?>'; // Replace with actual Invoice_Id
        fetchInvoiceData(invoiceId);
    </script>

    <!-- Loader Script -->
    <script>
        let startTime = performance.now(); // Capture the start time when the page starts loading

        window.addEventListener("load", function() {
            let endTime = performance.now(); // Capture the end time when the page is fully loaded
            let loadTime = endTime - startTime; // Calculate the total loading time

            // Ensure the loader stays for at least 500ms but disappears dynamically based on actual load time
            let delay = Math.max(loadTime);

            setTimeout(function() {
                document.getElementById("pageLoader").style.display = "none";
            }, delay);
        });
    </script>
    <!-- /Loader Script -->
</body>

</html>