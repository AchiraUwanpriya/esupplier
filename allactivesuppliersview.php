<?php
session_start();
include 'config.php';

if (!isset($_SESSION['mobile_number']) || !isset($_SESSION['name']) || !isset($_SESSION['entry'])) {
  header('Location: admin.php');
  exit();
}

include_once 'helper.php';
$updatedate = date('Y-m-d');

$entry = $_SESSION['entry'];

if ($entry == 'N') {
  $ButtonsDisabled = true;
} else {
  $ButtonsDisabled = false;
}

// Select Query
function selectquery($query)
{
  global $con;
  $query_run = mysqli_query($con, $query);

  if ($query_run) {
    $datalist = [];
    while ($row = mysqli_fetch_array($query_run, MYSQLI_ASSOC)) {
      array_push($datalist, $row);
    }
    return $datalist;
  } else {
    //   print_r(sqlsrv_errors());
    print_r(mysqli_error($con));
  }
}

function runquery($query)
{
  global $con;
  // $query_run = sqlsrv_query($con,$query); 
  $query_run = mysqli_query($con, $query);
  if ($query_run) {
    echo "Data Inserted Successfully!";
  } else {
    //   print_r(sqlsrv_errors());
    print_r(mysqli_error($con));
  }
}

// Update Attachement status to Inactive

if (isset($_POST['delete'])) {
  $qry = "UPDATE mms_supplier_attachments SET msd_status='I',updated_by='" . $_SESSION['User'] . "',updated_date='$updatedate' WHERE msd_serial_no = '" . $_POST['msd_serial_no'] . "'";
  runquery($qry);
}

// get Suppiler Details
$supplierCode = isset($_GET['suppliercode']) ? $_GET['suppliercode'] : null;

$suppliermobile = isset($_GET['supmobile']) ? $_GET['supmobile'] : null;

$updateModalOpen = false;
$supplierDetails = [];
if ($supplierCode) {
  $updateModalOpen = true;
  $query = "SELECT mmssd.*, mmssb.MSB_BANK_STATEMENT FROM mms_suppliers_details mmssd
  LEFT JOIN mms_supplier_banks mmssb ON mmssd.msd_supplier_code = mmssb.MSB_SUPPLIER_CODE 
  WHERE mmssd.msd_supplier_code='$supplierCode'
  GROUP BY mmssd.msd_supplier_code;";
  $datalist = selectquery($query);
  if (count($datalist) === 1)
    $supplierDetails = $datalist[0];
  $query = "SELECT msd_serial_no,msd_file_name,msd_file_path,msd_status FROM mms_supplier_attachments WHERE msd_sup_code = '$supplierCode' AND msd_status = 'A'";
  $datalist = selectquery($query);
  if (count($datalist) > 0)
    $supplierDetails['attachments'] = $datalist;
}


