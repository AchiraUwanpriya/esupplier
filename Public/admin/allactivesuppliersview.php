<?php
include_once '../../backend/allactivesuppliers_controller.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link rel="shortcut icon" href="../static/img/2.svg" />

  <title>eSupplier-CDL</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css" crossorigin="anonymous" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <link href="../static/css/app.css" rel="stylesheet">
  <link href="../static/css/main.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


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

  <script src="../static/js/jquery-3.3.1.min.js"></script>
  <script src="../static/js/jquery.validate.min.js"></script>
  <script src="../static/js/jquery.validate.unobtrusive.min.js"></script>

  <script src="../static/js/app.js"></script>

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

  <script>
    function validateBankForm(btn) {
      const form = btn.closest('form');
      const acc = form.querySelector('[name="accnumber"]').value.trim();
      const bank = form.querySelector('[name="mainbank"]').value.trim();
      const br = form.querySelector('[name="branch"]').value.trim();
      const code = form.querySelector('[name="bankcode"]').value.trim();
      const type = form.querySelector('[name="acctype"]').value.trim();
      
      if (!acc || !bank || !br || !code || !type) {
        Swal.fire({
          icon: 'warning',
          title: 'Incomplete Details',
          text: 'Please enter all fields for bank details!'
        });
        return false;
      }
      return true;
    }
  </script>




</head>

