<?php
// SQL query helpers for tenderview
function get_tenders_sql() {
    return "SELECT *, 
       CASE 
           WHEN mtd_status = 'A' THEN 'Active' 
           WHEN mtd_status = 'I' THEN 'Inactive'
           ELSE mtd_status 
       END AS mtd_status 
       FROM mms_tender_details WHERE mtd_type is null
       ORDER BY mtd_bidclose_date DESC 
       LIMIT 10;";
}

function get_tender_suppliers_sql($tenderNo) {
    return "SELECT msd_tender_no, mms_suptender_details.msd_supplier_code, msd_supplier_name 
            FROM mms_suptender_details 
            LEFT JOIN mms_suppliers_details 
            ON mms_suptender_details.msd_supplier_code = mms_suppliers_details.msd_supplier_code 
            WHERE msd_tender_no = '" . $tenderNo . "'";
}

function get_tender_items_by_category_sql($supplierCode, $tenderNo, $categoryCode) {
    return "SELECT MMC_DESCRIPTION, MMC_UNIT, mtt_remark, mtt_price 
            FROM mms_tenderprice_transactions
            RIGHT JOIN mms_material_catalogue 
            ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code 
            AND mms_tenderprice_transactions.mtt_supplier_code = '" . $supplierCode . "' 
            AND mms_tenderprice_transactions.mtt_tender_no = '" . $tenderNo . "'
            WHERE MMC_CAT_CODE in ('" . $categoryCode . "') 
            GROUP BY mms_material_catalogue.MMC_MATERIAL_CODE 
            ORDER BY MMC_DESCRIPTION ASC";
}
?>
