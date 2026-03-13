<?php
// Controller for allactivesuppliersview - handles session/checks and write handlers
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/common/config.php';
include_once __DIR__ . '/common/helper.php';

$updatedate = date('Y-m-d');

if (!isset($_SESSION['mobile_number']) || !isset($_SESSION['name']) || !isset($_SESSION['entry'])) {
  header('Location: ../admin.php');
  exit();
}

$entry = $_SESSION['entry'];
$ButtonsDisabled = ($entry == 'N');

include_once __DIR__ . '/queries/allactivesuppliers_queries.php';

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
    print_r(mysqli_error($con));
  }
}

function runquery($query)
{
  global $con;
  $query_run = mysqli_query($con, $query);
  if ($query_run) {
    echo "Data Inserted Successfully!";
  } else {
    print_r(mysqli_error($con));
  }
}

// WRITE HANDLERS
if (isset($_POST['delete'])) {
  $qry = "UPDATE mms_supplier_attachments SET msd_status='I',updated_by='" . $_SESSION['User'] . "',updated_date='$updatedate' WHERE msd_serial_no = '" . $_POST['msd_serial_no'] . "'";
  runquery($qry);
}

if (isset($_GET['action']) && $_GET['action'] === 'authorize' && isset($_GET['suppliercode'])) {
  $sc = mysqli_real_escape_string($con, $_GET['suppliercode']);
  mysqli_query($con, "UPDATE mms_suppliers_details SET msd_status = 'C' WHERE msd_supplier_code = '$sc'");
  header('Location: allactivesuppliersview.php');
  exit();
}

if (isset($_POST['updateSupBtn'])) {
  $sc               = mysqli_real_escape_string($con, $_POST['supcode_hidden']);
  $supmob           = mysqli_real_escape_string($con, $_POST['supmobile_hidden']);
  $supname          = mysqli_real_escape_string($con, $_POST['supname']);
  $supcat           = mysqli_real_escape_string($con, $_POST['supcat']);
  $bsnature         = mysqli_real_escape_string($con, $_POST['bsnature']);
  $address          = mysqli_real_escape_string($con, $_POST['address']);
  $officeaddress    = mysqli_real_escape_string($con, $_POST['officeaddress']);
  $operationaddress = mysqli_real_escape_string($con, $_POST['operationaddress']);
  $postalCode       = mysqli_real_escape_string($con, $_POST['postalCode']);
  $telnumber        = mysqli_real_escape_string($con, $_POST['telnumber']);
  $fax              = mysqli_real_escape_string($con, $_POST['fax']);
  $emailad          = mysqli_real_escape_string($con, $_POST['emailad']);
  $web              = mysqli_real_escape_string($con, $_POST['web']);
  $contactperson    = mysqli_real_escape_string($con, $_POST['contactperson']);
  $agent            = mysqli_real_escape_string($con, $_POST['agent']);
  mysqli_query($con, "UPDATE mms_suppliers_details SET
    msd_supplier_name='$supname', msd_supply_category='$supcat', msd_business_nature='$bsnature',
    msd_address='$address', msd_officeaddress='$officeaddress', msd_operationaddress='$operationaddress',
    msd_postalcode='$postalCode', msd_teleno='$telnumber', msd_faxno='$fax',
    msd_email_address='$emailad', msd_website='$web', msd_contact_person='$contactperson', msd_agent='$agent'
    WHERE msd_supplier_code='$sc'");
  header("Location: allactivesuppliersview.php?suppliercode=$sc&supmobile=$supmob&msg=supplier_updated");
  exit();
}

if (isset($_POST['updateBankBtn'])) {
  $sc      = mysqli_real_escape_string($con, $_POST['bank_supplier_code']);
  $supmob  = mysqli_real_escape_string($con, $_POST['bank_supmobile']);
  $accno   = mysqli_real_escape_string($con, $_POST['accnumber']);
  $acctype = mysqli_real_escape_string($con, $_POST['acctype']);
  $bankcode= mysqli_real_escape_string($con, $_POST['bankcode']);
  mysqli_query($con, "UPDATE mms_supplier_banks SET
    MSB_ACCOUNT_NO='$accno', MSB_ACCOUNT_TYPE='$acctype', MSB_BANK_CODE='$bankcode'
    WHERE MSB_SUPPLIER_CODE='$sc'");
  header("Location: allactivesuppliersview.php?suppliercode=$sc&supmobile=$supmob&msg=bank_updated");
  exit();
}

if (isset($_POST['updateRefer'])) {
  $sc          = mysqli_real_escape_string($con, $_POST['refer_supplier_code']);
  $supmob      = mysqli_real_escape_string($con, $_POST['refer_supmobile']);
  $referenceNo = mysqli_real_escape_string($con, $_POST['msd_supplier_reference_no']);
  mysqli_query($con, "UPDATE mms_suppliers_details SET msd_supplier_reference_no='$referenceNo' WHERE msd_supplier_code='$sc'");
  header("Location: allactivesuppliersview.php?suppliercode=$sc&supmobile=$supmob&msg=ref_updated");
  exit();
}

if (isset($_POST['updateTaxBtn'])) {
  $supplierCode = isset($_GET['suppliercode']) ? $_GET['suppliercode'] : null;
  $statustax = $_POST['statustax'];
  $updatedate = date('Y-m-d');
  $query = "UPDATE mms_tax_details SET msd_status = '$statustax',updated_by = '" . $_SESSION['User'] . "',updated_date = '$updatedate' WHERE msd_supplier_code = '$supplierCode'";
  $stmtq = mysqli_query($con, $query);
  if ($stmtq) {
    echo '<script language="javascript">';
    echo 'alert("Data successfully added!"); location.href="allactivesuppliersview.php"';
    echo '</script>';
  } else {
    echo '<div class="alert alert-danger">Data not inserted!</div>';
  }
}

// Prepare data for the view
$supplierCode   = isset($_GET['suppliercode']) ? $_GET['suppliercode'] : null;
$suppliermobile = isset($_GET['supmobile'])    ? $_GET['supmobile']    : null;

$updateModalOpen = false;
$supplierDetails = [];
if ($supplierCode) {
  $updateModalOpen = true;
  $safeCode = mysqli_real_escape_string($con, $supplierCode);
  $datalist = selectquery(get_supplier_details_sql($safeCode));
  if (count($datalist) === 1) $supplierDetails = $datalist[0];
  $datalist = selectquery("SELECT msd_serial_no,msd_file_name,msd_file_path,msd_status FROM mms_supplier_attachments WHERE msd_sup_code='$safeCode' AND msd_status='A'");
  if (count($datalist) > 0) $supplierDetails['attachments'] = $datalist;
}

// Handle flash messages
if (isset($_GET['msg'])) {
  $msgs = ['supplier_updated'=>'Supplier Details Updated Successfully!!','bank_updated'=>'Bank Details Updated Successfully!!','ref_updated'=>'Reference Number Updated Successfully!!'];
  if (isset($msgs[$_GET['msg']])) {
    echo "<script>window.addEventListener('load',function(){ alert('" . $msgs[$_GET['msg']] . "'); });</script>";
  }
}

?>
