<?php
// update_tender_status.php - Refactored for Public/Supplier/
$__root = __DIR__ . '/../../';
require_once $__root . 'backend/common/config.php';
require_once $__root . 'backend/supplier/tender_status_queries.php';

date_default_timezone_set("Asia/Colombo");
$statusQueries = new TenderStatusQueries();

$tenders = $statusQueries->getActiveTenders();
$dateNow = strtotime(date("Y-m-d h:i:sa"));

foreach ($tenders as $tender) {
    if (isset($tender["mtd_bidclose_date"])) {
        $closedate = strtotime($tender["mtd_bidclose_date"]);
        if ($closedate <= $dateNow) {
            if ($statusQueries->closeTender($tender)) {
                echo "Tender " . $tender['mtd_tender_no'] . " closed successfully.<br>";
            } else {
                echo "Failed to close tender " . $tender['mtd_tender_no'] . ".<br>";
            }
        }
    }
}
?>
