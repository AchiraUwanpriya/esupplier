<?php
session_start();
if (!isset($_SESSION['sup_code'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$supplierCode = $_SESSION['sup_code'];
require_once '../../backend/common/config.php';
require_once '../../backend/supplier/tender_history_queries.php';

if (isset($_REQUEST['tid'])) {
    $tenderNo = $_REQUEST['tid'];
    $queries = new TenderHistoryQueries($con);
    $tdata = $queries->getTenderHistoryDetails($supplierCode, $tenderNo);
    
    $_SESSION['tid'] = $tenderNo;
    echo json_encode($tdata);
} else {
    echo 0;
}
?>
