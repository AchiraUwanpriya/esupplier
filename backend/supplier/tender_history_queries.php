<?php
// backend/supplier/tender_history_queries.php

class TenderHistoryQueries {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    /**
     * Get the last 10 tender numbers for a supplier
     */
    public function getRecentTenderNumbers($supplierCode, $limit = 10) {
        $currentYear = date('Y');
        $yearFilter = $currentYear . '-%';
        $sql = "SELECT msd_tender_no, Year FROM (
                    SELECT msd_tender_no, 
                    CONVERT(SUBSTRING_INDEX(SUBSTRING_INDEX(msd_tender_no, 'Week', -1), '-', 1), UNSIGNED INTEGER) AS Week, 
                    CONVERT(SUBSTRING_INDEX(msd_tender_no, '-', 1), UNSIGNED INTEGER) AS Year 
                    FROM `mms_suptender_details` 
                    WHERE msd_supplier_code = ?
                    AND msd_tender_no LIKE ?
                ) AS SortedTenders 
                ORDER BY Year DESC, Week DESC 
                LIMIT ?";
        
        $stmt = mysqli_prepare($this->con, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssi", $supplierCode, $yearFilter, $limit);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $tenders = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $tenders[] = $row;
            }
            mysqli_stmt_close($stmt);
            return $tenders;
        }
        return [];
    }

    /**
     * Get tender history details for a specific tender number
     */
    public function getTenderHistoryDetails($supplierCode, $tenderNo) {
        $sql = "SELECT MMC_DESCRIPTION, mtt_price, MMC_CAT_CODE, 
                (SELECT mtc_description FROM mms_tendermaterial_categories WHERE mtc_cat_code = MMC_CAT_CODE) AS CategoryName 
                FROM mms_tenderprice_transactions 
                LEFT JOIN mms_tender_details ON mms_tender_details.mtd_tender_no = mms_tenderprice_transactions.mtt_tender_no 
                LEFT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code  
                WHERE mms_tenderprice_transactions.mtt_supplier_code = ? 
                AND mtt_tender_no = ? 
                ORDER BY MMC_CAT_CODE ASC";
        
        $stmt = mysqli_prepare($this->con, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $supplierCode, $tenderNo);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $data = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            mysqli_stmt_close($stmt);
            return $data;
        }
        return [];
    }
}
?>