// update sup reference number
if (isset($_POST['updateRefer'])) {
  $referenceNo = $_POST['msd_supplier_reference_no'];

  // Sanitize and validate the input
  $referenceNo = mysqli_real_escape_string($con, $referenceNo);

  $query = "UPDATE mms_suppliers_details SET msd_supplier_reference_no = '$referenceNo'
     WHERE msd_supplier_code = '$supplierCode'";

  $query_run = mysqli_query($con, $query);

  if ($query_run) {
    echo "<script>alert('Records Updated Successfully!!'); window.location.href = window.location.href;</script>";
    // echo "<script>window.location.reload();</script>"; 
  } else {
    echo "Please fill the fields!";
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link rel="shortcut icon" href="./static/img/2.svg" />

  <title>eSupplier-CDL</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css" crossorigin="anonymous" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <link href="./static/css/app.css" rel="stylesheet">
  <link href="./static/css/main.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

  <!-- Material details -->
  <style>
    .btn {
      background-color: DodgerBlue;
      border: none;
      color: white;
      cursor: pointer;
    }

    /* Darker background on mouse-over */
    .btn:hover {
      background-color: RoyalBlue;
    }
  </style>

  <script src="./static/js/jquery-3.3.1.min.js"></script>
  <script src="./static/js/jquery.validate.min.js"></script>
  <script src="./static/js/jquery.validate.unobtrusive.min.js"></script>

  <script src="./static/js/app.js"></script>

  <!-- checkbox -->
  <script>
    function myFunction1() {
      // Get the checkbox
      var checkBox = document.getElementById("myCheck");
      // Get the output text
      var text = document.getElementById("text");

      // If the checkbox is checked, display the output text
      if (checkBox.checked == true) {
        text.style.display = "block";
      } else {
        text.style.display = "none";
      }
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

</head>

<body>
  <div class="wrapper">
    <nav id="sidebar" class="sidebar js-sidebar">
      <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="adminview.php">
          <!-- <span class="align-middle">eSupplier-CDL</span> -->
          <center><img src="./static/img/8.png" class="mt-3" style=" width: 100%; padding-right: 30px;" alt=""></center>
        </a>

        <ul class="sidebar-nav">
          <li class="sidebar-header">
            Supplier Managment
          </li>
          <li class="sidebar-item ">
            <a class="sidebar-link" href="allsuppliersview.php">
              <i class="align-middle" data-feather="user-check"></i> <span class="align-middle">Pending Suppliers</span>
            </a>
          </li>
          <li class="sidebar-item active">
            <a class="sidebar-link" href="allactivesuppliersview.php">
              <i class="align-middle" data-feather="users"></i> <span class="align-middle">Registered Suppliers</span>
            </a>
          </li>

          <li class="sidebar-header">
            Tender Managment
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="tenderview.php">
              <i class="align-middle" data-feather="trending-up"></i> <span class="align-middle">Tenders</span>
            </a>
          </li>
          <li class="sidebar-item ">
						 <a class="sidebar-link" href="monthlytenderview.php">
							<i class="align-middle" data-feather="trending-up"></i> <span class="align-middle">Monthly Tenders</span>
						</a>
					</li>
          <?php if ($entry != 'N') : ?>
          <li class="sidebar-header">
            Food Managment
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="addfood.php">
              <i class="align-middle" data-feather="shopping-cart"></i> <span class="align-middle">Add Food</span>
            </a>
          </li>
          <?php endif; ?>
        </ul>

        <div class="sidebar-cta">
          <div class="sidebar-cta-content">
            <div class="d-grid">
              <a href="adminlogout.php" class="btn btn-primary" onclick="logoutfunction()">Logout</a>

            </div>
          </div>
        </div>
      </div>
    </nav>

    <div class="main">
      <nav class="navbar navbar-expand navbar-light navbar-bg">
        <a class="sidebar-toggle js-sidebar-toggle">
          <i class="hamburger align-self-center"></i>
        </a>
        <a href="" style="color: blue; font-weight: bolder; text-decoration: none;">
          HELLO <?php echo $_SESSION['name'] ?>! WELCOME TO eSupplier-CDPLC ADMIN DASHBOARD!!!
        </a>
        <div class="navbar-collapse collapse">
          <ul class="navbar-nav navbar-align">
            <li class="nav-item dropdown">
              <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                <i class="align-middle" data-feather="settings"></i>
              </a>

              <a class="nav-link d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                <img src="./static/img/avatars/avatar1.jpg" class="avatar img-fluid rounded me-1" alt="Charles Hall" /> <span class="text-dark"><?php echo $_SESSION['name'] ?></span>
              </a>
              <div class="dropdown-menu dropdown-menu-end">
                <!-- <a class="dropdown-item" href=""><i class="align-middle me-1" data-feather="user"></i> Profile</a> -->
                <!-- <a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="pie-chart"></i> Analytics</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.html"><i class="align-middle me-1" data-feather="settings"></i> Settings & Privacy</a>
								<a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="help-circle"></i> Help Center</a> -->
                <div class="dropdown-divider"> </div>
                <!-- <a class="dropdown-item" href="logout.php" onclick="logoutfunction()">Log out</a> -->
                <a href="logout.php" class="dropdown-item" onclick="logoutfunction()">Logout</a>
              </div>
            </li>
          </ul>
        </div>
      </nav>
      <!-- dashboard content -->
      <main class="content">
        <div class="container-fluid p-0">

          <h1 class="h3 mb-3">
            <strong>
              eSupplier All Approved
            </strong>
          </h1>

          <div style="height: 100%;">
            <div class="content">
              <div class="row">
                <div class="col-md-12">
                  <div class="table-responsive">
                    <table class="table table-hover table-bordered rounded">
                      <thead>
                        <tr style="background-color: mediumseagreen; color: white;">
                          <th class="fw-bold" scope="col">Supplier Code</th>
                          <th class="fw-bold" scope="col">Supplier Name</th>
                          <th class="fw-bold" scope="col">Email</th>
                          <th class="fw-bold" scope="col">Mobile</th>
                          <th class="fw-bold" scope="col">Supplier Category</th>
                          <th class="fw-bold" scope="col">Address</th>
                          <th scope="col">Status</th>
                          <th class="fw-bold" scope="col">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $tsql = "SELECT *
                                FROM mms_suppliers_details where msd_status IN('A','C') ORDER BY msd_supplier_code DESC";
                        $stmt = mysqli_query($con, $tsql);
                        if ($stmt === false) {
                          echo "Error in query";
                          die(print_r(mysqli_error($con), true));
                        }
                        $index = 0;
                        while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
                        ?>
                          <tr>
                            <td><?php echo $row['msd_supplier_code']; ?>
                              <input type="text" hidden name="msd_supplier_code[<?= $index ?>]" value="<?php echo $row['msd_supplier_code']; ?>">
                            </td>
                            <td><?php echo $row['msd_supplier_name']; ?>
                              <input type="text" hidden name="msd_supplier_name[<?= $index ?>]" value="<?php echo $row['msd_supplier_name']; ?>">
                            </td>
                            <td><?php echo $row['msd_email_address']; ?>
                              <input type="text" hidden name="msd_email_address[<?= $index ?>]" value="<?php echo $row['msd_email_address']; ?>">
                            </td>
                            <td><?php echo $row['msd_mobileno']; ?>
                              <input type="number" hidden name="msd_mobileno[<?= $index ?>]" value="<?php echo $row['msd_mobileno']; ?>">
                            </td>
                            <td><?php echo $row['msd_supply_category']; ?>
                              <input type="text" hidden name="msd_supply_category[<?= $index ?>]" value="<?php echo $row['msd_supply_category']; ?>" hidden>
                            </td>
                            <td><?php echo $row['msd_address']; ?>
                              <input type="text" hidden name="msd_address[<?= $index ?>]" value="<?php echo $row['msd_address']; ?>" hidden>
                            </td>
                            <td><?php echo $row['msd_status']; ?>
                              <input type="text" hidden name="msd_status[<?= $index ?>]" value="<?php echo $row['msd_status']; ?>">
                            </td>
                            <td>
                              <a class="btn btn-primary updatebtn" href="?suppliercode=<?= $row['msd_supplier_code'] ?>&supmobile=<?= $row['msd_mobileno'] ?>">Show More</a>
                            </td>
                          </tr>
                        <?php
                          $index++;
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>

      <!-- footer -->
      <?php include './components/footer.php' ?>


      <?php
      if ($updateModalOpen) {
        echo '<div class="modal-backdrop fade show" onclick="closeModal()"></div>';
      }
      ?>

      <div class="modal fade <?= $updateModalOpen ? "show" : "" ?>" id="exampleModalScrollableupdate" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="<?= $updateModalOpen ? "false" : "true" ?>" aria-modal="<?= $updateModalOpen ? "true" : "false" ?>" style="display:<?= $updateModalOpen ? "block" : "none" ?>">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" id="updateSupplierModal" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h2 class="modal-title text-info" id="exampleModalScrollableTitle">SUPPLIER DETAILS</h2>
	      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal();"></button>
            </div>
            <div class="modal-body">
              <div class="col-md-12 col-xl-12">
                <div class="card">
                  <div class="card-header">

                    <h5 class="card-title mb-0">Update the details</h5>
                  </div>
                  <div class="card-body h-100">
                    <form method="POST" id="profUpdate" name="profUpdate">
                      <div class="form-row">

                        <div class="form-group col-md-10">
                          <input type="text" class="form-control" name="supcode" id="supcode" placeholder="Type your name" value="<?= $supplierDetails['msd_supplier_code'] ?>" disabled>
                        </div>

                        <div class="form-group col-md-10">
                          <label for="inputAddress2">Supplier Name</label>
                          <input type="text" class="form-control" name="supname" id="supname" placeholder="Type your name" value="<?= $supplierDetails['msd_supplier_name'] ?>" disabled>
                        </div>
                        <div class="form-group col-md-2">
                          <!-- <label for="inputAddress2">Supplier Code</label> -->
                          <input type="number" class="form-control" name="supcode" value="" id="supcode" hidden>
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="form-group col-md-10">
                          <label for="inputAddress2">Supplier Category</label>
                          <input type="text" class="form-control" name="supcat" id="supcat" placeholder="Fish, Vegetables, Spices, Rice / Oil and Coconut, Dry Fish" disabled value="<?= $supplierDetails['msd_supply_category'] ?>">
                        </div>
                        <div class="form-group col-md-2">
                          <label for="bsnature">Business Nature</label>
                          <!-- <input type="text" class="form-control" name="country" id="country" placeholder="country"> -->
                          <select id="bsnature" name="bsnature" class="form-control" disabled>
                            <option selected disabled hidden value="<?= $supplierDetails['msd_business_nature'] ?>"><?= $supplierDetails['msd_business_nature'] ? $supplierDetails['msd_business_nature'] : "Manufacture" ?></option>
                          </select>
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="form-group col-md-12">
                          <label for="address">Address</label>
                          <input type="text" class="form-control" name="address" id="address" value="<?= $supplierDetails['msd_address'] ?>" disabled>
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label for="officeaddress">Office Address</label>
                          <!-- <input type="text" class="form-control" name="country" id="country" placeholder="country"> -->
                          <input type="text" class="form-control" name="officeaddress" id="officeaddress" placeholder="Office Address" value="<?= $supplierDetails['msd_officeaddress'] ?>" disabled>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="operationaddress">Operation Address</label>
                          <input type="text" class="form-control" name="operationaddress" id="operationaddress" placeholder="Operation Address" value="<?= $supplierDetails['msd_operationaddress'] ?>" disabled>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="postalCode">Postal Code</label>
                          <input type="text" class="form-control" name="postalCode" id="postalCode" placeholder="postal Code" value="<?= $supplierDetails['msd_postalcode'] ?>" disabled>
                        </div>

                      </div>
                      <div class="form-row">
                        <div class="form-group col-md-2">
                          <label for="">Country</label>
                          <!-- <input type="text" class="form-control" name="country" id="country" placeholder="country"> -->
                          <select id="country" name="country" class="form-control" disabled>
                            <option selected>Sri lanka</option>
                          </select>
                        </div>

                        <div class="form-group col-md-4">
                          <label for="telnumber">Telephone Number</label>
                          <input type="text" class="form-control" name="telnumber" id="telnumber" placeholder="Telephone Number(Other)" value="<?= $supplierDetails['msd_teleno'] ?>" disabled>
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-3">
                          <label for="fax">Fax Number</label>
                          <input type="text" class="form-control" name="fax" id="fax" value="<?= $supplierDetails['msd_faxno'] ?>" disabled>
                        </div>
                        <div class="form-group col-md-4">
                          <label for="emailad">Sales email address</label>
                          <input type="email" name="emailad" class="form-control" id="emailad" value="<?= $supplierDetails['msd_email_address'] ?>" disabled>
                        </div>
                        <div class="form-group col-md-5">
                          <label for="web">Web site</label>
                          <input type="text" name="web" class="form-control" id="web" value="<?= $supplierDetails['msd_website'] ?>" disabled>
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="form-group col-md-5">
                          <label for="contactperson">Contact Person</label>
                          <input type="text" name="contactperson" class="form-control" id="contactperson" value="<?= $supplierDetails['msd_contact_person'] ?>" disabled>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="agent">Agent </label>
                          <select id="agent" name="agent" class="form-control" disabled>
                            <option selected disabled hidden value="<?= $supplierDetails['msd_agent'] ?>"><?= $supplierDetails['msd_agent'] ? $supplierDetails['msd_agent'] : "Yes" ?></option>
                          </select>
                        </div>
                      </div>

                      <input type="submit" class="btn btn-info" name="updateSupBtn" id="updateSupBtn" value="Update Details" onclick="supReg();" hidden />
                      <hr>
                    </form>

                    <!-- Refrence number update -->
                    <form method="POST" id="ref" name="ref">
                      <div class="form-group col-md-4">
                        <label for="refNo">Supplier Reference Number:</label>
                        <input type="text" name="msd_supplier_reference_no" class="form-control" id="referenceNo" value="<?= $supplierDetails['msd_supplier_reference_no'] ?>" required>
                        <br>
                        <input type="submit" class="btn btn-info" name="updateRefer" id="updateRefer" value="Update" />
                        <br>
                      </div>
                    </form>
                    <hr>

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                      <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab font-weight-bold" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Bank Details</button>
                      </li>
                      <!-- <li class="nav-item" role="presentation">
                      <button class="nav-link" id="profile-tab fw-bold" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Discrepancies</button>
                    </li> -->
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="contact-tab font-weight-bold" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Tax Details</button>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="matdetails-tab font-weight-bold" data-bs-toggle="tab" data-bs-target="#matdetails" type="button" role="tab" aria-controls="matdetails" aria-selected="false">Material Details</button>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="attachment-tab font-weight-bold" data-bs-toggle="tab" data-bs-target="#attachment" type="button" role="tab" aria-controls="attachment" aria-selected="false">Download Attachments</button>
                      </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">

                      <!-- bank details -->
                      <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <br>

                        <!-- Supplier Bank -->
                        <?php
                        // $suppliercode = $_SESSION['sup_code'];
                        $supplierCode = isset($_GET['suppliercode']) ? $_GET['suppliercode'] : null;

                        $query = "SELECT MMSSB.*, MMSDB.MBD_BANK_NAME, MMSDB2.MBD_BANK_NAME AS BRANCH_NAME FROM mms_supplier_banks MMSSB 
                                  LEFT JOIN mms_bank_details MMSDB ON MMSDB.MBD_CHILD_KEY = MMSSB.MSB_MAIN_BANK_CODE 
                                  LEFT JOIN mms_bank_details MMSDB2 ON MMSDB2.MBD_CHILD_KEY = MMSSB.MSB_CHILD_KEY
                                  WHERE MMSSB.MSB_SUPPLIER_CODE = $supplierCode ";
                        $dataList = selectquery($query);
                        $data = [];
                        if (count($dataList)) {
                          $data = $dataList[0];
                        }

                        ?>
                        <br>
                        <form method="POST" id="bankdetails" name="bankdetails">
                          <br>
                          <div class="form-row">
                            <div class="form-group col-md-6">
                              <label for="mainbank">Main Bank</label>
                              <input type="text" class="form-control" value="<?= getvalue($data, 'MBD_BANK_NAME') ?>" disabled>
                            </div>
                            <div class="form-group col-md-4">
                              <label for="bankcode">Bank Code</label>
                              <input type="text" class="form-control" value="<?= getvalue($data, 'MSB_BANK_CODE') ?>" disabled>
                            </div>
                          </div>

                          <div class="form-row">
                            <div class="form-group col-md-6">
                              <label for="branch">Branch</label>
                              <input type="text" class="form-control" value="<?= getvalue($data, 'BRANCH_NAME') ?>" disabled>
                            </div>

                            <div class="form-group col-md-4">
                              <label for="accnumber">Account Number</label>
                              <input type="text" class="form-control" name="accnumber" id="accnumber" value="<?= getvalue($data, 'MSB_ACCOUNT_NO') ?>" disabled>
                            </div>
                          </div>

                          <div class="form-row">
                            <div class="form-group col-md-6">
                              <label for="acctype">Account Type</label>
                              <!-- <input type="text" class="form-control" name="country" id="country" placeholder="country"> -->
                              <input type="text" class="form-control" value="<?= getvalue($data, 'MSB_ACCOUNT_TYPE') ?>" disabled>
                            </div>
                          </div>
                          <br />
                          <br />
                          <?php
                          if ($supplierDetails['MSB_BANK_STATEMENT'] === "Pending") {
                          ?>
                            <button type="submit" id="approvebank" class="btn bg-success" data-bs-dismiss="modal" onclick="approvedbakdetails()" <?php if ($ButtonsDisabled) echo 'disabled'; ?>>Approve</button>

                          <?php
                          }
                          ?>
                        </form>

                      </div>

                      <?php
                      // $suppliercode = $_SESSION['sup_code'];
                      $supplierCode = isset($_GET['suppliercode']) ? $_GET['suppliercode'] : null;

                      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        if (isset($_POST['updateTaxBtn'])) {
                          $statustax = $_POST['statustax'];
                          $updatedate = date('Y-m-d');

                          $query = "UPDATE mms_tax_details SET msd_status = '$statustax',updated_by = '" . $_SESSION['User'] . "',updated_date = '$updatedate'
                                        WHERE msd_supplier_code = '$supplierCode'";
                          // var_dump($query);
                          $stmtq = $query_run = mysqli_query($con, $query);

                          if ($stmtq) {
                            // echo '<div class="alert alert-success">Successfully Updated your details!</div';
                            echo '<script language="javascript">';
                            echo 'alert("Data successfully added!"); location.href="allactivesuppliersview.php"';
                            echo '</script>';
                          } else {
                            echo '<div class="alert alert-danger">Data not inserted!</div>';
                          }
                        }
                      }
                      ?>

                      <!-- Tax details -->
                      <?php
                      //$suppliercode = $_SESSION['sup_code'];
                      $tsql = "SELECT * FROM mms_tax_details WHERE msd_supplier_code  = '$supplierCode'";
                      $stmt = mysqli_query($con, $tsql);
                      while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
                      ?>
                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                          <br>
                          <form method="POST" id="taxdetails" name="taxdetails">
                            <div class="form-row">
                              <div class="form-group col-md-2">
                                <label for="vat">VAT</label>
                                <input type="text" class="form-control" name="VAT" id="VAT" placeholder="VAT" disabled value=" <?php echo $row['msd_vat']; ?>">
                              </div>
                              <div class="form-group col-md-2">
                                <label for="savt">SVAT</label>
                                <input type="text" class="form-control" name="SVAT" id="SVAT" placeholder="SVAT" disabled value=" <?php echo $row['msd_svat']; ?>">
                              </div>
                            </div>
                            <hr>
                            <!-- <input type="submit" class="btn btn-info" name="updateTaxBtn" id="updateTaxBtn" value="Update Details" onclick=""/> -->
                          </form>
                        </div>

                        <script>
                          function Updatetaxdetails() {
                            alert("Data Saved Successfully!!");
                          }
                        </script>

                      <?php
                      }
                      ?>

                      <!-- categories -->
                      <div class="tab-pane fade" id="matdetails" role="tabpanel" aria-labelledby="matdetails-tab">
                        <br>
                        <table class="table table-borderless">
                          <thead>
                            <tr>
                              <th>CATEGORIES</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            include 'config.php';
                            $tsql = "SELECT DISTINCT MMC_CAT_CODE,(SELECT mtc_description FROM mms_tendermaterial_categories WHERE mtc_cat_code = MMC_CAT_CODE) AS CATDESC FROM mms_tenderprice_transactions LEFT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code WHERE mms_tenderprice_transactions.mtt_supplier_code = '$supplierCode'";
                            $stmt = mysqli_query($con, $tsql);
                            while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
                            ?>
                              <tr>
                                <td>
                                  <ul>
                                    <li>

                                      <?php
                                      echo $row['CATDESC'];
                                      ?>
                                    </li>
                                  </ul>
                                </td>

                              </tr>
                            <?php
                            }
                            ?>
                          </tbody>

                        </table>
                      </div>

                      <!-- Attachments -->
                      <div class="tab-pane fade" id="attachment" role="tabpanel" aria-labelledby="attachment-tab">
                        <br>
                        <br>
                        <div class="form-row">
                          <table class="table table-hover">
                            <thead>
                              <tr>
                                <th>Attachment Types</th>
                                <th>Status</th>
                                <th>Download</th>
                                <th>Deactivate</th>

                              </tr>
                            </thead>
                            <tbody>
                              <?php

                              if (isset($supplierDetails['attachments'])) {
                                foreach ($supplierDetails['attachments'] as $attachment) {
                              ?>
                                  <form method="POST" id="attachment['<?= $attachment['msd_serial_no'] ?>']" name="attachment">
                                    <input name="msd_serial_no" value="<?= $attachment['msd_serial_no'] ?>" hidden />
                                    <tr>
                                      <td>
                                        <?= $attachment['msd_file_name']; ?>
                                      </td>
                                      <td>
                                        <?= $attachment['msd_status']; ?>
                                      </td>
                                      <td>
                                        <a href="./<?= $attachment['msd_file_path'] ?>" download='<?php $attachment['msd_file_name'] ?>' class="btn"><i class="fa fa-download"></i> Download</a>
                                      </td>
                                      <td>
                                        <button type="submit" value="delete" class="btn" style="background-color:red" id="delete" onclick="popupattachments()" name="delete" <?php if ($ButtonsDisabled) echo 'disabled'; ?>>Deactivate</button>
                                      </td>
                                    </tr>
                                  </form>
                              <?php
                                }
                              }
                              ?>
                            </tbody>
                          </table>
                        </div>
                        <hr>
                        <!-- <input type="submit" class="btn btn-info" name="updateSupBtn" id="updateSupBtn" value="Update Details" /> -->
                      </div>

                    </div>
                  </div>
                  <script>
                    function docdeact() {
                      alert("Document has been Deactivated!");
                      window.location.reload();
                    }
                  </script>
                  <!-- download -->


                  <div class="modal-footer">
                    <input type="hidden" name="insert" value="vegitables" />
                    <button type="submit" id="authBtn" class="btn bg-success" data-bs-dismiss="modal" <?= $supplierDetails['msd_status'] === "A" && $supplierDetails['MSB_BANK_STATEMENT'] === "Approved" ? "" : "disabled" ?>>Authorize</button>
                    <button type="submit" class="btn btn-success" data-bs-dismiss="modal" onclick="closeModal()">Close</button>
                    <!-- <button type="submit" class="btn btn-primary" name="insert" id="insert" value="vegitables">Save changes</button> -->
                    <!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Update</button> -->
                  </div>
                </div>
                </form>

