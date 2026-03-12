<?php
// SQL query helpers for addtender
function get_latest_tender_end_date_sql($currentYear) {
    return "SELECT mtd_end_date 
              FROM mms_tender_details 
              WHERE mtd_year = '$currentYear' 
              ORDER BY mtd_end_date DESC 
              LIMIT 1";
}

function update_tender_status_inactive_sql($year) {
    return "UPDATE mms_tender_details SET mtd_status='I' WHERE mtd_year='$year'";
}

function insert_tender_details_sql($year, $tno, $sdate, $edate, $formatted_closetime, $createdby, $createddate) {
    return "INSERT INTO mms_tender_details 
              (mtd_year, mtd_tender_no, mtd_start_date, mtd_end_date, mtd_bidclose_date, mtd_status, created_by, created_date) 
              VALUES 
              ('$year', '$tno', '$sdate', '$edate', '$formatted_closetime', 'A', '$createdby', '$createddate')";
}
?>
