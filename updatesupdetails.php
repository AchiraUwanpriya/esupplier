<?php

include 'config.php';
if($_SERVER['REQUEST_METHOD']=='POST'){
  if(isset($_POST['insertbtn'])){

  $supname = $_POST['supname'];
  $email = $_POST['email'];
  $mobile = $_POST['mobile'];
  $bsnature = $_POST['bsnature'];
  $manufacture = $_POST['manufacture'];
  $telnumber = $_POST['telnumber'];
  $productCat = $_POST['productCat'];
  $fax = $_POST['fax'];
  $inputAddress = $_POST['inputAddress'];
  $country = $_POST['country'];
  $suptype = $_POST['suptype'];
  $contactperson = $_POST['contactperson'];
  $productCat = $_POST['productCat'];
  $supStatus = $_POST['supStatus'];
  $payterms = $_POST['payterms'];
  $vatnumber = $_POST['vatnumber'];
  $currency = $_POST['currency']; //no field
  $svatnumber = $_POST['svatnumber'];
  

  // $query = "UPDATE mms_suppliers_details SET msd_business_nature = '$bsnature',msd_manufacture = '$manufacture',msd_teleno = '$telnumber',
  // msd_supply_category = '$productCat', msd_faxno = '$fax', msd_sup_type = '$suptype', msd_contact_person = '$contactperson', msd_product_catalogue = '$productCat', msd_supplier_status = '$supStatus', msd_payment_terms = '$payterms', msd_vatno = '$vatnumber', msd_svat_no = '$svatnumber' 
  // WHERE msd_supplier_code = 'SUP1'";

  $query = "UPDATE mms_suppliers_details SET msd_business_nature = '".$_POST['bsnature']."',msd_manufacture = '".$_POST['bsnature']."',msd_teleno = '".$_POST['mobile']."',
  msd_supply_category = '".$_POST['productCat']."', msd_faxno = '".$_POST['fax']."', msd_sup_type = '".$_POST['suptype']."', msd_contact_person = '".$_POST['contactperson']."', msd_product_catalogue = '".$_POST['productCat']."', msd_supplier_status = '".$_POST['supStatus']."', msd_payment_terms = '".$_POST['payterms']."', msd_vatno = '".$_POST['vatnumber']."', msd_svat_no = '".$_POST['svatnumber']."' 
  WHERE msd_supplier_code = 'SUP2'";

  // $query ="UPDATE mms_material_catalogue SET MMC_DESCRIPTION='".$_POST['Description']."', MMC_MATERIAL_SPEC='".$_POST['MaterialSpec']."' WHERE MMC_MATERIAL_CODE='".$_POST['MaterialCode']."'";
  
 $query_run = sqlsrv_query($con,$query);

  if($query_run) {
    echo "Successfully Added your details!";
  }
  else {
    echo "Please fill the fields!";
  }
}
}
