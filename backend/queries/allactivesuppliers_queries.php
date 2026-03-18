<?php
// SQL query helpers for allactivesuppliers
function get_active_suppliers_sql()
{
  return "SELECT * FROM mms_suppliers_details where msd_status IN('A','C') ORDER BY msd_supplier_code DESC";
}

function get_supplier_details_sql($supplierCode)
{
  // $supplierCode should be escaped by caller
  return "SELECT mmssd.*, mmssb.MSB_BANK_STATEMENT FROM mms_suppliers_details mmssd
    LEFT JOIN mms_supplier_banks mmssb ON mmssd.msd_supplier_code = mmssb.MSB_SUPPLIER_CODE
    WHERE mmssd.msd_supplier_code='" . $supplierCode . "' GROUP BY mmssd.msd_supplier_code";
}

function get_supplier_banks_sql($supplierCode)
{
  return "SELECT MMSSB.*, MMSDB.MBD_BANK_NAME, MMSDB2.MBD_BANK_NAME AS BRANCH_NAME FROM mms_supplier_banks MMSSB 
          LEFT JOIN mms_bank_details MMSDB ON MMSDB.MBD_CHILD_KEY = MMSSB.MSB_MAIN_BANK_CODE 
          LEFT JOIN mms_bank_details MMSDB2 ON MMSDB2.MBD_CHILD_KEY = MMSSB.MSB_CHILD_KEY
          WHERE MMSSB.MSB_SUPPLIER_CODE = '" . $supplierCode . "' ";
}

function get_tax_details_sql($supplierCode)
{
  return "SELECT * FROM mms_tax_details WHERE msd_supplier_code  = '" . $supplierCode . "'";
}

function get_categories_sql($supplierCode)
{
  return "SELECT DISTINCT MMC_CAT_CODE,(SELECT mtc_description FROM mms_tendermaterial_categories WHERE mtc_cat_code = MMC_CAT_CODE) AS CATDESC FROM mms_tenderprice_transactions LEFT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code WHERE mms_tenderprice_transactions.mtt_supplier_code = '" . $supplierCode . "'";
}

function get_all_banks_sql()
{
  return "SELECT MBD_CHILD_KEY, MBD_BANK_NAME FROM mms_bank_details WHERE MBD_STATUS = 'A' AND MBD_PARENT_KEY IS NULL";
}

function get_all_branches_sql()
{
  return "SELECT MBD_CHILD_KEY, MBD_BANK_NAME, MBD_PARENT_KEY FROM mms_bank_details WHERE MBD_STATUS = 'A' AND MBD_PARENT_KEY IS NOT NULL";
}


?>
