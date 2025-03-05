-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 02, 2025 at 05:31 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_orbis_solutions_inventory_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_backend`
--

CREATE TABLE `tbl_backend` (
  `Backend_Id` int(11) NOT NULL,
  `Backend_Name` varchar(100) NOT NULL,
  `Screen_Category` varchar(20) NOT NULL,
  `Screen_Sub_Category` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_backend`
--

INSERT INTO `tbl_backend` (`Backend_Id`, `Backend_Name`, `Screen_Category`, `Screen_Sub_Category`) VALUES
(100, 'addNewCategory.php', 'Categories', 'Add'),
(101, 'addNewCustomer.php', 'Customers', 'Add'),
(102, 'addNewProduct.php', 'Products', 'Add'),
(103, 'addNewSupplier.php', 'Suppliers', 'Add'),
(104, 'deleteCategory.php', 'Categories', 'Delete'),
(105, 'deleteCustomer.php', 'Customers', 'Delete'),
(106, 'deleteDetails.php', 'Product Details', 'Delete'),
(107, 'deleteProduct.php', 'Products', 'Delete'),
(108, 'deleteSupplier.php', 'Suppliers', 'Delete'),
(109, 'getAllCategoryData.php', 'Categories', 'View'),
(110, 'getAllCustomerData.php', 'Customers', 'View'),
(111, 'getAllProductData.php', 'Products', 'View'),
(112, 'getAllProductHistoryData.php', 'Product Details', 'View'),
(113, 'getAllSupplierData.php', 'Suppliers', 'View'),
(114, 'removeInventory.php', 'Inventories', 'Delete'),
(115, 'updateCategory.php', 'Categories', 'Edit'),
(116, 'updateCustomer.php', 'Customers', 'Edit'),
(117, 'updateDetails.php', 'Product Details', 'Edit'),
(118, 'updateProduct.php', 'Products', 'Edit'),
(119, 'updateSupplier.php', 'Suppliers', 'Edit'),
(120, 'viewCategoryData.php', 'Categories', 'View'),
(121, 'viewCustomerData.php', 'Customers', 'View'),
(122, 'viewProductData.php', 'Products', 'View'),
(123, 'viewProductDetails.php', 'Product Details', 'View'),
(124, 'viewSupplierData.php', 'Suppliers', 'View'),
(125, 'addNewRole.php', 'Roles', 'Add'),
(126, 'getAllRoleData.php', 'Roles', 'View'),
(127, 'updateRole.php', 'Roles', 'Edit'),
(128, 'deleteRole.php', 'Roles', 'Delete'),
(129, 'viewRoleData.php', 'Roles', 'View'),
(130, 'savePermissions.php', 'Roles', 'Add'),
(131, 'addInventory.php', 'Inventories', 'Add'),
(132, 'addNewUser.php', 'Users', 'Add'),
(133, 'getAllUserData.php', 'Users', 'View'),
(134, 'updateUser.php', 'Users', 'Edit'),
(135, 'deleteUser.php', 'Users', 'Delete'),
(136, 'viewUserData.php', 'Users', 'View'),
(138, 'searchProductData.php', 'POS', 'View'),
(139, 'saveInvoice.php', 'POS', 'View'),
(140, 'viewInvoiceData.php', 'POS', 'View'),
(141, 'viewInvoiceDataCopy.php', 'Invoice', 'View'),
(142, 'getAllInvoiceData.php', 'Invoice', 'View'),
(143, 'updateInvoice.php', 'Invoice', 'Payment'),
(144, 'deleteInvoice.php', 'Invoice', 'Return'),
(145, 'viewInvoiceDetails.php', 'Invoice', 'View'),
(146, 'getInventoryReportData.php', 'Reports', 'Inventory Report'),
(147, 'getSalesReportData.php', 'Reports', 'Sales Report'),
(148, 'addNewBrand.php', 'Brands', 'Add'),
(149, 'getAllBrandData.php', 'Brands', 'View'),
(150, 'viewBrandData.php', 'Brands', 'View'),
(151, 'updateBrand.php', 'Brands', 'Edit'),
(152, 'deleteBrand.php', 'Brands', 'Delete'),
(153, 'searchCustomer.php', 'POS', 'View'),
(154, 'addNewExpenseType.php', 'Expenses Types', 'Add'),
(155, 'getAllExpenseTypeData.php', 'Expenses Types', 'View'),
(156, 'viewExpenseTypeData.php', 'Expenses Types', 'View'),
(157, 'updateExpensesTypes.php', 'Expenses Types', 'Edit'),
(158, 'deleteExpensesTypes.php', 'Expenses Types', 'Delete'),
(159, 'reversePayment.php', 'Reverse Payment', 'View'),
(160, 'addNewExpense.php', 'Expenses', 'Add'),
(161, 'getAllExpenseData.php', 'Expenses', 'View'),
(162, 'viewExpenseData.php', 'Expenses', 'View'),
(163, 'updateExpenses.php', 'Expenses', 'Edit'),
(164, 'deleteExpenses.php', 'Expenses', 'Delete'),
(165, 'expensePayment.php', 'Expenses', 'Payment'),
(166, 'reverseExpensePayment.php', 'Reverse Expenses', 'View'),
(167, 'getAllInvoicePaymentData.php', 'Reverse Payment', 'View'),
(168, 'getAllExpensePaymentData.php', 'Reverse Expense', 'View'),
(169, 'getAllCashFlowData.php', 'Cash Flow', 'View'),
(170, 'returnInvoice.php', 'Return Invoice', 'View'),
(171, 'getProductDetails.php', 'Products', 'Add'),
(172, 'getCompanyDetails.php', 'System Information', 'View'),
(173, 'updateCompany.php', 'System Information', 'View'),
(174, 'getSystemConfiguration.php', 'System Information', 'View'),
(175, 'updateConfiguration.php', 'System Information', 'View');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_backend_permissions`
--

CREATE TABLE `tbl_backend_permissions` (
  `Permission_Id` int(11) NOT NULL,
  `Role` varchar(50) NOT NULL,
  `Backend_Id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_backend_permissions`
--

INSERT INTO `tbl_backend_permissions` (`Permission_Id`, `Role`, `Backend_Id`) VALUES
(779, 'Super Admin', 125),
(780, 'Super Admin', 126),
(781, 'Super Admin', 127),
(782, 'Super Admin', 128),
(783, 'Super Admin', 129),
(784, 'Super Admin', 130),
(785, 'Super Admin', 100),
(786, 'Super Admin', 104),
(787, 'Super Admin', 109),
(788, 'Super Admin', 120),
(789, 'Super Admin', 115),
(790, 'Super Admin', 101),
(791, 'Super Admin', 105),
(792, 'Super Admin', 110),
(793, 'Super Admin', 121),
(794, 'Super Admin', 116),
(795, 'Super Admin', 102),
(796, 'Super Admin', 171),
(797, 'Super Admin', 107),
(798, 'Super Admin', 111),
(799, 'Super Admin', 122),
(800, 'Super Admin', 118),
(801, 'Super Admin', 103),
(802, 'Super Admin', 108),
(803, 'Super Admin', 113),
(804, 'Super Admin', 124),
(805, 'Super Admin', 119),
(806, 'Super Admin', 106),
(807, 'Super Admin', 112),
(808, 'Super Admin', 123),
(809, 'Super Admin', 117),
(810, 'Super Admin', 114),
(811, 'Super Admin', 131),
(812, 'Super Admin', 132),
(813, 'Super Admin', 133),
(814, 'Super Admin', 136),
(815, 'Super Admin', 134),
(816, 'Super Admin', 135),
(817, 'Super Admin', 138),
(818, 'Super Admin', 139),
(819, 'Super Admin', 140),
(820, 'Super Admin', 153),
(821, 'Super Admin', 141),
(822, 'Super Admin', 142),
(823, 'Super Admin', 145),
(824, 'Super Admin', 143),
(825, 'Super Admin', 144),
(826, 'Super Admin', 146),
(828, 'Super Admin', 148),
(829, 'Super Admin', 149),
(830, 'Super Admin', 150),
(831, 'Super Admin', 151),
(832, 'Super Admin', 152),
(833, 'Super Admin', 154),
(834, 'Super Admin', 155),
(835, 'Super Admin', 156),
(836, 'Super Admin', 157),
(837, 'Super Admin', 158),
(838, 'Super Admin', 159),
(839, 'Super Admin', 167),
(840, 'Super Admin', 160),
(841, 'Super Admin', 161),
(842, 'Super Admin', 162),
(843, 'Super Admin', 163),
(844, 'Super Admin', 164),
(845, 'Super Admin', 165),
(846, 'Super Admin', 166),
(847, 'Super Admin', 168),
(848, 'Super Admin', 169),
(849, 'Super Admin', 170),
(850, 'Admin', 100),
(851, 'Admin', 104),
(852, 'Admin', 109),
(853, 'Admin', 120),
(854, 'Admin', 101),
(855, 'Admin', 105),
(856, 'Admin', 110),
(857, 'Admin', 121),
(858, 'Admin', 116),
(859, 'Admin', 102),
(860, 'Admin', 171),
(861, 'Admin', 107),
(862, 'Admin', 111),
(863, 'Admin', 122),
(864, 'Admin', 118),
(865, 'Admin', 103),
(866, 'Admin', 108),
(867, 'Admin', 113),
(868, 'Admin', 124),
(869, 'Admin', 119),
(870, 'Admin', 106),
(871, 'Admin', 112),
(872, 'Admin', 123),
(873, 'Admin', 117),
(874, 'Admin', 114),
(875, 'Admin', 131),
(876, 'Admin', 125),
(877, 'Admin', 130),
(878, 'Admin', 126),
(879, 'Admin', 129),
(880, 'Admin', 127),
(881, 'Admin', 128),
(882, 'Admin', 132),
(883, 'Admin', 133),
(884, 'Admin', 136),
(885, 'Admin', 134),
(886, 'Admin', 135),
(887, 'Admin', 138),
(888, 'Admin', 139),
(889, 'Admin', 140),
(890, 'Admin', 153),
(891, 'Admin', 141),
(892, 'Admin', 142),
(893, 'Admin', 145),
(894, 'Admin', 143),
(896, 'Admin', 146),
(897, 'Admin', 147),
(898, 'Admin', 148),
(899, 'Admin', 149),
(900, 'Admin', 150),
(901, 'Admin', 151),
(902, 'Admin', 152),
(903, 'Admin', 154),
(904, 'Admin', 155),
(905, 'Admin', 156),
(906, 'Admin', 157),
(907, 'Admin', 158),
(908, 'Admin', 159),
(909, 'Admin', 167),
(910, 'Admin', 160),
(911, 'Admin', 161),
(912, 'Admin', 162),
(913, 'Admin', 163),
(914, 'Admin', 164),
(915, 'Admin', 166),
(916, 'Admin', 168),
(917, 'Admin', 169),
(919, 'Admin', 115),
(920, 'Admin', 165),
(923, 'Super Admin', 147),
(924, 'Super Admin', 172),
(925, 'Super Admin', 173),
(926, 'Super Admin', 174),
(927, 'Super Admin', 175);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_brand`
--

