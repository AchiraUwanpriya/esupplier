<?php
// SQL query helpers for monthlytenderview
function get_monthly_tenders_sql($mtd_type) {
    return "SELECT *, 
       CASE 
           WHEN mtd_status = 'A' THEN 'Active' 
           WHEN mtd_status = 'I' THEN 'Inactive'
           ELSE mtd_status 
       END AS mtd_status 
       FROM mms_tender_details WHERE mtd_type='$mtd_type' 
       ORDER BY mtd_bidclose_date DESC 
       LIMIT 10;";
}

function get_monthly_tender_suppliers_sql($tenderNo, $category_name) {
    return "SELECT mst.msd_tender_no, mst.msd_supplier_code, msd.msd_supplier_name 
             FROM mms_suptender_details mst
             INNER JOIN mms_suppliers_details msd ON mst.msd_supplier_code = msd.msd_supplier_code 
             WHERE mst.msd_tender_no =  '" . $tenderNo . "' 
             AND msd.msd_supply_category = '$category_name'";
}

function get_monthly_tender_items_sql($supplierCode, $tenderNo, $cat_code) {
    return "SELECT MMC_DESCRIPTION, MMC_UNIT, mtt_remark, mtt_price 
            FROM mms_tenderprice_transactions
            RIGHT JOIN mms_material_catalogue 
            ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code 
            AND mms_tenderprice_transactions.mtt_supplier_code = '" . $supplierCode . "' 
            AND mms_tenderprice_transactions.mtt_tender_no = '" . $tenderNo . "'
            WHERE MMC_CAT_CODE in ('" . $cat_code . "') 
            GROUP BY mms_material_catalogue.MMC_MATERIAL_CODE 
            ORDER BY MMC_DESCRIPTION ASC";
}
?>
