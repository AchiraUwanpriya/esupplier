<?php
session_start();
if (!isset($_SESSION['sup_code'])) {
  header('Location: index.php');
  exit;
}
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helper.php';

// Include backend files
require_once(__DIR__ . '/../../backend/supplier/profile-queries.php');
require_once(__DIR__ . '/../../backend/supplier/profile-handler.php');

$suppliercode = $_SESSION['sup_code'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link rel="shortcut icon" href="./static/img/9.png" />

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css" crossorigin="anonymous" />

  <title>eSupplier-CDPLC</title>

  <link href="./static/css/app.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

</head>

<body>
  <div class="wrapper">
    <!-- sidenav -->
    <?php include __DIR__ . '/../components/sidenav.php'; ?>
    <!-- navbar -->
    <div class="main">
      <?php include __DIR__ . '/../components/navbar.php'; ?>

      <main class="content">
        <div class="container-fluid p-0">
          <div class="row">
            <div class="col-md-4 col-xl-3">
              <div class="card mb-3">
                <div class="card-header">
                  <h5 class="card-title mb-0">Profile Details</h5>
                </div>
                <div class="card-body text-center">
                  <img src="./static/img/avatars/avatar1.jpg" alt="" class="img-fluid rounded-circle mb-2" width="128" height="128" />
                  <h2 class="mb-0"><?php echo $_SESSION['sup_name'] ?></h2>
                </div>
                <hr class="my-0" />
                <div class="card-body">
                  <h5 class="h6 card-title">About</h5>

                  <?php
                  $stmt = getSupplierDetails($con, $suppliercode);
                  
                  if ($stmt === false) {
                    echo "Error loading profile";
                  } else {
                    while ($row = $stmt->fetch_assoc()) {
                      $mobile = $row['msd_mobileno'];
                      $address = $row['msd_address'];
                      $postalCode = $row['msd_postalcode'];
                      $officeaddress = $row['msd_officeaddress'];
                      $operationaddress = $row['msd_operationaddress'];
                      $supcategory = $row['msd_supply_category'];
                      $emailAd = $row['msd_email_address'];
                      $bnature = $row['msd_business_nature'];
                      $telno = $row['msd_teleno'];
                      $faxnumber = $row['msd_faxno'];
                      $webs = $row['msd_website'];
                      $contperson = $row['msd_contact_person'];
                      $agent = $row['msd_agent'];
                  ?>

                    <ul class="list-unstyled mb-0">
                      <li class="mb-1"><span data-feather="home" class="feather-sm me-1"></span> Address -
                        <a href="#">
                          <?php echo htmlspecialchars($row['msd_address']); ?>
                        </a>
                      </li>
                      <li class="mb-1"><span data-feather="home" class="feather-sm me-1"></span> Mobile Number -
                        <a href="#">
                          <?php echo htmlspecialchars($row['msd_mobileno']); ?>
                        </a>
                      </li>
                      <li class="mb-1"><span data-feather="home" class="feather-sm me-1"></span> Category -
                        <a href="#">
                          <?php echo htmlspecialchars($row['msd_supply_category']); ?>
                        </a>
                      </li>
                      <li class="mb-1"><span data-feather="home" class="feather-sm me-1"></span> Email -
                        <a href="#">
                          <?php echo htmlspecialchars($row['msd_email_address']); ?>
                        </a>
                      </li>
                    </ul>
                  <?php
                    }
                  }
                  ?>
                </div>
                <?php
                ?>
              </div>
            </div>

            <!-- 1st -->
            <div class="col-md-8 col-xl-9">
              <div class="card">
                <div class="card-header">

                  <h5 class="card-title mb-0">Update the details</h5>
                </div>
                <div class="card-body h-100">
                  <form method="POST" id="profUpdate" name="profUpdate">
                    <div class="form-row">
                      <div class="form-group col-md-10">
                        <label for="inputAddress2">Supplier Name</label>
                        <input type="text" class="form-control" name="supname" id="supname" placeholder="Type your name" value="<?php echo $_SESSION['sup_name'] ?>" required disabled>
                      </div>
                      <div class="form-group col-md-2">
                        <!-- <label for="inputAddress2">Supplier Code</label> -->
                        <input type="number" class="form-control" name="supcode" value="" id="supcode" placeholder="<?php echo $_SESSION['sup_code'] ?>" hidden>
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="form-group col-md-10">
                        <label for="inputAddress2">Supplier Category</label>
                        <input type="text" class="form-control" name="supcat" id="supcat" placeholder="Fish, Vegetables, Spices, Rice / Oil and Coconut, Dry Fish" value="<?php echo $supcategory ?>" disabled>
                      </div>
                      <div class="form-group col-md-2">
                        <label for="bsnature">Business Nature</label>
                        <!-- <input type="text" class="form-control" name="country" id="country" placeholder="country"> -->
                        <select id="bsnature" name="bsnature" class="form-control">
                          <option selected hidden><?= $bnature ?></option>
                          <option>Traders</option>
                          <option>Agent</option>
                          <option>Other</option>
                          <option>Manufacture/Traders</option>
                          <option>Agent/Traders</option>
                        </select>
                      </div>
                    </div>

                    <!-- <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="inputEmail4">Business Nature</label>
                        <input type="text" class="form-control" id="bsnature" name="bsnature" placeholder="Business Nature">
                      </div>
                      <div class="form-group col-md-6">
                        <label for="inputPassword4">Manufacture</label>
                        <input type="password" class="form-control" id="manufacture" name="manufacture" placeholder="Password">
                      </div>
                    </div> -->
                    <div class="form-row">
                      <div class="form-group col-md-12">
                        <label for="address">(PO) Address</label>
                        <input type="text" class="form-control" name="address" id="address" value="<?php echo $address ?>">
                      </div>

                      <!-- <div class="form-group col-md-8">
                        <label for="inputAddress">Address</label>
                        <input type="text" class="form-control" name="inputAddress" id="inputAddress"
                          placeholder="Address" value = "No 221, TB Jaya Mawatha, Colombo 14" hidden>
                      </div> -->
                      <!-- <div class="form-group col-md-2">
                        <label for="countrycode">Country code</label>
                        <input type="text" class="form-control" name="countrycode" id="countrycode" placeholder=""
                          disabled>
                      </div> -->
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="officeaddress">Office Address</label>
                        <!-- <input type="text" class="form-control" name="country" id="country" placeholder="country"> -->
                        <input type="text" class="form-control" name="officeaddress" id="officeaddress" placeholder="Office Address" value="<?php echo $officeaddress ?>">
                      </div>
                      <div class="form-group col-md-6">
                        <label for="operationaddress">Operational Address</label>
                        <input type="text" class="form-control" name="operationaddress" id="operationaddress" placeholder="Operation Address" value="<?php echo $operationaddress ?>">
                      </div>
                      <div class="form-group col-md-6">
                        <label for="postalCode">Postal Code</label>
                        <input type="number" class="form-control" name="postalCode" id="postalCode" placeholder="postal Code" value="<?php echo $postalCode ?>">
                      </div>

                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-2">
                        <label for="">Country</label>
                        <!-- <input type="text" class="form-control" name="country" id="country" placeholder="country"> -->
                        <select id="country" name="country" class="form-control">
                          <option selected>Sri lanka</option>
                        </select>
                      </div>
                      <!-- <div class="form-group col-md-4">
                        <label for="telnumber">Telephone Number</label>
                        <input type="number" class="form-control" name="telnumber" id="telnumber" placeholder="Telephone Number(Other)" value="<?php echo $telno ?>" maxlength="10">
                      </div> -->
                      <div class="form-group col-md-4">
                        <label for="telnumber">Telephone Number(Other)</label>
                        <input type="tel" class="form-control" name="telnumber" id="telnumber" placeholder="0112345678" onkeypress="return onlyNumberKey(event)" maxlength='10' value="<?php echo $telno ?> ">
                      </div>

                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-3">
                        <label for="fax">Fax Number</label>
                        <input type="number" class="form-control" name="fax" id="fax" value="<?php echo $faxnumber ?>">
                      </div>
                      <!-- <div class="form-group col-md-4">
                        <label for="inputState">Supplier Type</label>
                        <select id="suptype" name="suptype" class="form-control">
                          <option selected>Choose...</option>
                          <option>1</option>
                        </select>
                      </div> -->
                      <div class="form-group col-md-4">
                        <label for="emailad">Sales email address</label>
                        <input type="email" name="emailad" class="form-control" id="emailad" value="<?php echo $emailAd ?>">
                      </div>
                      <div class="form-group col-md-5">
                        <label for="web">Web site</label>
                        <input type="text" name="web" class="form-control" id="web" value="<?php echo $webs ?>">
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="form-group col-md-5">
                        <label for="contactperson">Contact Person</label>
                        <input type="text" name="contactperson" class="form-control" id="contactperson" value="<?php echo $contperson ?>">
                      </div>
                      <div class="form-group col-md-6">
                        <label for="agent">Agent </label>
                        <select id="agent" name="agent" class="form-control">
                          <option selected hidden><?= $agent ?></option>
                          <option>Yes</option>
                          <option>No</option>
                        </select>
                      </div>
                      <!-- <div class="form-group col-md-1">
                        <label for="agentcode">Agent code</label>
                        <input type="text" class="form-control" name="agentcode" id="agentcode" placeholder="" >
                      </div> -->

                      <input type="submit" class="btn btn-success" name="updateSupBtn" id="updateSupBtn" value="Update Details" onclick="supReg();" />
                    </div>
                  </form>
                  <br>
                  <hr>

                  <div class="form-row">
                    <div class="form-group">
                      <div class="form-group col">
                        <h5 style="color:blue">Upload Business Registration Document!</h5>
                        <?php if (isset($_GET['error1'])) : ?>
                          <p style="color:red"><?= $_GET['error1']; ?></p>
                        <?php endif ?>
                        <form action="./attachments/upload_doc_1.php" method="post" enctype="multipart/form-data">
                          <input type="file" class="mb-3 btn btn-" name="my_image1">
                          <input type="submit" class="btn btn-info" name="submit" value="Upload file">
                        </form>
                      </div>
                      <br>

                      <div class="form-group col">
                        <h5 style="color:blue">Upload Company Certificate Document!</h5>
                        <?php if (isset($_GET['error2'])) : ?>
                          <p style="color:red"><?php echo $_GET['error2']; ?></p>
                        <?php endif ?>
                        <form action="./attachments/upload_doc_2.php" method="post" enctype="multipart/form-data">
                          <input type="file" class="mb-3 btn btn-uu" name="my_image2">
                          <input type="submit" class="btn btn-info" name="submit" value="Upload file">
                        </form>
                      </div>
                      <br>
                      <div class="form-group col">
                        <h5 style="color:blue">Upload Form 20!</h5>
                        <?php if (isset($_GET['error3'])) : ?>
                          <p style="color:red"><?php echo $_GET['error3']; ?></p>
                        <?php endif ?>
                        <form action="./attachments/upload_doc_3.php" method="post" enctype="multipart/form-data">
                          <input type="file" class="mb-3 btn btn-uu" name="my_image3">
                          <input type="submit" class="btn btn-info" name="submit" value="Upload file">
                        </form>
                      </div>
                    </div>
                  </div>
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
                      <button class="nav-link" id="matdetails-tab font-weight-bold" name="matdetails-tab" data-bs-toggle="tab" data-bs-target="#matdetails" type="button" role="tab" aria-controls="matdetails" aria-selected="false">Material Details</button>
                    </li>
                    <!-- up03 -->
                    <li class="nav-item" role="presentation">
                      <button class="nav-link" id="attachment-tab font-weight-bold" data-bs-toggle="tab" data-bs-target="#attachment" type="button" role="tab" aria-controls="attachment" aria-selected="false">Attachments</button>
                    </li>
                  </ul>
                  <div class="tab-content" id="myTabContent">
                    <!-- bank details -->
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                      <br>
                      <br />
                      <h4>Please Upload Your Bank Statement! </h4>
                      <br>
                      <?php if (isset($_GET['error4'])) : ?>
                        <p style="color:red"><?php echo $_GET['error4']; ?></p>
                      <?php endif ?>
                      <form action="./attachments/upload_bank_details.php" method="post" enctype="multipart/form-data">
                        <!-- allachments/upload_bank_details.php -->

                        <input type="file" class="mb-3 btn btn-success" name="my_imagebank">
                        <br>
                        <input type="submit" class="btn btn-info" name="submit" value="Upload file">
                      </form>
                      <br />

                      <!-- bank details form -->

                      <?php
                      $dataList = getSupplierBankDetails($con, $suppliercode);
                      $data = [];
                      if (count($dataList) > 0) {
                        $data = $dataList[0];
                      }
                      $banks = getMainBanks($con);
                      ?>

                      <form method="POST" id="bankdetails" name="bankdetails">
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="mainbank">Main Bank</label>
                            <input type="text" hidden name="supbankid" value="<?= getvalue($data, 'MSB_SUPPLIER_BANK_ID') ?>">
                            <select name="mainbank" id="mainbank" class="form-control" required onChange="selectBank(this)">
                              <option value="<?= getvalue($data, 'MSB_MAIN_BANK_CODE') ?>" hidden><?= getvalue($data, 'MBD_BANK_NAME', 'Select Bank') ?></option>
                              <?php
                              for ($i = 0; $i < count($banks); $i++) {
                                $bank = $banks[$i];
                              ?>
                                <option value="<?= htmlspecialchars($bank['MBD_CHILD_KEY']) ?>"><?= htmlspecialchars($bank['MBD_BANK_NAME']) ?> </option>
                              <?php
                                $banks[$i]['Branches'] = getBankBranches($con, $bank['MBD_CHILD_KEY']);
                              }
                              ?>
                            </select>
                          </div>

                          <div class="form-group col-md-4">
                            <label for="inputAddress2">Bank Code</label>
                            <input type="text" class="form-control" name="bankcode" id="bankcode" value="<?= getvalue($data, 'MSB_BANK_CODE') ?>" placeholder="Bank Code">
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="branch">Branch</label>

                            <select name="branch" id="branch" class="form-control">
                              <option class="branch_default" value="<?= getvalue($data, 'MSB_CHILD_KEY') ?>" hidden><?= getvalue($data, 'BRANCH_NAME', 'Select Branch') ?></option>
                              <?php
                              foreach ($banks as $bank) {
                                foreach ($bank['Branches'] as $branch) {
                              ?>
                                  <option hidden class="branches branch_<?= htmlspecialchars($bank['MBD_CHILD_KEY']) ?>" value="<?= htmlspecialchars($branch['MBD_CHILD_KEY']) ?>"><?= htmlspecialchars($branch['MBD_BANK_NAME']) ?></option>
                              <?php
                                }
                              }
                              ?>
                            </select>
                          </div>

                          <div class="form-group col-md-4">
                            <label for="accnumber">Account Number</label>
                            <input type="number" class="form-control" name="accnumber" id="accnumber" value="<?= getvalue($data, 'MSB_ACCOUNT_NO') ?>" placeholder="Account Number" required>
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="acctype">Account Type</label>
                            <select id="acctype" name="acctype" class="form-control" required>
                              <option selected value="<?= getvalue($data, 'MSB_ACCOUNT_TYPE') ?>" hidden><?= getvalue($data, 'MSB_ACCOUNT_TYPE', "Select Account Type") ?></option>
                              <!-- <option selected hidden ><?= getvalue($row, 'msd_status') ?></option> -->
                              <option value="Saving Account">Saving Account</option>
                              <option value="Current Account">Current Account</option>
                              <option value="Join Account">Join Account</option>
                            </select>
                          </div>
                          <!-- <div class="form-group col-md-2">
                            <label for="sortcode">Sort Code</label>
                            <input type="text" class="form-control" name="sortcode" id="sortcode">
                          </div> -->
                        </div>
                        <!-- <div class="form-row">
                          <div class="form-group col-md-4">
                            <label for="status">Status</label>
                            <input type="text" class="form-control" name="status" id="status">
                          </div>
                          <div class="form-group col-md-4">
                            <label for="benblzcode">Benifitciary's BLZ Code</label>
                            <input type="text" class="form-control" name="benblzcode" id="benblzcode">
                          </div>
                          <div class="form-group col-md-4">
                            <label for="benNumber">Benifitciary's Number</label>
                            <input type="text" class="form-control" name="benNumber" id="benNumber">
                          </div>
                        </div> -->
                        <input type="submit" class="btn btn-info" name="updateBankBtn" id="updateBankBtn" onclick="" value="Update Details" />
                      </form>
                    </div>
                  <!-- Discrepancies -->
                      <br>
                      <form method="POST" id="discrepancies" name="discrepancies">
                        <div class="form-row">
                          <div class="form-group col-md-2">
                            <label for="ponumber">PO-Number</label>
                            <input type="number" class="form-control" name="ponumber" id="ponumber" placeholder="">
                          </div>
                          <div class="form-group col-md-10">
                            <label for="disrepancyType">Disrepancy Type</label>
                            <input type="text" class="form-control" name="disrepancyType" id="disrepancyType"
                              placeholder="Type your Disrepancy Type">
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" name="date" id="date"
                              placeholder="Enter your date">
                          </div>
                          <div class="form-group col-md-6">
                            <label for="inputAddress2">Account Number</label>
                            <input type="text" class="form-control" name="accnumber" id="accnumber"
                              placeholder="Type your Account Number">
                          </div>
                        </div>
              
                        <hr>

                        <input type="submit" class="btn btn-info" name="updateSupBtn" id="updateSupBtn"
                          value="Update Details" />
                      </form>
                    </div>  -->

                  <!-- Tax details -->
                  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                    <br>
                    <!-- up03 -->
                    <?php
                    $taxdetails = getTaxDetails($con, $suppliercode);
                    ?>

                    <!-- upload VAT document -->
                    <br />
                    <div class="row">
                      <div class="form-group col-md-4">
                        <h4>Please Upload Your VAT Statement! </h4>
                        <br>
                        <?php if (isset($_GET['error5'])) : ?>
                          <p style="color:red"><?php echo htmlspecialchars($_GET['error5']); ?></p>
                        <?php endif ?>
                        <form action="./attachments/upload_vat_details.php" method="post" enctype="multipart/form-data">

                          <input type="file" class="mb-3 btn btn-success" name="my_imagevat">
                          <br>
                          <input type="submit" class="btn btn-info" name="submit" value="Upload file">

                        </form>
                      </div>
                      <!-- upload SVAT document -->
                      <div class="form-group col-md-4">
                        <h4>Please Upload Your SVAT Statement! </h4>
                        <br>
                        <?php if (isset($_GET['error6'])) : ?>
                          <p style="color:red"><?php echo htmlspecialchars($_GET['error6']); ?></p>
                        <?php endif ?>
                        <form action="./attachments/upload_svat_details.php" method="post" enctype="multipart/form-data">

                          <input type="file" class="mb-3 btn btn-success" name="my_imagesvat">
                          <br>
                          <input type="submit" class="btn btn-info" name="submit" value="Upload file">

                        </form>
                      </div>

                    </div>
                    <br />
                    <form method="POST" id="taxdetails" name="taxdetails">
                      <input type="text" id="msd_id" name="msdid" value="<?= getvalue($taxdetails, 'msd_id') ?>" hidden>
                      <div class="form-row">
                        <!-- <div class="form-group col-md-2">
                          <label for="taxtype">Tax Type</label>
                          <input type="text" id="taxtype" name="taxtype" value="<?= getvalue($taxdetails, 'msd_tax_type') ?>" class="form-control" placeholder="Tax Type" required>
                          
                        </div> -->
                        <!-- <div class="form-group col-md-2">
                          <label for="year">Year</label> -->
                        <!-- <input type="text" class="form-control" name="country" id="country" placeholder="country"> -->
                        <!-- <input type="number" id="year" name="year" value="<?= getvalue($taxdetails, 'msd_year') ?>" class="form-control" placeholder="Year" required> -->

                        <!-- </div> -->
                        <!-- <div class="form-group col-md-2">
                          <label for="percentage">Percentage (%)</label>
                          <input type="number" class="form-control" name="percentage" value="<?= getvalue($taxdetails, 'msd_percentage') ?>" id="percentage" placeholder="Percentage" required >
                        </div> -->
                        <div class="form-group col-md-2">
                          <label for="percentage">VAT</label>
                          <input type="number" class="form-control" name="VAT" value="<?= getvalue($taxdetails, 'msd_vat') ?>" id="VAT" placeholder="VAT" required>
                        </div>
                        <div class="form-group col-md-2">
                          <label for="percentage">SVAT</label>
                          <input type="number" class="form-control" name="SVAT" value="<?= getvalue($taxdetails, 'msd_svat') ?>" id="SVAT" placeholder="SVAT" required>
                        </div>
                      </div>
                      <div class="form-row">
                        <!-- <div class="form-group col-md-4">
                          <label for="startdate">startdate</label>
                          <input type="date" class="form-control" name="startdate" value="<?= getvalue($taxdetails, 'msd_sdate') ?>" id="startdate" placeholder="Select start date" required>
                        </div> -->
                        <!-- <div class="form-group col-md-4">
                          <label for="enddate">End Date</label>
                          <input type="date" class="form-control" name="enddate" value="<?= getvalue($taxdetails, 'msd_edate') ?>" id="enddate" placeholder="Select end date" required>
                        </div> -->
                        <!-- <div class="form-group col-md-4">
                          <label for="statustax">Status</label>
                          <input type="text" class="form-control" name="statustax" id="statustax" placeholder="Status" required>
                        </div> -->
                        <!-- <div class="form-group col-md-4">
                          <label for="statustax">Status </label>
                          <select id="statustax" name="statustax" class="form-control" disabled required>
                            <option selected hidden><?= getvalue($taxdetails, 'msd_status', "Select Status") ?></option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                          </select>
                        </div> -->
                      </div>
                      <hr>
                      <input type="submit" class="btn btn-info" name="updateTaxBtn" id="updateTaxBtn" onclick="" value="Update Details" />
                    </form>
                  </div>

                  <!-- Material details -->
                  <div class="tab-pane fade" id="matdetails" name="matdetails" role="tabpanel" aria-labelledby="matdetails-tab">
                    <br>
                    <table class="table table-borderless">
                      <thead>
                        <tr>
                          <th>CATEGORIES</th>
                        </tr>
                      </thead>
                      <?php
                      $categories = getSupplierMaterialCategories($con, $suppliercode);
                      foreach ($categories as $row) {
                      ?>
                        <tbody>
                          <tr>
                            <td>
                              <ul>
                                <li><?php echo htmlspecialchars($row['CATDESC'] ?? ''); ?></li>
                              </ul>
                            </td>
                          </tr>
                        </tbody>
                      <?php
                      }
                      ?>
                    </table>
                  </div>

                  <!-- up03 -->
                  <div class="tab-pane fade" id="attachment" role="tabpanel" aria-labelledby="attachment-tab">
                    <br>
                    <br>
                    <div class="form-row">
                      <table class="table table-hover">
                        <thead>
                          <tr>
                            <th>Uploaded Attachment</th>
                            <th>Attachment Status</th>
                            <th>Download</th>

                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $attachments = getSupplierAttachments($con, $suppliercode);
                          foreach ($attachments as $row) {
                            $msd_file_path = $row['msd_file_path'];
                            $msd_file_name = $row['msd_file_name'];
                            $msd_serial_no = $row['msd_serial_no'];
                          ?>
                            <form method="POST" id="attachment['<?= $msd_serial_no ?>']" name="attachment">
                              <input name="msd_serial_no" value="<?= htmlspecialchars($msd_serial_no) ?>" hidden />
                              <tr>
                                <td>
                                  <?php echo htmlspecialchars($row['msd_file_name']); ?>
                                </td>
                                <td>
                                  <?php echo htmlspecialchars($row['msd_status']); ?>
                                </td>
                                <td>
                                  <a href="./<?php echo htmlspecialchars($msd_file_path) ?>" download='<?php echo htmlspecialchars($msd_file_name) ?>' class="btn btn-primary"><i class="fa fa-download"></i> Download</a>
                                </td>
                              </tr>
                            </form>
                          <?php
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
              </div>
            </div>

            <!-- 2nd -->
            <!-- <div class="container-fluid p-0">
              <div class="row">
                <div class="col-md-4 col-xl-3 ">
  
                </div>
  
                  <div class="col-md-8 col-xl-9 " style="float: right;" >
                    <div class="card">
                      <div class="card-header">
  
                        <h5 class="card-title mb-0">Update the details</h5>
                      </div>
                      <div class="card-body h-100">
                        
  
                      </div>
                    </div>
                  </div>
              </div>
            </div>     -->
          </div>
        </div>
      </main>



      <footer class="footer">
        <div class="container-fluid">
          <div class="row text-muted">
            <div class="col-6 text-start">
              <p class="mb-0">
                <a class="text-muted" href="" target="_blank">
                  <strong>
                    &copy;<a id="yr"></a> <a href="https://www.dockyardsolutions.lk"> Dockyard Total Solutions (Pvt) Ltd </a>
                  </strong>
                  <script>
                    document.getElementById("yr").innerHTML = new Date().getFullYear();
                  </script>
                </a>
              </p>
              <!-- <a id="heading" class="text-center" style="color:blue"><strong>Colombo Dockyard PLC </strong></a>
              <a class="text-center ">&nbsp; Po. Box: 906, Port of Colombo, Colombo 15.</a> -->
            </div>
            <div class="col-6 text-end">
              <ul class="list-inline">
                <li class="list-inline-item">
                  <!-- <a class="text-muted" href="" target="_blank">Support</a> -->
                  <a id="" class="text-center" style="color:blue"><strong>Colombo Dockyard PLC </strong></a><br>
                  <a class="text-center ">&nbsp; P. O. Box: 906, Port of Colombo, Colombo 15.</a>
                </li>
                <!-- <li class="list-inline-item">
                  <a class="text-muted" href="" target="_blank">Support</a>
                </li>
                <li class="list-inline-item">
                  <a class="text-muted" href="" target="_blank">Help Center</a>
                </li>
                <li class="list-inline-item">
                  <a class="text-muted" href="" target="_blank">Privacy</a>
                </li>
                <li class="list-inline-item">
                  <a class="text-muted" href="" target="_blank">Terms</a>
                </li> -->
              </ul>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>

  <script>
    function supReg() {
      alert("Profile Successfully Updated!");
      window.location.refresh();
    }

    function taxDetailsFunc() {
      alert("Data successfully added!");
      window.location.refresh();
    }

    function bankDetailsFunc() {
      alert("Data successfully added!");
      window.location.refresh();
    }

    function selectBank({
      value
    }) {
      $(".branches").each((i, val) => {
        val.hidden = true
      });
      $(`.branch_${value}`).each((i, val) => {
        val.hidden = false
      });
      $(`.branch_default`).each((i, val) => {
        val.value = ""
        val.text = "Select Branch"
      });
    }
  </script>

  <script>
    function onlyNumberKey(evt) {
      // Only ASCII character in that range allowed
      var ASCIICode = (evt.which) ? evt.which : evt.keyCode
      if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
        return false;
      return true;
    }
  </script>

  <script src="static/js/app.js"></script>

  <!-- timer script sessionUnset -->
  <script src="js/sessionUnset.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="js/translate.js"></script>
</body>

</html>