CREATE TABLE `tbl_brand` (
  `Id` int(11) NOT NULL,
  `Brand_Id` varchar(11) NOT NULL,
  `Brand_Name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_brand`
--

INSERT INTO `tbl_brand` (`Id`, `Brand_Id`, `Brand_Name`) VALUES
(1, 'YMAFZ', 'YAMAHA FZ'),
(2, 'YMARZR', 'YAMAHA RAY ZR '),
(3, 'YMAFZV3', 'YAMAHA FZ V3'),
(4, 'YMAFZV2', 'YAMAHA FZ V2'),
(5, 'YMAV1', 'YAMAHA V1'),
(6, 'YMAR15', 'YAMAHA R15'),
(7, 'HND', 'HONDA'),
(8, 'OTH', 'OTHER'),
(10, 'BAJ/WE', 'Bajaj');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cash_flow`
--

CREATE TABLE `tbl_cash_flow` (
  `Id` int(11) NOT NULL,
  `Income_Transaction_Id` int(11) DEFAULT NULL,
  `Expense_Transaction_Id` int(11) DEFAULT NULL,
  `Description` varchar(150) NOT NULL,
  `Income` float(10,2) DEFAULT NULL,
  `Expense` float(10,2) DEFAULT NULL,
  `Payment_Type` enum('Cash','Card','Cheque','Bank Transfer') NOT NULL,
  `Update_Date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_cash_flow`
--

INSERT INTO `tbl_cash_flow` (`Id`, `Income_Transaction_Id`, `Expense_Transaction_Id`, `Description`, `Income`, `Expense`, `Payment_Type`, `Update_Date`) VALUES
(183, 7, NULL, 'Invoice Payment_1 - INVW00001', 37060.00, NULL, 'Bank Transfer', '2024-11-17 17:09:15'),
(184, NULL, 52, 'Internet Bill Payment_1 - EXP0001', NULL, 7060.00, 'Cash', '2025-02-05 20:08:26'),
(186, 8, NULL, 'Invoice Payment_1 - INVW00005', 2300.00, NULL, 'Card', '2025-02-05 20:26:57'),
(188, 9, NULL, 'Invoice Payment_1 - INVW00002', 800.00, NULL, 'Cash', '2025-02-16 13:26:02');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE `tbl_category` (
  `Id` int(11) NOT NULL,
  `Category_Id` varchar(11) NOT NULL,
  `Category_Name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_category`
--

INSERT INTO `tbl_category` (`Id`, `Category_Id`, `Category_Name`) VALUES
(1, 'FILTER', 'FILTER'),
(2, 'CBL', 'CBL'),
(3, 'BDPT', 'BDPT'),
(4, 'BRAKE SHO', 'BRAKE SHO'),
(5, 'SRVPT', 'SRVPT'),
(6, 'BRAKE PD ', 'BRAKE PD '),
(7, 'CLU', 'CLU'),
(8, 'OIL SL', 'OIL SL'),
(9, 'ENG', 'ENG'),
(10, 'ELCT', 'ELCT'),
(11, 'KBL', 'KBL'),
(12, 'HO SRV', 'HO SRV'),
(13, 'HO CBL', 'HO CBL'),
(14, 'HO ENG', 'HO ENG'),
(15, 'HO BDPT', 'HO BDPT'),
(16, 'HO OILSL', 'HO OILSL'),
(17, 'FZ 2ND', 'FZ 2ND'),
(18, 'DIO 2ND', 'DIO 2ND'),
(19, 'BLB', 'BLB'),
(20, 'PLUG ', 'PLUG '),
(21, '2ND SEV FZ', '2ND SEV FZ'),
(22, '2ND BDPT FZ', '2ND BDPT FZ'),
(23, '2ND BDPT DI', '2ND BDPT DIO'),
(24, '2ND', '2ND'),
(25, '2ND SV DIO', '2ND SV DIO');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_company_info`
--

CREATE TABLE `tbl_company_info` (
  `Id` int(11) NOT NULL,
  `Company_Name` varchar(150) NOT NULL,
  `Company_Address` varchar(1000) NOT NULL,
  `Company_Email` varchar(150) NOT NULL,
  `Company_Tel1` varchar(15) NOT NULL,
  `Company_Tel2` varchar(15) DEFAULT NULL,
  `Company_Tel3` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_company_info`
--

INSERT INTO `tbl_company_info` (`Id`, `Company_Name`, `Company_Address`, `Company_Email`, `Company_Tel1`, `Company_Tel2`, `Company_Tel3`) VALUES
(1, 'Orbis Solutions', '570/4, Pathalwaththe Rd, Erewwala, Pannipitiya, Sri Lanka', 'info@orbislk.com', '0773697070', '0712098989', '0768576851');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customers`
--

CREATE TABLE `tbl_customers` (
  `Id` int(11) NOT NULL,
  `Customer_Id` varchar(11) NOT NULL,
  `Customer_Name` varchar(150) NOT NULL,
  `Customer_Address` varchar(1000) DEFAULT NULL,
  `Customer_Contact` varchar(15) NOT NULL,
  `Customer_Email` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_customers`
--

INSERT INTO `tbl_customers` (`Id`, `Customer_Id`, `Customer_Name`, `Customer_Address`, `Customer_Contact`, `Customer_Email`) VALUES
(1, 'CUS0001', 'SHAN YAMAHA ', NULL, '0777917356', NULL),
(4, 'CUS0002', 'Dananjaya', 'Ganemulla', '0777323252', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_expenses`
--

CREATE TABLE `tbl_expenses` (
  `Id` int(11) NOT NULL,
  `Expense_Id` varchar(11) NOT NULL,
  `Expense_Type_Id` varchar(11) NOT NULL,
  `User_Id` int(11) NOT NULL,
  `Expense_Title` varchar(150) NOT NULL,
  `Expense_Description` varchar(150) DEFAULT NULL,
  `Expense_Amount` float(10,2) NOT NULL,
  `Doc` varchar(150) NOT NULL,
  `Status` enum('Fully Paid','Partially Paid','Unpaid') NOT NULL,
  `Paid_Amount` float(10,2) NOT NULL,
  `Due_Amount` float(10,2) NOT NULL,
  `Payment_Type` enum('Cash','Card','Cheque','Bank Transfer','N/A') NOT NULL,
  `Expence_Date` datetime NOT NULL,
  `Payment_Date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_expenses`
--

INSERT INTO `tbl_expenses` (`Id`, `Expense_Id`, `Expense_Type_Id`, `User_Id`, `Expense_Title`, `Expense_Description`, `Expense_Amount`, `Doc`, `Status`, `Paid_Amount`, `Due_Amount`, `Payment_Type`, `Expence_Date`, `Payment_Date`) VALUES
(30, 'EXP0001', 'ETP0001', 1, 'SLT Fiber', 'Pay', 7060.00, 'http://localhost/Orbis_Solutions_Inventory_System/Files/EXP0001.pdf', 'Fully Paid', 7060.00, 0.00, 'Cash', '2025-02-05 20:08:17', '2025-02-05 20:08:26'),
(31, 'EXP0002', 'ETP0001', 1, 'Reload Bill', NULL, 5000.00, 'http://localhost/Orbis_Solutions_Inventory_System/Files/EXP0002.pdf', 'Unpaid', 0.00, 5000.00, 'Cash', '2025-02-05 20:25:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_expenses_categories`
--

CREATE TABLE `tbl_expenses_categories` (
  `Id` int(11) NOT NULL,
  `Expense_Category_Id` varchar(11) NOT NULL,
  `Expense_Category_Name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_expenses_categories`
--

INSERT INTO `tbl_expenses_categories` (`Id`, `Expense_Category_Id`, `Expense_Category_Name`) VALUES
(1, 'EXC0001', 'Administration Expenses'),
(2, 'EXC0002', 'Selling & Distribution Expenses'),
(3, 'EXC0003', 'Financial & Other Expenses');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_expenses_types`
--

CREATE TABLE `tbl_expenses_types` (
  `Id` int(11) NOT NULL,
  `Expense_Type_Id` varchar(11) NOT NULL,
  `Expense_Category_Id` varchar(11) NOT NULL,
  `Expense_Type` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_expenses_types`
--

INSERT INTO `tbl_expenses_types` (`Id`, `Expense_Type_Id`, `Expense_Category_Id`, `Expense_Type`) VALUES
(11, 'ETP0001', 'EXC0003', 'Internet Bill');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_expense_payments`
--

CREATE TABLE `tbl_expense_payments` (
  `Id` int(11) NOT NULL,
  `Expense_Id` varchar(11) NOT NULL,
  `Payment_Id` int(11) NOT NULL,
  `Expense_Amount` float(10,2) NOT NULL,
  `Paid_Amount` float(10,2) NOT NULL,
  `Due_Amount` float(10,2) NOT NULL,
  `Payment_Type` enum('Cash','Card','Cheque','Bank Transfer','N/A') NOT NULL,
  `Expense_Description` varchar(1000) DEFAULT NULL,
  `Payment_Date` datetime NOT NULL,
  `Updated_By` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_expense_payments`
--

INSERT INTO `tbl_expense_payments` (`Id`, `Expense_Id`, `Payment_Id`, `Expense_Amount`, `Paid_Amount`, `Due_Amount`, `Payment_Type`, `Expense_Description`, `Payment_Date`, `Updated_By`) VALUES
(52, 'EXP0001', 1, 7060.00, 7060.00, 0.00, 'Cash', 'Pay', '2025-02-05 20:08:26', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_invoice`
--

CREATE TABLE `tbl_invoice` (
  `Id` int(11) NOT NULL,
  `Invoice_Id` varchar(11) NOT NULL,
  `Customer_Id` varchar(11) DEFAULT NULL,
  `User_Id` int(11) DEFAULT NULL,
  `Sale_Type` enum('Whole Sale','Retail Sale') NOT NULL,
  `Item_Count` int(11) NOT NULL,
  `Status` enum('Fully Paid','Partially Paid','Unpaid') NOT NULL,
  `Sub_Total` float(10,2) NOT NULL,
  `Discount_Total` float(10,2) NOT NULL,
  `Profit_Total` float(10,2) NOT NULL,
  `Grand_Total` float(10,2) NOT NULL,
  `Paid_Amount` float(10,2) NOT NULL,
  `Balance_Total` float(10,2) NOT NULL,
  `Due_Total` float(10,2) NOT NULL,
  `Payment_Type` enum('Cash','Card','Cheque','Bank Transfer','N/A') NOT NULL,
  `Description` varchar(1000) DEFAULT NULL,
  `Invoice_Date` datetime NOT NULL,
  `Payment_Date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_invoice`
--

INSERT INTO `tbl_invoice` (`Id`, `Invoice_Id`, `Customer_Id`, `User_Id`, `Sale_Type`, `Item_Count`, `Status`, `Sub_Total`, `Discount_Total`, `Profit_Total`, `Grand_Total`, `Paid_Amount`, `Balance_Total`, `Due_Total`, `Payment_Type`, `Description`, `Invoice_Date`, `Payment_Date`) VALUES
(5, 'INVW00001', 'CUS0002', 2, 'Whole Sale', 5, 'Fully Paid', 37060.00, 0.00, 12362.00, 37060.00, 37060.00, 0.00, 0.00, 'Bank Transfer', 'Paid - 26/10/2024', '2024-11-17 17:09:15', '2024-11-17 17:09:15'),
(6, 'INVW00002', 'CUS0001', 2, 'Whole Sale', 2, 'Partially Paid', 16800.00, 0.00, 6476.00, 16800.00, 800.00, 0.00, 16000.00, 'Cash', 'PAYMENT WITH IN 40 DAYS', '2024-11-27 11:10:56', '2025-02-16 13:26:02'),
(7, 'INVW00003', 'CUS0002', 1, 'Whole Sale', 2, 'Unpaid', 2340.00, 0.00, 790.00, 2340.00, 0.00, 0.00, 2380.00, 'N/A', NULL, '2025-02-03 23:13:09', NULL),
(8, 'INVW00004', 'CUS0001', 1, 'Whole Sale', 1, 'Unpaid', 1190.00, 0.00, 390.00, 1190.00, 0.00, 0.00, 2380.00, 'N/A', 'jik', '2025-02-03 23:25:46', NULL),
(9, 'INVW00005', 'CUS0002', 1, 'Whole Sale', 1, 'Fully Paid', 2300.00, 0.00, 800.00, 2300.00, 2500.00, 200.00, 0.00, 'Card', 'fully paid', '2025-02-05 20:26:57', '2025-02-05 20:26:57'),
(10, 'INVW00006', 'CUS0002', 1, 'Whole Sale', 1, 'Unpaid', 1190.00, 0.00, 390.00, 1190.00, 0.00, 0.00, 1190.00, 'N/A', 'tesrt', '2025-02-16 20:50:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_item`
--

CREATE TABLE `tbl_item` (
  `Id` int(11) NOT NULL,
  `Invoice_Id` varchar(11) NOT NULL,
  `Product_Id` varchar(150) NOT NULL,
  `Product_Detail_Id` int(11) NOT NULL,
  `Product_Name` varchar(250) NOT NULL,
  `Landing_Cost` float(10,2) NOT NULL,
  `Unit_Price` float(10,2) NOT NULL,
  `Unit_Discount` float(10,2) NOT NULL,
  `Qty` int(11) NOT NULL,
  `Total_Discount` float(10,2) NOT NULL,
  `Total_Price` float(10,2) NOT NULL,
  `Total_Cost` float(10,2) NOT NULL,
  `Total_Profit` float(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_item`
--

INSERT INTO `tbl_item` (`Id`, `Invoice_Id`, `Product_Id`, `Product_Detail_Id`, `Product_Name`, `Landing_Cost`, `Unit_Price`, `Unit_Discount`, `Qty`, `Total_Discount`, `Total_Price`, `Total_Cost`, `Total_Profit`) VALUES
(4, 'INVW00001', 'B97-F6280-00', 44, 'MIRROR V3', 797.00, 1180.00, 0.00, 2, 0.00, 2360.00, 1594.00, 766.00),
(5, 'INVW00001', 'B97-F6290-00', 45, 'MIRROR V3', 762.00, 1150.00, 0.00, 2, 0.00, 2300.00, 1524.00, 776.00),
(6, 'INVW00001', '54B-W0041-00', 73, 'CYLINDER MASTER SET', 1126.00, 1690.00, 0.00, 10, 0.00, 16900.00, 11260.00, 5640.00),
(7, 'INVW00001', '54B-W0047-00', 74, 'CALIPER SEAL KIT', 374.00, 590.00, 0.00, 10, 0.00, 5900.00, 3740.00, 2160.00),
(8, 'INVW00001', '54B-H3922-00', 13, 'LEVER 2', 658.00, 960.00, 0.00, 10, 0.00, 9600.00, 6580.00, 3020.00),
(9, 'INVW00002', '5YY-H1950-10', 21, 'RELAY', 346.00, 850.00, 0.00, 4, 0.00, 3400.00, 1384.00, 2016.00),
(10, 'INVW00002', '2GS-F3144-00', 36, 'DUST SEAL', 894.00, 1340.00, 0.00, 10, 0.00, 13400.00, 8940.00, 4460.00),
(11, 'INVW00003', '037-SVM-FZL', 112, 'FZ V2 MIRROR LEFT 2PCS', 800.00, 1190.00, 0.00, 1, 0.00, 1190.00, 800.00, 390.00),
(12, 'INVW00003', '045-BLA-NML', 115, 'SIGNAL LIGHT RIGHT', 750.00, 1150.00, 0.00, 1, 0.00, 1150.00, 750.00, 400.00),
(13, 'INVW00004', '037-SVM-FZL', 112, 'FZ V2 MIRROR LEFT 2PCS', 800.00, 1190.00, 0.00, 1, 0.00, 1190.00, 800.00, 390.00),
(14, 'INVW00005', '045-BLA-NML', 115, 'SIGNAL LIGHT RIGHT', 750.00, 1150.00, 0.00, 2, 0.00, 2300.00, 1500.00, 800.00),
(15, 'INVW00006', '037-SVM-FZL', 112, 'FZ V2 MIRROR LEFT 2PCS', 800.00, 1190.00, 0.00, 1, 0.00, 1190.00, 800.00, 390.00);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payments`
--

CREATE TABLE `tbl_payments` (
  `Id` int(11) NOT NULL,
  `Invoice_Id` varchar(11) NOT NULL,
  `Payment_Id` int(11) NOT NULL,
  `Grand_Total` float(10,2) NOT NULL,
  `Paid_Amount` float(10,2) NOT NULL,
  `Balance_Total` float(10,2) NOT NULL,
  `Due_Total` float(10,2) NOT NULL,
  `Payment_Type` enum('Cash','Card','Cheque','Bank Transfer','N/A') NOT NULL,
  `Description` varchar(1000) DEFAULT NULL,
  `Payment_Date` datetime NOT NULL,
  `Updated_By` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_payments`
--

INSERT INTO `tbl_payments` (`Id`, `Invoice_Id`, `Payment_Id`, `Grand_Total`, `Paid_Amount`, `Balance_Total`, `Due_Total`, `Payment_Type`, `Description`, `Payment_Date`, `Updated_By`) VALUES
(7, 'INVW00001', 1, 37060.00, 37060.00, 0.00, 0.00, 'Bank Transfer', 'Paid - 26/10/2024', '2024-11-17 17:09:15', 2),
(8, 'INVW00005', 1, 2300.00, 2500.00, 200.00, 0.00, 'Card', 'fully paid', '2025-02-05 20:26:57', 1),
(9, 'INVW00002', 1, 16800.00, 800.00, 0.00, 16000.00, 'Cash', 'PAYMENT WITH IN 40 DAYS', '2025-02-16 13:26:02', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product`
--

CREATE TABLE `tbl_product` (
  `Id` int(11) NOT NULL,
  `Product_Id` varchar(150) NOT NULL,
  `Brand_Id` varchar(11) NOT NULL,
  `Category_Id` varchar(11) NOT NULL,
  `Product_Name` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_product`
--

INSERT INTO `tbl_product` (`Id`, `Product_Id`, `Brand_Id`, `Category_Id`, `Product_Name`) VALUES
(1, '21C-E3440-01', 'YMAFZ', 'FILTER', 'OIL FILTER'),
(2, '1GC-F6341-00', 'YMARZR ', 'CBL', 'BRAKE CABLE FRONT'),
(3, 'B4G-F6111-00', 'YMAFZV3', 'BDPT', 'HANDLE BAR V3'),
(4, '1GC-F530K-00', 'YMAFZV2', 'BRAKE SHO', 'BRAKE SHOE'),
(5, 'B97-F5364-00', 'YMAFZV3', 'SRVPT', 'DAMPER V3'),
(6, '1GC-E5473-00', 'YMARZR', 'FILTER', 'DUCT AIR'),
(7, '2GS-W0045-00', 'YMAFZV2', 'BRAKE PD ', 'BRAKE PAD'),
(8, '1PM-F5805-00', 'YMARZR ', 'BRAKE PD ', 'BRAKE PAD RAY'),
(9, '1GC-E7660-00', 'YMARZR ', 'CLU', 'SECONDRARY FIXED SHEAVE'),
(10, '1GC-E7670-00', 'YMARZR ', 'CLU', 'SECONDARY SLIDING'),
(11, '2GS-F3145-00', 'YMAFZ', 'OIL SL', 'OIL SEAL'),
(12, '54B-H3912-00', 'YMAFZ', 'BDPT', 'LEVER 1'),
(13, '54B-H3922-00', 'YMAFZ', 'BDPT', 'LEVER 2'),
(14, '1GC-F5138-10', 'YMARZR', 'SRVPT', 'GEAR METER'),
(15, '1GC-F5135-10', 'YMARZR', 'SRVPT', 'GEAR DRIVE'),
(16, '2GS-F6312-00', 'YMAFZV2', 'CBL', 'THROTTLE CABLE'),
(17, '2GS-F4124-00', 'YMAFZV2', 'BDPT', 'AIR SHROUND'),
(18, '21C-H2530-00', 'YMAFZV2', 'SRVPT', 'STOP SWITCH FZ'),
(19, '21C-F5364-00', 'YMAFZV2', 'SRVPT', 'DAMPER V2'),
(20, '21C-14340-00', 'YMAFZV2', 'ENG', 'VALVE ASSY'),
(21, '5YY-H1950-10', 'YMAFZV2', 'ELCT', 'RELAY'),
(22, '1GC-E7632-01', 'YMARZR', 'CLU', 'WEIGHT'),
(23, '1GC-E7641-10', 'YMARZR', 'SRVPT', 'V BELT'),
(24, '1GC-F6351-00', 'YMARZR', 'CBL', 'REAR BRAKE CABLE'),
(25, '1GC-E7678-00', 'YMARZR', 'CLU', 'BEARING'),
(26, '1GC-E7678-10', 'YMARZR', 'CLU', 'BEARING'),
(27, '1PM-F5803-00', 'YMARZR', 'CLU', 'CALIPER SEAL RAY'),
(28, '2FS-F6311-00', 'YMARZR', 'KBL', 'THROTTLE CABLE RAY'),
(29, '21C-F3156-10', 'YMAFZV2', 'SRVPT', 'RING SNAP PACKET'),
(30, '21C-F6111-01', 'YMAFZV2', 'BDPT', 'HANDLE BAR V2'),
(31, '21C-F6311-21', 'YMAV1', 'CBL', 'THROTTLE CABLE 1 V1'),
(32, '21C-F6312-00', 'YMAV1', 'CBL', 'THROTTLE CABLE 2 V1'),
(33, '2FS-E5451-00', 'YMAFZV2', 'SRVPT', 'GASKET '),
(34, '90387-1286300', 'YMAFZV2', 'SRVPT', 'COLLER'),
(35, '933173-18Y500', 'YMAFZV2', 'SRVPT', 'SWIM ARM BEARING 2pcs'),
(36, '2GS-F3144-00', 'YMAFZV2', 'OIL SL', 'DUST SEAL'),
(37, '2FS-F3145-00', 'YMARZR', 'OIL SL', 'OIL SEAL RAY'),
(38, '2GS-F6311-10', 'YMAFZV2', 'CBL', 'THROTTLE CABLE'),
(39, '2GS-WE660-60-41', 'YMAFZV3', 'SRVPT', 'CHAIN SPROCKET V3'),
(40, '93306054Y500', 'YMAFZV2', 'SRVPT', 'BEARING'),
(41, '93306202XT00', 'YMAFZV2', 'SRVPT', 'BEARING'),
(42, '93822-14800', 'YMAFZV2', 'SRVPT', 'SPROCKET '),
(43, '94703-0088500', 'YMAFZV2', 'SRVPT', 'PLUG FZ'),
(44, 'B97-F6280-00', 'YMAFZV3', 'BDPT', 'MIRROR V3'),
(45, 'B97-F6290-00', 'YMAFZV3', 'BDPT', 'MIRROR V3'),
(46, '1PM-H3330-00', 'YMAV1', 'BDPT', 'REAR FLASHER  V1 YELLOW'),
(47, '2GS-WE660-60-10', 'YMAFZV2', 'SRVPT', 'CHAIN SPROCKET V2'),
(48, '2FS-F6331-00', 'YMARZR', 'CBL', 'CHOCK CABLE RAY ZR'),
(49, '38B-E34400-10', 'YMAR15', 'OIL SL', 'ELEMENT ASSY OIL'),
(50, '1GC-H2310-00', 'YMAFZV2', 'ELCT', 'IGNITION COIL ASSY'),
(51, '21C-E6371-00', 'YMAFZV2', 'ENG', 'BOSS CLUTCH'),
(52, '2GS-E3907-11', 'YMAFZV2', 'ENG', 'FUEL PUMP COMP'),
(53, '2GS-E4450-00', 'YMAFZV2', 'FILTER', 'AIR FILTER V2'),
(54, '2GS-F1651-00', 'YMAFZV2', 'BDPT', 'COVER REAR FENDER'),
(55, '2GS-H1800-12', 'YMAFZV2', 'ELCT', 'STARTING MOTOR FZ'),
(56, 'B44-H1960-00', 'YMARZR', 'ELCT', 'RECTIFIER & REGULATOR'),
(57, 'B62-H4700-00', 'YMARZR', 'BDPT', 'TAILLIGHT ASSY RAY ZR'),
(58, '54B-E6321-00', 'YMAFZV2', 'ENG', 'PLATE FRICTION '),
(59, '21C-E6331-00', 'YMAFZV2', 'ENG', 'PLATE FRICTION 2'),
(60, 'B97-F5806-00', 'YMAFZV3', 'SRVPT', 'BRAKE PAD V3'),
(61, '1GC-E7620-00', 'YMARZR', 'SRVPT', 'PRIMARY SLIDING'),
(62, '1GC-E7674-|0', 'YMARZR', 'SRVPT', 'COLLAR-GUIDE'),
(63, '1GC-H3550-02', 'YMARZR', 'SRVPT', 'SPEEDO METER CABLE'),
(64, '1GC-H3912-00', 'YMARZR', 'SRVPT', 'LEVER 1 '),
(65, '21C-F582U-00', 'YMAFZV2', 'SRVPT', 'DISC BRAKE RIGHT'),
(66, '21CF-6335-04', 'YMAFZV2', 'SRVPT', 'CLUTCH CABLE'),
(67, '21C-WF6619-00', 'YMAFZV2', 'SRVPT', 'CUP SET'),
(68, '2GS-H3310-00', 'YMAFZV2', 'BDPT', 'FRONT FLASHER LIGHT'),
(69, '2GS-H3320-00', 'YMAFZV2', 'BDPT', 'FRONT FLASHER LIGHT'),
(70, '2GS-H3330-00', 'YMAFZV2', 'BDPT', 'REAR FLASH LIGHT'),
(71, '2GS-H3340-00', 'YMAFZV2', 'BDPT', 'REAR FLASH LIGHT'),
(72, '2SP-H180-11', 'YMARZR', 'SRVPT', 'BRUSH SET'),
(73, '54B-W0041-00', 'YMAFZV2', 'SRVPT', 'CYLINDER MASTER SET'),
(74, '54B-W0047-00', 'YMAFZV2', 'SRVPT', 'CALIPER SEAL KIT'),
(75, '5KA-W6619-00', 'YMAFZV2', 'SRVPT', 'CUP SET'),
(76, '5TP-H1801-00', 'YMAFZV2', 'SRVPT', 'BRUSH SET'),
(78, 'B62-H3550-01', 'YMARZR', 'SRVPT', 'SPEEDO METER CABLE'),
(79, 'B62-H3922-00', 'YMARZR', 'BDPT', 'LEVER 2'),
(80, '06430-KWP-D00', 'HND', 'HO SRV', 'BRAKE SHOE'),
(81, '45440-KWP-910', 'HND', 'HO CBL', 'BRAKE CABLE '),
(82, '45450-KWP-910', 'HND', 'HO CBL', 'BRAKE CABLE '),
(83, '17210-KVT-D00', 'HND', 'HO SRV', 'AIR FILTER'),
(84, '23205-KWP-D00', 'HND', 'HO ENG', 'FACE SET DRIVEN'),
(85, '23224-KWP-900', 'HND', 'HO ENG', 'FACE SET MOVEBLE'),
(86, '17950-KWP-900', 'HND', 'HO CBL', 'CHOCK CABLE'),
(87, '44830-KWP-901', 'HND', 'HO CBL', 'SPEEDO M CABLE'),
(88, '06179-KZK-901', 'HND', 'HO CBL', 'THROTTLE CABLE'),
(89, '88210-KZK-901', 'HND', 'HO BDPT', 'MIRROR RIGHT'),
(90, '35170-KPL-842', 'HND', 'HO BDPT', 'DIMMER SWITCH'),
(91, '38301-KPL-901', 'HND', 'HO BDPT', 'WINKER RELAY'),
(92, '22535-KWP-900', 'HND', 'HO ENG', 'CLUTCH WEIGHT'),
(93, '35175-KTP-900', 'HND', 'HO BDPT', 'LEVER'),
(94, '05141-KPL-840', 'HND', 'HO SRV', 'GREEN BUSH'),
(95, '35340-KWP-900', 'HND', 'HO SRV', 'BRAKE LIGHT SWITCH'),
(96, '91201-KWP-D01', 'HND', 'HO OILSL', 'OIL SEAL 16*26*7'),
(97, '91203-KWP-D01', 'HND', 'HO OILSL', 'CLUTCH SHAFT '),
(98, '91202-KWP-D01', 'HND', 'HO OILSL', 'CLUTCH OIL SEAL'),
(99, '12391-KWP-900', 'HND', 'HO OILSL', 'GASKET HEAD COVER'),
(100, '91225-KWP-D01', 'HND', 'HO OILSL', 'GEAR BOX OILSEAL'),
(101, '23100-KWP-D00', 'HND', 'HO SRV', 'V BELT'),
(102, '43450-KZK-910', 'HND', 'HO CBL', 'REAR BRAKE CABLE'),
(103, '22123-KWN-780', 'HND', 'HO SRV', 'ROLLER SET'),
(104, '22110-KWPD00', 'HND', 'HO SRV', 'FACE MOVBLE DRIVE (HET)'),
(105, '22011-KPL-940', 'HND', 'HO SRV', 'SLIDE'),
(106, 'YFZ61', 'OTH', 'FZ 2ND', 'NIPON FZ BRAKE SHOE'),
(107, 'HAS61', 'OTH', 'DIO 2ND', 'NIPON DIO BRAKE SHOE'),
(108, '1141A', 'OTH', 'BLB', 'ORENGE BULB'),
(109, '1014', 'OTH', 'BLB', 'PHO12V 10/5W'),
(110, 'F00240517ENG', 'OTH', 'PLUG ', 'BOSH PLUG PULSER'),
(111, '26024563 LUCAS', 'OTH', '2ND SEV FZ', 'FZ START MOTOR V1'),
(112, '037-SVM-FZL', 'OTH', '2ND BDPT FZ', 'FZ V2 MIRROR LEFT 2PCS'),
(113, '037-SVMFZR', 'OTH', '2ND BDPT FZ', 'FZV2 MIRROR RIGHT 2PCS'),
(114, '045-BLA-NMR', 'OTH', '2ND BDPT DI', 'SIGNAL LIGHT LEFT'),
(115, '045-BLA-NML', 'OTH', '2ND BDPT DI', 'SIGNAL LIGHT RIGHT'),
(116, '045-SVM-DNL', 'OTH', '2ND BDPT DI', 'DIO MIRROR LEFT 2PCS'),
(117, '045-SVM-DNR', 'OTH', '2ND BDPT DI', 'DIO MIRROR RIGHT 2PCS'),
(118, '058-SVM- LEFT', 'OTH', '2ND', 'NTORQ MIRROR 2PCS'),
(119, '058-SVM-RIGHT', 'OTH', '2ND', 'NTORQ MIRROR 2PCS'),
(120, 'DIO BRAKE SHOE', 'OTH', '2ND SV DIO', 'NINKI BRAKE SHOE DIO'),
(121, 'HS1/12V/35/35W', 'OTH', 'BLB ', 'OSRAM HEAD BULB'),
(122, 'R10W/12V/10W', 'OTH', 'BLB', 'OSRAM SIGNAL BULB'),
(127, '1GC-E4450-00', 'YMARZR', 'FILTER', 'ELEMENT ASSY AIR'),
(129, '1GC-E6620-20', 'YMARZR', 'SRVPT', 'CLUTCH CARRIER ASSY'),
(130, '1GC-E7632-01	', 'YMARZR', 'SRVPT', 'WEIGHT'),
(131, '1GC-H276A-00', 'YMARZR', 'SRVPT', 'DIMMER SWITCH'),
(132, '1GC-H3371-00', 'YMARZR', 'SRVPT', 'HORN'),
(133, '1GC-H396U-00', 'YMARZR', 'SRVPT', 'SWITCH'),
(134, '1GC-H39700-00', 'YMARZR', 'SRVPT', 'STARTER SWITCH ASSY'),
(135, '1GC-H575200-00', 'YMARZR', 'SRVPT', 'SENDER UNIT ASSY FUEL'),
(136, '21C-E632400-00', 'YMAFZ', 'SRVPT', 'PLATE CLUTC 1'),
(137, '21C-E6351-00', 'YMAFZ', 'ENG', 'PLATE PRESSURE 1'),
(138, '21C-F31100-00', 'YMAFZ', 'SRVPT', 'INNER TUBE'),
(139, '21C-F620B-21', 'YMAFZ', 'SRVPT', 'END GRIP ASSY BLACK'),
(140, '2GS-E8110-11', 'YMAFZ', 'SRVPT', 'SHIFT PEDAL ASSY'),
(141, '2GS-F317-00', 'YMAFZV2', 'SRVPT', 'CYLINDER COMP FR'),
(142, '2GS-H1960-10', 'YMAFZV2', 'SRVPT', 'RECTIFIER & REGULATOR FZ'),
(143, '2GS-H3500-00', 'YMAFZV2', 'SRVPT', 'SPEEDO METER'),
(144, '2GS-H3963-00', 'YMAFZV2', 'BDPT', 'SWITCH HANDEL 3 FZ'),
(145, '2GS-H3972-01', 'YMAFZV2', 'BDPT', 'SWITCH HANDEL 4 FZ'),
(146, '2GS-H4300-00', 'YMAFZV2', 'BDPT', 'HEADLIGHT'),
(147, '2GS-XH2500-00', 'YMAFZV2', 'BDPT', 'MAIN SWITCH SET'),
(148, '9470300-83400', 'YMARZR', 'SRVPT', 'PLUG   RAY'),
(149, 'B62-F5190-00', 'YMARZR', 'SRVPT', 'GEAR UNIT ASSY'),
(150, 'B62-F530K-00', 'YMARZR', 'SRVPT', 'BRAKE SHOE RAY'),
(151, 'B62-F582U-10', 'YMARZR', 'BDPT', 'DISK BRAKE RIGHT RAY ZR'),
(152, '2GS-SF4124-00', 'YMAFZV2', 'BDPT', 'AIR SHROUND'),
(153, '10026552', 'HND', '2ND', 'Product 01');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_details`
--

CREATE TABLE `tbl_product_details` (
  `Id` int(11) NOT NULL,
  `Product_Id` varchar(150) NOT NULL,
  `Cost` float(10,2) NOT NULL,
  `Landing_Cost` float(10,2) NOT NULL,
  `Retail_Price` float(10,2) NOT NULL,
  `Wholesale_Price` float(10,2) NOT NULL,
  `Qty` int(11) NOT NULL,
  `Supplier_Id` varchar(11) NOT NULL,
  `Received_Date` datetime NOT NULL,
  `Inventort_Updated` enum('True','False') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_product_details`
--

INSERT INTO `tbl_product_details` (`Id`, `Product_Id`, `Cost`, `Landing_Cost`, `Retail_Price`, `Wholesale_Price`, `Qty`, `Supplier_Id`, `Received_Date`, `Inventort_Updated`) VALUES
(1, '21C-E3440-01', 80.00, 277.00, 554.00, 390.00, 169, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(2, '1GC-F6341-00', 205.00, 710.00, 1421.00, 1050.00, 11, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(3, 'B4G-F6111-00', 495.00, 1715.00, 3430.00, 2550.00, 11, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(4, '1GC-F530K-00', 295.00, 1022.00, 2044.00, 1490.00, 21, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(5, 'B97-F5364-00', 150.00, 520.00, 1039.00, 850.00, 5, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(9, '1GC-E7660-00', 505.00, 1750.00, 3499.00, 2540.00, 8, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(10, '1GC-E7670-00', 450.00, 1559.00, 3118.00, 2250.00, 10, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(12, '54B-H3912-00', 125.00, 433.00, 866.00, 780.00, 35, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(13, '54B-H3922-00', 190.00, 658.00, 1317.00, 960.00, 35, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(14, '1GC-F5138-10', 57.00, 197.00, 395.00, 250.00, 90, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(15, '1GC-F5135-10', 34.00, 118.00, 236.00, 280.00, 92, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(16, '2GS-F6312-00', 119.00, 412.00, 825.00, 590.00, 29, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(17, '2GS-F4124-00', 135.00, 468.00, 936.00, 800.00, 1, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(18, '21C-H2530-00', 50.00, 173.00, 346.00, 320.00, 32, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(19, '21C-F5364-00', 128.00, 443.00, 887.00, 750.00, 22, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(20, '21C-14340-00', 1570.00, 5440.00, 10880.00, 7560.00, 2, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(21, '5YY-H1950-10', 100.00, 346.00, 693.00, 850.00, 2, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(22, '1GC-E7632-01', 46.00, 159.00, 319.00, 290.00, 40, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(24, '1GC-F6351-00', 410.00, 1421.00, 2841.00, 1590.00, 35, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(25, '1GC-E7678-00', 172.00, 596.00, 1192.00, 890.00, 88, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(26, '1GC-E7678-10', 103.00, 357.00, 714.00, 560.00, 76, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(27, '1PM-F5803-00', 45.00, 156.00, 312.00, 340.00, 30, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(28, '2FS-F6311-00', 325.00, 1126.00, 2252.00, 1680.00, 28, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(29, '21C-F3156-10', 140.00, 485.00, 970.00, 790.00, 21, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(30, '21C-F6111-01', 450.00, 1559.00, 3118.00, 2350.00, 42, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(31, '21C-F6311-21', 360.00, 1247.00, 2495.00, 1790.00, 5, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(32, '21C-F6312-00', 270.00, 936.00, 1871.00, 1350.00, 10, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(33, '2FS-E5451-00', 119.00, 412.00, 825.00, 650.00, 20, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(34, '90387-1286300', 190.00, 658.00, 1317.00, 1050.00, 40, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(35, '933173-18Y500', 506.00, 1753.00, 3506.00, 2550.00, 68, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(37, '2FS-F3145-00', 167.00, 579.00, 1157.00, 900.00, 60, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(38, '2GS-F6311-10', 310.00, 1074.00, 2148.00, 1650.00, 9, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(39, '2GS-WE660-60-41', 1780.00, 6167.00, 12335.00, 8900.00, 7, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(40, '93306054Y500', 170.00, 589.00, 1178.00, 870.00, 25, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(41, '93306202XT00', 185.00, 641.00, 1282.00, 930.00, 50, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(43, '94703-0088500', 207.00, 717.00, 1434.00, 1100.00, 60, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(44, 'B97-F6280-00', 230.00, 797.00, 1594.00, 1180.00, 18, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(45, 'B97-F6290-00', 220.00, 762.00, 1525.00, 1150.00, 13, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(46, '1PM-H3330-00', 185.00, 641.00, 1282.00, 750.00, 7, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(47, '2GS-WE660-60-10', 1780.00, 6167.00, 12335.00, 8690.00, 18, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(48, '2FS-F6331-00', 185.00, 641.00, 1282.00, 940.00, 16, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(49, '38B-E34400-10', 247.00, 856.00, 1712.00, 1240.00, 10, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(50, '1GC-H2310-00', 941.00, 3260.00, 6521.00, 4690.00, 8, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(51, '21C-E6371-00', 295.00, 1022.00, 2044.00, 1490.00, 6, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(52, '2GS-E3907-11', 3915.00, 13565.00, 27130.00, 19580.00, 2, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(54, '2GS-F1651-00', 162.00, 561.00, 1123.00, 820.00, 8, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(55, '2GS-H1800-12', 1991.00, 6898.00, 13797.00, 9960.00, 4, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(56, 'B44-H1960-00', 850.00, 2945.00, 5890.00, 4250.00, 8, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(57, 'B62-H4700-00', 580.00, 2010.00, 4019.00, 2950.00, 1, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(58, '54B-E6321-00', 220.00, 762.00, 1525.00, 2250.00, 19, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(59, '21C-E6331-00', 310.00, 1074.00, 2148.00, 1590.00, 9, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(61, '1GC-E7620-00', 320.00, 1109.00, 2217.00, 1650.00, 20, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(62, '1GC-E7674-|0', 108.00, 374.00, 748.00, 560.00, 20, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(63, '1GC-H3550-02', 150.00, 520.00, 1039.00, 780.00, 30, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(64, '1GC-H3912-00', 135.00, 468.00, 936.00, 700.00, 40, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(65, '21C-F582U-00', 1580.00, 5474.00, 10949.00, 7980.00, 10, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(66, '21CF-6335-04', 250.00, 866.00, 1732.00, 1290.00, 30, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(67, '21C-WF6619-00', 428.00, 1483.00, 2966.00, 2180.00, 40, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(68, '2GS-H3310-00', 190.00, 658.00, 1317.00, 980.00, 50, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(69, '2GS-H3320-00', 196.00, 679.00, 1358.00, 980.00, 50, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(70, '2GS-H3330-00', 193.00, 669.00, 1337.00, 980.00, 50, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(71, '2GS-H3340-00', 193.00, 669.00, 1337.00, 980.00, 50, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(72, '2SP-H180-11', 106.00, 367.00, 735.00, 560.00, 60, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(73, '54B-W0041-00', 325.00, 1126.00, 2252.00, 1690.00, 70, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(74, '54B-W0047-00', 108.00, 374.00, 748.00, 590.00, 30, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(75, '5KA-W6619-00', 388.00, 1344.00, 2689.00, 1990.00, 60, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(76, '5TP-H1801-00', 345.00, 1195.00, 2391.00, 1750.00, 100, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(77, '93822-14800', 138.00, 478.00, 956.00, 720.00, 70, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(78, 'B62-H3550-01', 130.00, 450.00, 901.00, 750.00, 20, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(79, 'B62-H3922-00', 178.00, 617.00, 1233.00, 900.00, 40, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(80, '06430-KWP-D00', 271.00, 973.00, 1946.00, 1390.00, 68, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(81, '45440-KWP-910', 183.00, 657.00, 1314.00, 980.00, 55, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(82, '45450-KWP-910', 254.00, 912.00, 1824.00, 1150.00, 35, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(83, '17210-KVT-D00', 223.00, 801.00, 1601.00, 1150.00, 7, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(84, '23205-KWP-D00', 1346.00, 4832.00, 9664.00, 6850.00, 15, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(85, '23224-KWP-900', 703.00, 2524.00, 5048.00, 3590.00, 20, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(86, '17950-KWP-900', 156.00, 560.00, 1120.00, 830.00, 32, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(87, '44830-KWP-901', 108.00, 388.00, 775.00, 580.00, 30, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(88, '06179-KZK-901', 415.00, 1490.00, 2980.00, 2150.00, 32, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(89, '88210-KZK-901', 190.00, 682.00, 1364.00, 890.00, 8, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(90, '35170-KPL-842', 64.00, 230.00, 460.00, 340.00, 20, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(91, '38301-KPL-901', 167.00, 600.00, 1199.00, 880.00, 10, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(92, '22535-KWP-900', 909.00, 3263.00, 6527.00, 4450.00, 6, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(93, '35175-KTP-900', 102.00, 366.00, 732.00, 590.00, 2, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(94, '05141-KPL-840', 91.00, 327.00, 653.00, 450.00, 40, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(95, '35340-KWP-900', 54.00, 194.00, 388.00, 320.00, 20, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(96, '91201-KWP-D01', 22.00, 79.00, 158.00, 110.00, 70, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(97, '91203-KWP-D01', 30.00, 108.00, 215.00, 150.00, 90, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(98, '91202-KWP-D01', 56.00, 201.00, 402.00, 280.00, 17, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(99, '12391-KWP-900', 324.00, 1163.00, 2326.00, 1690.00, 5, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(100, '91225-KWP-D01', 36.00, 129.00, 258.00, 180.00, 200, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(101, '23100-KWP-D00', 456.00, 1637.00, 3274.00, 2200.00, 50, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(102, '43450-KZK-910', 246.00, 883.00, 1766.00, 1290.00, 8, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(103, '22123-KWN-780', 333.00, 1195.00, 2391.00, 1950.00, 40, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(104, '22110-KWPD00', 615.00, 2208.00, 4416.00, 2980.00, 40, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(105, '22011-KPL-940', 136.00, 488.00, 976.00, 560.00, 50, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(106, 'YFZ61', 310.00, 560.00, 1120.00, 940.00, 10, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(107, 'HAS61', 215.00, 422.00, 844.00, 650.00, 10, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(108, '1141A', 22.00, 55.00, 110.00, 70.00, 188, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(109, '1014', 17.00, 55.00, 110.00, 70.00, 170, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(110, 'F00240517ENG', 110.00, 230.00, 460.00, 340.00, 20, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(111, '26024563 LUCAS', 1775.00, 4650.00, 9300.00, 6850.00, 2, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(112, '037-SVM-FZL', 222.00, 800.00, 1600.00, 1190.00, 2, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(113, '037-SVMFZR', 222.00, 800.00, 1600.00, 1190.00, 7, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(114, '045-BLA-NMR', 231.00, 750.00, 1500.00, 1150.00, 9, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(115, '045-BLA-NML', 231.00, 750.00, 1500.00, 1150.00, 4, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(116, '045-SVM-DNL', 125.00, 750.00, 1500.00, 1150.00, 5, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(117, '045-SVM-DNR', 125.00, 750.00, 1500.00, 1150.00, 10, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(118, '058-SVM- LEFT', 200.00, 900.00, 1800.00, 1490.00, 1, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(119, '058-SVM-RIGHT', 200.00, 900.00, 1800.00, 1490.00, 2, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(120, 'DIO BRAKE SHOE', 203.00, 380.00, 760.00, 590.00, 20, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(121, 'HS1/12V/35/35W', 41.72, 200.00, 400.00, 340.00, 100, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(122, 'R10W/12V/10W', 3.00, 12.00, 24.00, 20.00, 400, 'SUP0001', '2024-10-18 00:32:31', 'True'),
(127, '1GC-E4450-00', 175.00, 585.00, 1750.00, 950.00, 40, 'SUP0001', '2024-11-29 03:01:39', 'True'),
(129, '1GC-E5473-00', 115.00, 380.00, 1250.00, 690.00, 18, 'SUP0001', '2024-11-29 03:21:59', 'True'),
(130, '1GC-E6620-20', 1521.00, 5080.00, 14500.00, 7600.00, 10, 'SUP0001', '2024-11-29 03:24:09', 'True'),
(131, '1GC-E7632-01	', 46.00, 151.00, 390.00, 290.00, 198, 'SUP0001', '2024-11-29 03:27:42', 'True'),
(132, '1GC-E7641-10', 570.00, 1880.00, 3950.00, 2950.00, 42, 'SUP0001', '2024-11-29 03:29:59', 'True'),
(133, '1GC-H276A-00', 55.00, 182.00, 690.00, 350.00, 50, 'SUP0001', '2024-11-29 05:57:06', 'True'),
(134, '1GC-H3371-00', 190.00, 635.00, 1750.00, 980.00, 10, 'SUP0001', '2024-11-29 06:03:15', 'True'),
(135, '1GC-H396U-00', 60.00, 200.00, 690.00, 350.00, 50, 'SUP0001', '2024-11-29 06:53:37', 'True'),
(136, '1GC-H39700-00', 65.00, 210.00, 690.00, 350.00, 50, 'SUP0001', '2024-11-29 06:55:36', 'True'),
(137, '1GC-H575200-00', 315.00, 1050.00, 2950.00, 1650.00, 10, 'SUP0001', '2024-11-29 06:57:24', 'True'),
(138, '1PM-F5805-00', 345.00, 1138.00, 2250.00, 1750.00, 48, 'SUP0001', '2024-11-29 07:02:35', 'True'),
(139, '21C-E632400-00', 150.00, 495.00, 1650.00, 850.00, 20, 'SUP0001', '2024-11-29 07:05:09', 'True'),
(140, '21C-E6351-00', 190.00, 635.00, 1950.00, 1150.00, 10, 'SUP0001', '2024-11-29 07:07:29', 'True'),
(141, '21C-F31100-00', 2002.00, 6685.00, 14500.00, 10000.00, 12, 'SUP0001', '2024-11-29 07:08:46', 'True'),
(142, '21C-F620B-21', 45.00, 150.00, 500.00, 250.00, 100, 'SUP0001', '2024-11-29 07:10:41', 'True'),
(143, '2GS-E4450-00', 235.00, 775.00, 1850.00, 1180.00, 40, 'SUP0001', '2024-11-29 07:15:42', 'True'),
(144, '2GS-E8110-11', 330.00, 1090.00, 2100.00, 1680.00, 20, 'SUP0001', '2024-11-29 07:17:34', 'True'),
(145, '2GS-F3144-00', 258.00, 850.00, 1750.00, 1340.00, 50, 'SUP0001', '2024-11-29 07:19:25', 'True'),
(146, '2GS-F3145-00', 356.00, 1175.00, 1950.00, 1780.00, 84, 'SUP0001', '2024-11-29 07:21:30', 'True'),
(147, '2GS-F317-00', 220.00, 725.00, 1450.00, 1200.00, 1, 'SUP0001', '2024-11-29 07:22:57', 'True'),
(148, '2GS-H1960-10', 920.00, 3035.00, 6750.00, 4600.00, 20, 'SUP0001', '2024-11-29 07:25:11', 'True'),
(149, '2GS-H3500-00', 2763.00, 7925.00, 19500.00, 13850.00, 5, 'SUP0001', '2024-11-29 07:27:27', 'True'),
(150, '2GS-H3963-00', 415.00, 1370.00, 2450.00, 2150.00, 20, 'SUP0001', '2024-11-29 07:30:05', 'True'),
(151, '2GS-H3972-01', 410.00, 1352.00, 2450.00, 2150.00, 20, 'SUP0001', '2024-11-29 07:31:22', 'True'),
(152, '2GS-H4300-00', 1224.00, 4035.00, 10500.00, 6250.00, 5, 'SUP0001', '2024-11-29 07:33:01', 'True'),
(153, '2GS-W0045-00', 200.00, 660.00, 1750.00, 1050.00, 69, 'SUP0001', '2024-11-29 07:35:09', 'True'),
(154, '2GS-XH2500-00', 1250.00, 4125.00, 10000.00, 6950.00, 10, 'SUP0001', '2024-11-29 07:37:37', 'True'),
(155, '9470300-83400', 214.00, 885.00, 1550.00, 1100.00, 60, 'SUP0001', '2024-11-29 07:38:57', 'True'),
(156, 'B62-F5190-00', 305.00, 1000.00, 1950.00, 1590.00, 40, 'SUP0001', '2024-11-29 07:40:42', 'True'),
(157, 'B62-F530K-00', 330.00, 1090.00, 2100.00, 1750.00, 60, 'SUP0001', '2024-11-29 07:42:03', 'True'),
(158, 'B62-F582U-10', 614.00, 2025.00, 5200.00, 3150.00, 10, 'SUP0001', '2024-11-29 07:43:37', 'True'),
(159, 'B97-F5806-00', 995.00, 2870.00, 5200.00, 4980.00, 22, 'SUP0001', '2024-11-29 07:45:03', 'True'),
(160, '2GS-SF4124-00', 135.00, 445.00, 1350.00, 800.00, 8, 'SUP0001', '2024-11-30 01:42:58', 'True'),
(161, '10026552', 1500.00, 2500.00, 4500.00, 3500.00, 10, 'SUP0001', '2025-02-05 21:54:40', 'True');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_roles`
--

CREATE TABLE `tbl_roles` (
  `Id` int(11) NOT NULL,
  `Role_Id` varchar(11) NOT NULL,
  `Role_Name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_roles`
--

INSERT INTO `tbl_roles` (`Id`, `Role_Id`, `Role_Name`) VALUES
(1, 'ROL0001', 'Super Admin'),
(3, 'ROL0002', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_screens`
--

CREATE TABLE `tbl_screens` (
  `Screen_Id` int(11) NOT NULL,
  `Screen_Name` varchar(100) NOT NULL,
  `Screen_Category` varchar(20) NOT NULL,
  `Screen_Sub_Category` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_screens`
--

INSERT INTO `tbl_screens` (`Screen_Id`, `Screen_Name`, `Screen_Category`, `Screen_Sub_Category`) VALUES
(300, 'add_suppliers.php', 'Suppliers', 'View'),
(301, 'view_supplier.php', 'Suppliers', 'View'),
(302, 'add_categories.php', 'Categories', 'View'),
(303, 'add_customers.php', 'Customers', 'View'),
(304, 'add_product-history.php', 'Product Details', 'View'),
(305, 'add_products.php', 'Products', 'View'),
(306, 'view_category.php', 'Categories', 'View'),
(307, 'view_customer.php', 'Customers', 'View'),
(308, 'view_product-details.php', 'Product Details', 'View'),
(309, 'view_product.php', 'Products', 'View'),
(310, 'add_roles.php', 'Roles', 'View'),
(311, 'view_role.php', 'Roles', 'View'),
(312, 'add_users.php', 'Users', 'View'),
(313, 'view_user.php', 'Users', 'View'),
(314, 'pos.php', 'POS', 'View'),
(315, 'printInvoice.php', 'POS', 'View'),
(316, 'printInvoiceCopy.php', 'Invoice', 'View'),
(317, 'sales_invoice.php', 'Invoice', 'View'),
(318, 'view_invoice.php', 'Invoice', 'View'),
(319, 'inventory_report.php', 'Reports', 'Inventory Report'),
(320, 'printInventoryReport.php', 'Reports', 'Inventory Report'),
(321, 'pos2.php', 'POS', 'View'),
(322, 'sales_report.php', 'Reports', 'Sales Report'),
(323, 'printSalesReport.php', 'Reports', 'Sales Report'),
(324, 'add_brand.php', 'Brands', 'View'),
(325, 'view_brand.php', 'Brands', 'View'),
(326, 'add_expenses_types.php', 'Expenses Types', 'View'),
(327, 'view_expenses_types.php', 'Expenses Types', 'View'),
(328, 'add_expenses.php', 'Expenses', 'View'),
(329, 'view_expenses.php', 'Expenses', 'View'),
(330, 'reverse_payment.php', 'Reverse Payment', 'View'),
(331, 'reverse_expense.php', 'Reverse Expense', 'View'),
(332, 'cash_flow.php', 'Cash Flow', 'View'),
(333, 'settings.php', 'System Information', 'View');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_screen_permissions`
--

CREATE TABLE `tbl_screen_permissions` (
  `Permission_Id` int(11) NOT NULL,
  `Role` varchar(50) NOT NULL,
  `Screen_Id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_screen_permissions`
--

INSERT INTO `tbl_screen_permissions` (`Permission_Id`, `Role`, `Screen_Id`) VALUES
(375, 'Super Admin', 310),
(376, 'Super Admin', 311),
(377, 'Super Admin', 302),
(378, 'Super Admin', 306),
(379, 'Super Admin', 303),
(380, 'Super Admin', 307),
(381, 'Super Admin', 305),
(382, 'Super Admin', 309),
(383, 'Super Admin', 300),
(384, 'Super Admin', 301),
(385, 'Super Admin', 304),
(386, 'Super Admin', 308),
(387, 'Super Admin', 312),
(388, 'Super Admin', 313),
(389, 'Super Admin', 314),
(390, 'Super Admin', 315),
(391, 'Super Admin', 321),
(392, 'Super Admin', 316),
(393, 'Super Admin', 317),
(394, 'Super Admin', 318),
(395, 'Super Admin', 319),
(396, 'Super Admin', 320),
(399, 'Super Admin', 324),
(400, 'Super Admin', 325),
(401, 'Super Admin', 326),
(402, 'Super Admin', 327),
(403, 'Super Admin', 330),
(404, 'Super Admin', 328),
(405, 'Super Admin', 329),
(406, 'Super Admin', 331),
(407, 'Super Admin', 332),
(408, 'Admin', 302),
(409, 'Admin', 306),
(410, 'Admin', 303),
(411, 'Admin', 307),
(412, 'Admin', 305),
(413, 'Admin', 309),
(414, 'Admin', 300),
(415, 'Admin', 301),
(416, 'Admin', 304),
(417, 'Admin', 308),
(418, 'Admin', 310),
(419, 'Admin', 311),
(420, 'Admin', 312),
(421, 'Admin', 313),
(422, 'Admin', 314),
(423, 'Admin', 315),
(424, 'Admin', 321),
(425, 'Admin', 316),
(426, 'Admin', 317),
(427, 'Admin', 318),
(428, 'Admin', 319),
(429, 'Admin', 320),
(430, 'Admin', 322),
(431, 'Admin', 323),
(432, 'Admin', 324),
(433, 'Admin', 325),
(434, 'Admin', 326),
(435, 'Admin', 327),
(436, 'Admin', 330),
(437, 'Admin', 328),
(438, 'Admin', 329),
(439, 'Admin', 331),
(440, 'Admin', 332),
(443, 'Super Admin', 322),
(444, 'Super Admin', 323),
(446, 'Super Admin', 333);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_suppliers`
--

CREATE TABLE `tbl_suppliers` (
  `Id` int(11) NOT NULL,
  `Supplier_Id` varchar(11) NOT NULL,
  `Supplier_Name` varchar(150) NOT NULL,
  `Supplier_Address` varchar(1000) DEFAULT NULL,
  `Supplier_Contact` varchar(15) NOT NULL,
  `Supplier_Email` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_suppliers`
--

INSERT INTO `tbl_suppliers` (`Id`, `Supplier_Id`, `Supplier_Name`, `Supplier_Address`, `Supplier_Contact`, `Supplier_Email`) VALUES
(1, 'SUP0001', 'Popular Motorcycle Company', 'No.165/1,165/2,166/2a,166/2b,166/2c,166/2d,168/1a,8/1 Manali Express Way, Manjambakkam, Madhavaram-600103', '61096333', 'admin@populargroup.co.in');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_system_configuration`
--

CREATE TABLE `tbl_system_configuration` (
  `Id` int(11) NOT NULL,
  `ServiceCharge_IsPercentage` tinyint(1) DEFAULT NULL,
  `ServiceCharge` float(10,2) DEFAULT NULL,
  `Tax_IsPercentage` tinyint(1) DEFAULT NULL,
  `Tax` float(10,2) DEFAULT NULL,
  `Vat_IsPercentage` tinyint(1) DEFAULT NULL,
  `Vat` float(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_system_configuration`
--

INSERT INTO `tbl_system_configuration` (`Id`, `ServiceCharge_IsPercentage`, `ServiceCharge`, `Tax_IsPercentage`, `Tax`, `Vat_IsPercentage`, `Vat`) VALUES
(1, 0, 1000.00, 1, 20.00, 1, 15.00);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_temp_invoice`
--

CREATE TABLE `tbl_temp_invoice` (
  `Id` int(11) NOT NULL,
  `Value` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_temp_invoice`
--

INSERT INTO `tbl_temp_invoice` (`Id`, `Value`) VALUES
(1, '7');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `Id` int(11) NOT NULL,
  `First_Name` varchar(50) NOT NULL,
  `Last_Name` varchar(50) NOT NULL,
  `Username` varchar(20) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `Status` varchar(20) NOT NULL,
  `Img` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`Id`, `First_Name`, `Last_Name`, `Username`, `Password`, `Status`, `Img`) VALUES
(1, 'System', 'Administrator', 'system_admin', 'e10adc3949ba59abbe56e057f20f883e', 'Super Admin', 'http://localhost/Orbis_Solutions_Inventory_System/Images/Admins/default_profile.png'),
(2, 'Prasanna', 'Motors', 'prasanna', '5f4dcc3b5aa765d61d8327deb882cf99', 'Admin', 'http://localhost/Orbis_Solutions_Inventory_System/Images/Admins/default_profile.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_backend`
--
ALTER TABLE `tbl_backend`
  ADD PRIMARY KEY (`Backend_Id`);

--
-- Indexes for table `tbl_backend_permissions`
--
ALTER TABLE `tbl_backend_permissions`
  ADD PRIMARY KEY (`Permission_Id`),
  ADD KEY `Backend_Id` (`Backend_Id`),
  ADD KEY `Role` (`Role`);

--
-- Indexes for table `tbl_brand`
--
ALTER TABLE `tbl_brand`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Brand_Id` (`Brand_Id`);

--
-- Indexes for table `tbl_cash_flow`
--
ALTER TABLE `tbl_cash_flow`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Transaction_Id` (`Income_Transaction_Id`),
  ADD KEY `Expense_Transaction_Id` (`Expense_Transaction_Id`);

--
-- Indexes for table `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Category_Id` (`Category_Id`),
  ADD UNIQUE KEY `Category_Id_3` (`Category_Id`),
  ADD KEY `Category_Id_2` (`Category_Id`);

--
-- Indexes for table `tbl_company_info`
--
ALTER TABLE `tbl_company_info`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tbl_customers`
--
ALTER TABLE `tbl_customers`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Customer_Id` (`Customer_Id`);

--
-- Indexes for table `tbl_expenses`
--
ALTER TABLE `tbl_expenses`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Expense_Id` (`Expense_Id`),
  ADD KEY `Expense_Type_Id` (`Expense_Type_Id`),
  ADD KEY `User_Id` (`User_Id`);

--
-- Indexes for table `tbl_expenses_categories`
--
ALTER TABLE `tbl_expenses_categories`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Expense_Category_Id` (`Expense_Category_Id`);

--
-- Indexes for table `tbl_expenses_types`
--
ALTER TABLE `tbl_expenses_types`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Expense_Type_Id` (`Expense_Type_Id`),
  ADD KEY `Expense_Category_Id` (`Expense_Category_Id`);

--
-- Indexes for table `tbl_expense_payments`
--
ALTER TABLE `tbl_expense_payments`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Expense_Id` (`Expense_Id`,`Updated_By`),
  ADD KEY `Updated_By` (`Updated_By`);

--
-- Indexes for table `tbl_invoice`
--
ALTER TABLE `tbl_invoice`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Invoice_Id` (`Invoice_Id`),
  ADD UNIQUE KEY `Invoice_No` (`Invoice_Id`),
  ADD UNIQUE KEY `Invoice_Id_2` (`Invoice_Id`),
  ADD KEY `Customer_Id` (`Customer_Id`),
  ADD KEY `User_Id` (`User_Id`);

--
-- Indexes for table `tbl_item`
--
ALTER TABLE `tbl_item`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Product_Id` (`Product_Id`),
  ADD KEY `Product_Detail_Id` (`Product_Detail_Id`);

--
-- Indexes for table `tbl_payments`
--
ALTER TABLE `tbl_payments`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Invoice_Id` (`Invoice_Id`),
  ADD KEY `Updated_By` (`Updated_By`);

--
-- Indexes for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `pId` (`Product_Id`),
  ADD KEY `Category_Id` (`Category_Id`),
  ADD KEY `Brand_Id` (`Brand_Id`);

--
-- Indexes for table `tbl_product_details`
--
ALTER TABLE `tbl_product_details`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Product_Id` (`Product_Id`),
  ADD KEY `Supplier_Id` (`Supplier_Id`);

--
-- Indexes for table `tbl_roles`
--
ALTER TABLE `tbl_roles`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Role_Id` (`Role_Id`,`Role_Name`),
  ADD KEY `Role_Name` (`Role_Name`);

--
-- Indexes for table `tbl_screens`
--
ALTER TABLE `tbl_screens`
  ADD PRIMARY KEY (`Screen_Id`);

--
-- Indexes for table `tbl_screen_permissions`
--
ALTER TABLE `tbl_screen_permissions`
  ADD PRIMARY KEY (`Permission_Id`),
  ADD KEY `Screen_Id` (`Screen_Id`),
  ADD KEY `Role` (`Role`);

--
-- Indexes for table `tbl_suppliers`
--
ALTER TABLE `tbl_suppliers`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Supplier_Id` (`Supplier_Id`);

--
-- Indexes for table `tbl_system_configuration`
--
ALTER TABLE `tbl_system_configuration`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tbl_temp_invoice`
--
ALTER TABLE `tbl_temp_invoice`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD KEY `Status` (`Status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_backend`
--
ALTER TABLE `tbl_backend`
  MODIFY `Backend_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=176;

--
-- AUTO_INCREMENT for table `tbl_backend_permissions`
--
ALTER TABLE `tbl_backend_permissions`
  MODIFY `Permission_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=928;

--
-- AUTO_INCREMENT for table `tbl_brand`
--
ALTER TABLE `tbl_brand`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_cash_flow`
--
ALTER TABLE `tbl_cash_flow`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tbl_company_info`
--
ALTER TABLE `tbl_company_info`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_customers`
--
ALTER TABLE `tbl_customers`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_expenses`
--
ALTER TABLE `tbl_expenses`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `tbl_expenses_categories`
--
ALTER TABLE `tbl_expenses_categories`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_expenses_types`
--
ALTER TABLE `tbl_expenses_types`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_expense_payments`
--
ALTER TABLE `tbl_expense_payments`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `tbl_invoice`
--
ALTER TABLE `tbl_invoice`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_item`
--
ALTER TABLE `tbl_item`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_payments`
--
ALTER TABLE `tbl_payments`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_product`
--
ALTER TABLE `tbl_product`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT for table `tbl_product_details`
--
ALTER TABLE `tbl_product_details`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT for table `tbl_roles`
--
ALTER TABLE `tbl_roles`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_screens`
--
ALTER TABLE `tbl_screens`
  MODIFY `Screen_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=334;

--
-- AUTO_INCREMENT for table `tbl_screen_permissions`
--
ALTER TABLE `tbl_screen_permissions`
  MODIFY `Permission_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=447;

--
-- AUTO_INCREMENT for table `tbl_suppliers`
--
ALTER TABLE `tbl_suppliers`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_system_configuration`
--
ALTER TABLE `tbl_system_configuration`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_temp_invoice`
--
ALTER TABLE `tbl_temp_invoice`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_backend_permissions`
--
ALTER TABLE `tbl_backend_permissions`
  ADD CONSTRAINT `tbl_backend_permissions_ibfk_1` FOREIGN KEY (`Backend_Id`) REFERENCES `tbl_backend` (`Backend_Id`),
  ADD CONSTRAINT `tbl_backend_permissions_ibfk_2` FOREIGN KEY (`Role`) REFERENCES `tbl_user` (`Status`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_cash_flow`
--
ALTER TABLE `tbl_cash_flow`
  ADD CONSTRAINT `tbl_cash_flow_ibfk_1` FOREIGN KEY (`Income_Transaction_Id`) REFERENCES `tbl_payments` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_cash_flow_ibfk_2` FOREIGN KEY (`Expense_Transaction_Id`) REFERENCES `tbl_expense_payments` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_expenses`
--
ALTER TABLE `tbl_expenses`
  ADD CONSTRAINT `tbl_expenses_ibfk_1` FOREIGN KEY (`Expense_Type_Id`) REFERENCES `tbl_expenses_types` (`Expense_Type_Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_expenses_ibfk_2` FOREIGN KEY (`User_Id`) REFERENCES `tbl_user` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_expenses_types`
--
ALTER TABLE `tbl_expenses_types`
  ADD CONSTRAINT `tbl_expenses_types_ibfk_1` FOREIGN KEY (`Expense_Category_Id`) REFERENCES `tbl_expenses_categories` (`Expense_Category_Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_expense_payments`
--
ALTER TABLE `tbl_expense_payments`
  ADD CONSTRAINT `tbl_expense_payments_ibfk_1` FOREIGN KEY (`Expense_Id`) REFERENCES `tbl_expenses` (`Expense_Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_expense_payments_ibfk_2` FOREIGN KEY (`Updated_By`) REFERENCES `tbl_user` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_invoice`
--
ALTER TABLE `tbl_invoice`
  ADD CONSTRAINT `tbl_invoice_ibfk_1` FOREIGN KEY (`Customer_Id`) REFERENCES `tbl_customers` (`Customer_Id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_invoice_ibfk_2` FOREIGN KEY (`User_Id`) REFERENCES `tbl_user` (`Id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tbl_payments`
--
ALTER TABLE `tbl_payments`
  ADD CONSTRAINT `tbl_payments_ibfk_1` FOREIGN KEY (`Invoice_Id`) REFERENCES `tbl_invoice` (`Invoice_Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_payments_ibfk_2` FOREIGN KEY (`Updated_By`) REFERENCES `tbl_user` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD CONSTRAINT `tbl_product_ibfk_1` FOREIGN KEY (`Category_Id`) REFERENCES `tbl_category` (`Category_Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_product_ibfk_2` FOREIGN KEY (`Brand_Id`) REFERENCES `tbl_brand` (`Brand_Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_product_details`
--
ALTER TABLE `tbl_product_details`
  ADD CONSTRAINT `tbl_product_details_ibfk_1` FOREIGN KEY (`Product_Id`) REFERENCES `tbl_product` (`Product_Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_product_details_ibfk_2` FOREIGN KEY (`Supplier_Id`) REFERENCES `tbl_suppliers` (`Supplier_Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_screen_permissions`
--
ALTER TABLE `tbl_screen_permissions`
  ADD CONSTRAINT `tbl_screen_permissions_ibfk_1` FOREIGN KEY (`Screen_Id`) REFERENCES `tbl_screens` (`Screen_Id`),
  ADD CONSTRAINT `tbl_screen_permissions_ibfk_2` FOREIGN KEY (`Role`) REFERENCES `tbl_user` (`Status`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD CONSTRAINT `tbl_user_ibfk_1` FOREIGN KEY (`Status`) REFERENCES `tbl_roles` (`Role_Name`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
