<?php
session_start();

if (!isset($_SESSION['mobile_number']) || !isset($_SESSION['name']) || !isset($_SESSION['entry'])) {
    header('Location: ../../admin.php');
    exit();
}

require_once __DIR__ . '/common/config.php';
include_once __DIR__ . '/queries/addtender_queries.php';

date_default_timezone_set('Asia/Colombo');

$entry = $_SESSION['entry'];
$ButtonsDisabled = ($entry == 'N');

/* ================= AUTO CALCULATE NEXT TENDER ================= */

function calculateNextTenderDates($con) {
    $currentYear = date('Y');
    $query = get_latest_tender_end_date_sql($currentYear);
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $lastEndDate = $row['mtd_end_date'];
        $currentEnd = new DateTime($lastEndDate);
        $nextDate = clone $currentEnd;
        $nextDate->modify('+1 day');
        if ($nextDate->format('l') != 'Wednesday') {
            $nextDate->modify('next Wednesday');
        }
        $nextStartDate = $nextDate->format('Y-m-d');
    } else {
        $today = new DateTime();
        if ($today->format('l') == 'Wednesday') {
            $nextStartDate = $today->format('Y-m-d');
        } else {
            $today->modify('next Wednesday');
            $nextStartDate = $today->format('Y-m-d');
        }
    }
    $nextEndDate = date('Y-m-d', strtotime('+6 days', strtotime($nextStartDate)));
    $bidCloseDate = date('Y-m-d', strtotime('-1 day', strtotime($nextEndDate))) . ' 14:30';

    return [
        'year' => $currentYear,
        'start_date' => $nextStartDate,
        'end_date' => $nextEndDate,
        'bid_close_date' => $bidCloseDate
    ];
}

function getNextTenderNumber($startDate) {
    $year = date('Y', strtotime($startDate));
    $weekNumber = date('W', strtotime($startDate)); // ISO week number
    return $year . "-Week" . (int)$weekNumber;
}

$nextDates = calculateNextTenderDates($con);
$nextTenderNo = getNextTenderNumber($nextDates['start_date']);

/* ================= INSERT ================= */

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['insertbtn'])) {
    $year = $_POST['year'];
    $sdate = $_POST['sdate'];

    // Auto End Date
    $edate = date('Y-m-d', strtotime('+6 days', strtotime($sdate)));

    // Auto Bid Closing
    $mtd_bidclose_date = date('Y-m-d', strtotime('-1 day', strtotime($edate))) . ' 14:30';
    $formatted_closetime = date('Y-m-d g:i A', strtotime($mtd_bidclose_date));

    $createdby = $_SESSION['name'];
    $createddate = date('Y-m-d');

    $weekNumber = date('W', strtotime($sdate));
    $tno = date('Y', strtotime($sdate)) . "-Week" . (int)$weekNumber;

    mysqli_query($con, update_tender_status_inactive_sql($year));
    $query = insert_tender_details_sql($year, $tno, $sdate, $edate, $formatted_closetime, $createdby, $createddate);

    if (mysqli_query($con, $query)) {
        echo "<script>alert('Records Saved Successfully!!');</script>";
    } else {
        echo "<script>alert('ERROR!');</script>";
    }
}
?>
