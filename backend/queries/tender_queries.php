<?php
// Query functions for tenders
function getTenders($con, $type = null) {
    $typeCondition = $type ? "WHERE mtd_type='" . mysqli_real_escape_string($con, $type) . "'" : "WHERE mtd_type is null";
    $sql = "SELECT *, CASE WHEN mtd_status = 'A' THEN 'Active' WHEN mtd_status = 'I' THEN 'Inactive' ELSE mtd_status END AS mtd_status FROM mms_tender_details $typeCondition ORDER BY mtd_bidclose_date DESC LIMIT 10;";
    $stmt = mysqli_query($con, $sql);
    $tenders = [];
    if ($stmt) {
        while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
            $tenders[] = $row;
        }
    }
    return $tenders;
}
// Add more query functions as needed
?>