<body>
  <div class="wrapper">
  <div class="wrapper">
    <?php include 'components/adminsidenav.php'; ?>

    <div class="main">
      <?php include 'components/adminnavbar.php'; ?>
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
                        $tsql = get_active_suppliers_sql();
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
                                <input type="hidden" name="msd_mobileno[<?= $index ?>]" value="<?php echo $row['msd_mobileno']; ?>">

                            </td>
                            <td><?php echo $row['msd_supply_category']; ?>
                              <input type="text" hidden name="msd_supply_category[<?= $index ?>]" value="<?php echo $row['msd_supply_category']; ?>" hidden>
                            </td>
                            <td><?php echo $row['msd_address']; ?>
                              <input type="text" hidden name="msd_address[<?= $index ?>]" value="<?php echo $row['msd_address']; ?>" hidden>
                            </td>
                            <td><?php echo ($row['msd_status'] == 'A') ? 'Active' : (($row['msd_status'] == 'I') ? 'Inactive' : $row['msd_status']); ?>
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
      <?php include 'components/adminfooter.php' ?>


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
                      <input type="hidden" name="supcode_hidden" value="<?= $supplierDetails['msd_supplier_code'] ?>">
                      <input type="hidden" name="supmobile_hidden" value="<?= $suppliermobile ?>">
                      <div class="form-row">

                        <div class="form-group col-md-10">
                          <input type="text" class="form-control" placeholder="Type your name" value="<?= $supplierDetails['msd_supplier_code'] ?>" disabled>
                        </div>

                        <div class="form-group col-md-10">
                          <label for="inputAddress2">Supplier Name</label>
                          <input type="text" class="form-control" name="supname" id="supname" placeholder="Type your name" value="<?= htmlspecialchars($supplierDetails['msd_supplier_name']) ?>" readonly>
                        </div>
                        <div class="form-group col-md-2">
                          <input type="hidden" class="form-control" value="">

                        </div>
                      </div>
                      <div class="form-row">
                        <div class="form-group col-md-10">
                          <label for="inputAddress2">Supplier Category</label>
                          <select class="form-control" name="supcat" id="supcat" disabled>
                            <?php 
                            $categories = [
                              'RI' => 'Ration Items',
                              'PI' => 'PVC Items',
                              'B' => 'Cables',
                              'M' => 'Miscellaneous Items',
                              'I' => 'Medicine Items'
                            ];
                            foreach ($categories as $code => $name): ?>
                              <option value="<?= $code ?>" <?= $supplierDetails['msd_supply_category'] === $code ? 'selected' : '' ?>><?= $name ?></option>
                            <?php endforeach; ?>

                          </select>
                        </div>

                        <div class="form-group col-md-2">
                          <label for="bsnature">Business Nature</label>
                          <select id="bsnature" name="bsnature" class="form-control" disabled>
                            <?php foreach (['Manufacture','Trading','Service'] as $opt): ?>
                              <option value="<?= $opt ?>" <?= $supplierDetails['msd_business_nature'] === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="form-group col-md-12">
                          <label for="address">Address</label>
                          <input type="text" class="form-control" name="address" id="address" value="<?= htmlspecialchars($supplierDetails['msd_address']) ?>" readonly>
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label for="officeaddress">Office Address</label>
                          <input type="text" class="form-control" name="officeaddress" id="officeaddress" placeholder="Office Address" value="<?= htmlspecialchars($supplierDetails['msd_officeaddress']) ?>" readonly>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="operationaddress">Operation Address</label>
                          <input type="text" class="form-control" name="operationaddress" id="operationaddress" placeholder="Operation Address" value="<?= htmlspecialchars($supplierDetails['msd_operationaddress']) ?>" readonly>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="postalCode">Postal Code</label>
                          <input type="text" class="form-control" name="postalCode" id="postalCode" placeholder="postal Code" value="<?= htmlspecialchars($supplierDetails['msd_postalcode']) ?>" readonly>
                        </div>

                      </div>
                      <div class="form-row">
                        <div class="form-group col-md-2">
                          <label for="">Country</label>
                          <select id="country" name="country" class="form-control" disabled>
                            <option selected>Sri lanka</option>
                          </select>
                        </div>

                        <div class="form-group col-md-4">
                          <label for="telnumber">Telephone Number</label>
                          <input type="text" class="form-control" name="telnumber" id="telnumber" placeholder="Telephone Number(Other)" value="<?= htmlspecialchars($supplierDetails['msd_teleno']) ?>" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');" readonly>
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-3">
                          <label for="fax">Fax Number</label>
                          <input type="text" class="form-control" name="fax" id="fax" value="<?= htmlspecialchars($supplierDetails['msd_faxno']) ?>" readonly>
                        </div>
                        <div class="form-group col-md-4">
                          <label for="emailad">Sales email address</label>
                          <input type="email" name="emailad" class="form-control" id="emailad" value="<?= htmlspecialchars($supplierDetails['msd_email_address']) ?>" readonly>
                        </div>
                        <div class="form-group col-md-5">
                          <label for="web">Web site</label>
                          <input type="text" name="web" class="form-control" id="web" value="<?= htmlspecialchars($supplierDetails['msd_website']) ?>" readonly>
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="form-group col-md-5">
                          <label for="contactperson">Contact Person</label>
                          <input type="text" name="contactperson" class="form-control" id="contactperson" value="<?= htmlspecialchars($supplierDetails['msd_contact_person']) ?>" readonly>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="agent">Agent </label>
                          <select id="agent" name="agent" class="form-control" disabled>
                            <?php foreach (['Yes','No'] as $opt): ?>
                              <option value="<?= $opt ?>" <?= $supplierDetails['msd_agent'] === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>

                      <!-- <input type="submit" class="btn btn-info" name="updateSupBtn" id="updateSupBtn" value="Update Details" <?php if ($ButtonsDisabled) echo 'disabled'; ?> /> -->
                      <hr>
                    </form>

                    <!-- Refrence number update -->
                    <form method="POST" action="">
                      <input type="hidden" name="refer_supplier_code" value="<?= $supplierDetails['msd_supplier_code'] ?>">
                      <input type="hidden" name="refer_supmobile" value="<?= $suppliermobile ?>">
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
                        $data = $bankData;
                        ?>
                        <br>
                        <form method="POST" id="bankdetails" name="bankdetails">
                          <input type="hidden" name="bank_supplier_code" value="<?= $supplierDetails['msd_supplier_code'] ?>">
                          <input type="hidden" name="bank_supmobile" value="<?= $suppliermobile ?>">
                          <br>
                          <div class="form-row">
                          <div class="form-group col-md-4">
                            <label for="mainbank">Main Bank</label>
                            <select class="form-control" name="mainbank" id="mainbank_<?= $supplierDetails['msd_supplier_code'] ?>" disabled>
                              <option value="<?= getvalue($data, 'MSB_MAIN_BANK_CODE') ?>" hidden><?= getvalue($data, 'MBD_BANK_NAME', 'Select Bank') ?></option>
                              <?php
                              $allBanks = selectquery(get_all_banks_sql());
                              foreach ($allBanks as $bank): ?>
                                <option value="<?= $bank['MBD_CHILD_KEY'] ?>" <?= getvalue($data, 'MSB_MAIN_BANK_CODE') == $bank['MBD_CHILD_KEY'] ? 'selected' : '' ?>>
                                  <?= $bank['MBD_BANK_NAME'] ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                          <div class="form-group col-md-4">
                            <label for="bankcode">Bank Code</label>
                            <input type="text" class="form-control" name="bankcode" value="<?= getvalue($data, 'MSB_BANK_CODE') ?>" readonly>
                          </div>
                          <div class="form-group col-md-4">
                            <label for="branch">Branch Name</label>
                            <input type="text" class="form-control" name="branch" list="branches_<?= $supplierDetails['msd_supplier_code'] ?>" value="<?= getvalue($data, 'BRANCH_NAME', getvalue($data, 'MSB_CHILD_KEY')) ?>" readonly>
                            <datalist id="branches_<?= $supplierDetails['msd_supplier_code'] ?>">
                              <?php
                              $allBranches = selectquery(get_all_branches_sql());
                              foreach ($allBranches as $branch): ?>
                                <option value="<?= $branch['MBD_CHILD_KEY'] ?>"><?= $branch['MBD_BANK_NAME'] ?></option>
                              <?php endforeach; ?>
                            </datalist>
                          </div>
                        </div>
                          <div class="form-row">
                            <div class="form-group col-md-4">
                              <label for="accnumber">Account Number</label>
                              <input type="text" class="form-control" name="accnumber" id="accnumber" value="<?= getvalue($data, 'MSB_ACCOUNT_NO') ?>" readonly>
                            </div>
                          </div>

                          <div class="form-row">
                            <div class="form-group col-md-6">
                              <label for="acctype">Account Type</label>
                              <input type="text" class="form-control" name="acctype" value="<?= getvalue($data, 'MSB_ACCOUNT_TYPE') ?>" readonly>
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
                          <!-- <input type="submit" class="btn btn-info" name="updateBankBtn" id="updateBankBtn" value="Update Bank Details" <?php if ($ButtonsDisabled) echo 'disabled'; ?> onclick="return validateBankForm(this)" /> -->

                        </form>

                        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'bank_empty'): ?>
                        <script>
                          Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No bank details were entered!'
                          });
                        </script>
                        <?php endif; ?>


                      </div>

                      <?php
                      // tax updates are handled by the backend controller
                      $supplierCode = isset($_GET['suppliercode']) ? $_GET['suppliercode'] : null;
                      ?>

                      <!-- Tax details -->
                      <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                        <br>
                        <?php
                        $taxList = selectquery(get_tax_details_sql(mysqli_real_escape_string($con, $supplierCode)));
                        if (empty($taxList)) {
                          echo '<div class="alert alert-info">No Tax Details Available</div>';
                        } else {
                          foreach ($taxList as $row) {
                          ?>
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
                            </form>
                          <?php
                          }
                        }
                        ?>
                      </div>


                        <script>
                          function Updatetaxdetails() {
                            alert("Data Saved Successfully!!");
                          }
                        </script>

                      <?php
                        // Removed redundant closing brace
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
                            $safe = mysqli_real_escape_string($con, $supplierCode);
                            $catList = selectquery(get_categories_sql($safe));
                            foreach ($catList as $row) {
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
                                        <?= ($attachment['msd_status'] == 'A') ? 'Active' : (($attachment['msd_status'] == 'I') ? 'Inactive' : $attachment['msd_status']); ?>
                                      </td>
                                      <td>
                                        <a href="../uploads/<?= str_replace(['../../uploads/', '../uploads/'], '', $attachment['msd_file_path']) ?>" download="<?= htmlspecialchars($attachment['msd_file_name']) ?>" class="btn"><i class="fa fa-download"></i> Download</a>
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
                    <?php
                    $authorizeUrl = "allactivesuppliersview.php?suppliercode=" . urlencode($supplierDetails['msd_supplier_code']) . "&supmobile=" . urlencode($suppliermobile) . "&action=authorize";
                    ?>
                    <?php if (!$ButtonsDisabled): ?>
                    <a href="<?= htmlspecialchars($authorizeUrl) ?>"
                       class="btn bg-success"
                       id="authBtn"
                       onclick="return confirm('Confirm Authorize this supplier?')">
                      Authorize
                    </a>
                    <?php else: ?>
                    <button type="button" class="btn bg-success" id="authBtn" disabled>Authorize</button>
                    <?php endif; ?>
                    <button type="button" class="btn btn-success" onclick="closeModal()">Close</button>
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


  function myFunctionVeg() {
    alert("Data Saved Successfully!!!");
  }
</script>

<script>
  $('#approveBankDetails').on('click', function() {

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