<script>
  
  function popupMsgAuth() {
    alert("Supplier has Authorized!");
    closeModal();
  };

  function popupattachments() {
    alert("Attachments has Deactivated");
    closeModal();
  };

  function logoutfunction() {
    alert("Please Confirm To Logout!!");
  };

  function myFunctionVeg() {
    alert("Data Saved Successfully!!!");
  }
</script>

<script>
  
  $('#approveBankDetails').on('click', function() {

  })

  $('#authBtn').on('click', function() {
    $.get("/ajaxservice.php?func=confirmsupplier&suppliercode=<?= $supplierCode ?>&supmobile=<?= $suppliermobile ?>", function(data, status) {
      popupMsgAuth();
    });
  })

function closeModal() {
    window.location.replace(window.location.pathname);
  }

  function selectsupplier(msd_supplier_code) {
    console.log("msd_supplier_code: ", msd_supplier_code);

    // var url = "/ajaxservice.php?func=selectsupplier&suppliercode=" + msd_supplier_code;
    // $.get(url, function(data, status) {
    //   const dataSet = JSON.parse(data);
    //   console.log("Data: ", data, "dataSet", dataSet);
    //   if (dataSet?.data) {
    //     console.log(dataSet);
    //     $('#supcode').val(dataSet?.data.msd_supplier_code);
    //     $('#supname').val(dataSet?.data.msd_supplier_name);
    //     $('#supcat').val(dataSet?.data.msd_supply_category);
    //     $('#bsnature').val(dataSet?.data.msd_business_nature);
    //     //$('#inputAddress').val(dataSet?.data.msd_supply_category);
    //     //$('#country').val(dataSet?.data.msd_country_code);
    //     $('#address').val(dataSet?.data.msd_address);
    //     $('#officeaddress').val(dataSet?.data.msd_officeaddress);
    //     $('#operationaddress').val(dataSet?.data.msd_operationaddress);
    //     $('#telnumber').val(dataSet?.data.msd_teleno);
    //     $('#fax').val(dataSet?.data.msd_faxno);
    //     $('#emailad').val(dataSet?.data.msd_email_address);
    //     $('#web').val(dataSet?.data.msd_website);
    //     $('#contactperson').val(dataSet?.data.msd_contact_person);
    //     $('#agent').val(dataSet?.data.msd_agent);
    //     dataSet?.data.msd_status === "A" ? $('#authBtn').show() : $('#authBtn').hide();
    //   } else {
    //     alert(dataSet?.message);
    //   }
    // });
  }

  // window.addEventListener('click', function(e) {
  //   if (!document.getElementById('updateSupplierModal').contains(e.target)) {
  //     closeModal()
  //   }
  // });

  function approvedbakdetails() {
    if (confirm("If you approved the bank details & Now you can authorize the supplier!")) {
      $.get("/ajaxservice.php?func=bankstatusapprove&suppliercode=<?= $supplierCode ?>", function(data, status) {
        window.location.reload();
      });
    }
  }
</script>

</body>

</html>
