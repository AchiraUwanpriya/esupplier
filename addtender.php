<?php
session_start();

if (!isset($_SESSION['mobile_number']) || !isset($_SESSION['name']) || !isset($_SESSION['entry'])) {
    header('Location: admin.php');
    exit();
}

include 'config.php';

$entry = $_SESSION['entry'];
$ButtonsDisabled = ($entry == 'N');

// Function to calculate next tender dates automatically
function calculateNextTenderDates($con) {
    date_default_timezone_set('Asia/Colombo');
    
    // Get current year
    $currentYear = date('Y');
    
    // Get the latest tender's end date for current year
    $query = "SELECT mtd_end_date 
              FROM mms_tender_details 
              WHERE mtd_year = '$currentYear' 
              ORDER BY mtd_end_date DESC 
              LIMIT 1";
    
    $result = mysqli_query($con, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $lastEndDate = $row['mtd_end_date'];
        
        // Last tender ended on Tuesday
        // Next tender should start on Wednesday of the following week
        $nextStartDate = date('Y-m-d', strtotime('next wednesday', strtotime($lastEndDate)));
        
    } else {
        // No existing tender for this year
        // Check if we're past Wednesday this week
        $today = date('Y-m-d');
        $todayDay = date('w', strtotime($today));
        
        if ($todayDay >= 3) { // Wednesday(3) or later
            $nextStartDate = date('Y-m-d', strtotime('next wednesday'));
        } else {
            $nextStartDate = date('Y-m-d', strtotime('wednesday this week'));
        }
    }
    
    // End date is Tuesday of the next week
    $nextEndDate = date('Y-m-d', strtotime('tuesday next week', strtotime($nextStartDate)));
    
    // Bid closing is Monday at 2:30 PM of the start date week
    $bidCloseDate = date('Y-m-d', strtotime('monday this week', strtotime($nextStartDate))) . ' 14:30';
    
    return [
        'year' => $currentYear,
        'start_date' => $nextStartDate,
        'end_date' => $nextEndDate,
        'bid_close_date' => $bidCloseDate,
        'bid_close_formatted' => date('Y-m-d\TH:i', strtotime($bidCloseDate))
    ];
}

// Function to get next tender number
function getNextTenderNumber($con, $year) {
    $selQuery = "SELECT MAX(CAST(SUBSTRING_INDEX(mtd_tender_no, '-Week', -1) AS UNSIGNED)) AS max_week 
                 FROM mms_tender_details 
                 WHERE mtd_year = '$year'";
    
    $query_run = mysqli_query($con, $selQuery);
    
    if (!$query_run) {
        return "Error getting tender number";
    }
    
    $row = mysqli_fetch_array($query_run, MYSQLI_ASSOC);
    $suffix = $row && $row["max_week"] ? (int)$row["max_week"] : 0;
    
    return $year . "-Week" . ($suffix + 1);
}

// Calculate next dates for auto-fill
$nextDates = calculateNextTenderDates($con);
$nextTenderNo = getNextTenderNumber($con, $nextDates['year']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['insertbtn'])) {
        $separator = "-Week";
        $year = $_POST['year'];
        $sdate = $_POST['sdate'];
        $edate = $_POST['edate'];
        $mtd_bidclose_date = $_POST['bidclosedate'];
        
        $mtd_bidclose_date_obj = new DateTime($mtd_bidclose_date);
        date_default_timezone_set('Asia/Colombo');
        $formatted_closetime = $mtd_bidclose_date_obj->format('Y-m-d g:i A');

        $createdby = $_SESSION['name'];
        $createddate = date('Y-m-d');

        $selQuery = "SELECT MAX(CAST(SUBSTRING_INDEX(mtd_tender_no, '-Week', -1) AS UNSIGNED)) AS max_week 
                     FROM mms_tender_details 
                     WHERE mtd_year = '$year'";
        $query_run = mysqli_query($con, $selQuery);

        if (!$query_run) {
            die("Database error while getting max week: " . mysqli_error($con));
        }

        $row = mysqli_fetch_array($query_run, MYSQLI_ASSOC);
        $suffix = $row && $row["max_week"] ? (int)$row["max_week"] : 0;

        // Generate new tender number
        $tno = $year . $separator . ($suffix + 1);

        // Update all existing tenders to Inactive
        $query = "UPDATE mms_tender_details SET mtd_status='I' WHERE mtd_year='$year'";
        $query_run = mysqli_query($con, $query);

        // Insert new tender
        $query = "INSERT INTO mms_tender_details 
                  (mtd_year, mtd_tender_no, mtd_start_date, mtd_end_date, mtd_bidclose_date, mtd_status, created_by, created_date) 
                  VALUES 
                  ('$year', '$tno', '$sdate', '$edate', '$formatted_closetime', 'A', '$createdby', '$createddate')";
        $query_run = mysqli_query($con, $query);

        if ($query_run) {
            echo "<script>alert('Records Saved Successfully!!');</script>";
        } else {
            echo "<script>alert('ERROR!');</script>";
            echo json_encode(mysqli_error($con));
            die;
        }
    }
    
    // Handle auto-fill request
    if (isset($_POST['auto_fill'])) {
        // This will be handled by JavaScript to fill the form
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="./static/img/2.svg" />
    <link rel="canonical" href="https://demo-basic.adminkit.io/" />
    <title>eSupplier-CDL - Tender Creation</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link href="./static/css/app.css" rel="stylesheet">
    <link href="./static/css/main.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <script src="./static/js/jquery-3.3.1.min.js"></script>
    <script src="./static/js/jquery.validate.min.js"></script>
    <script src="./static/js/jquery.validate.unobtrusive.min.js"></script>
    <script src="./static/js/app.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <style>
        .auto-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .auto-dates {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
        }
        .date-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            padding-bottom: 5px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        .date-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .btn-auto-fill {
            margin-top: 10px;
            width: 100%;
        }
        .form-section {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <?php include './Admin_components/adminsidenav.php' ?>
        <div class="main">
            <?php include './Admin_components/adminnavbar.php' ?>
            
            <main class="content">
                <div class="container-fluid p-0">
                    <h1 class="h3 mb-3">
                        <strong>
                            <i class="fa fa-file-contract"></i> eSupplier Add Tender
                        </strong>
                    </h1>
                    
                    <!-- Auto-Calculation Section -->
                    <div class="auto-section">
                        <h5><i class="fa fa-calculator"></i> Auto-Calculated Next Tender</h5>
                        <div class="auto-dates">
                            <div class="date-item">
                                <span><strong>Next Tender Number:</strong></span>
                                <span><?php echo $nextTenderNo; ?></span>
                            </div>
                            <div class="date-item">
                                <span><strong>Start Date (Wednesday):</strong></span>
                                <span><?php echo date('d M Y', strtotime($nextDates['start_date'])); ?></span>
                            </div>
                            <div class="date-item">
                                <span><strong>End Date (Tuesday):</strong></span>
                                <span><?php echo date('d M Y', strtotime($nextDates['end_date'])); ?></span>
                            </div>
                            <div class="date-item">
                                <span><strong>Bid Closing (Monday 2:30 PM):</strong></span>
                                <span><?php echo date('d M Y H:i', strtotime($nextDates['bid_close_date'])); ?></span>
                            </div>
                        </div>
                        <button type="button" id="autoFillBtn" class="btn btn-info btn-auto-fill">
                            <i class="fa fa-magic"></i> Fill Auto-Calculated Dates
                        </button>
                    </div>
                    
                    <!-- Manual Tender Creation Form -->
                    <div class="form-section">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fa fa-plus-circle"></i> Create New Tender</h5>
                            </div>
                            <div class="card-body">
                                <form id="tender" name="tender" method="POST">
                                    <div class="form-group">
                                        <label for="year">Year</label>
                                        <select class="form-control" name="year" id="year">
                                            <?php
                                            $currentYear = date('Y');
                                            for ($y = $currentYear; $y <= $currentYear + 3; $y++) {
                                                $selected = ($y == $nextDates['year']) ? 'selected' : '';
                                                echo "<option value='$y' $selected>$y</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="sdate">Start Date (Wednesday)</label>
                                        <input type="date" class="form-control" name="sdate" id="sdate" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="edate">End Date (Tuesday)</label>
                                        <input type="date" class="form-control" name="edate" id="edate" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="bidclosedate">Bid Closing Date (Monday 2:30 PM)</label>
                                        <input type="datetime-local" class="form-control" name="bidclosedate" id="bidclosedate" required>
                                    </div>

                                    <div class="modal-footer">
                                        <a href="tenderview.php" class="btn btn-primary">
                                            <i class="fa fa-arrow-left"></i> Back to List
                                        </a>
                                        <button type="submit" name="insertbtn" id="insertbtn" class="btn btn-success" 
                                                <?php if ($ButtonsDisabled) echo 'disabled'; ?>>
                                            <i class="fa fa-save"></i> Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php include './components/footer.php' ?>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Auto-fill button click handler
        $('#autoFillBtn').click(function() {
            // Get calculated dates from PHP
            var year = '<?php echo $nextDates["year"]; ?>';
            var startDate = '<?php echo $nextDates["start_date"]; ?>';
            var endDate = '<?php echo $nextDates["end_date"]; ?>';
            var bidClose = '<?php echo $nextDates["bid_close_formatted"]; ?>';
            
            // Fill form fields
            $('#year').val(year);
            $('#sdate').val(startDate);
            $('#edate').val(endDate);
            $('#bidclosedate').val(bidClose);
            
            // Show confirmation message
            alert('Auto-calculated dates have been filled into the form!\n\n' +
                  'Start Date: ' + startDate + ' (Wednesday)\n' +
                  'End Date: ' + endDate + ' (Tuesday)\n' +
                  'Bid Closing: ' + bidClose.replace('T', ' ') + ' (Monday 2:30 PM)');
        });
        
        // Form validation
        $('#tender').submit(function(e) {
            var startDate = new Date($('#sdate').val());
            var endDate = new Date($('#edate').val());
            var bidClose = new Date($('#bidclosedate').val());
            
            // Check if dates are valid
            if (isNaN(startDate.getTime()) || isNaN(endDate.getTime()) || isNaN(bidClose.getTime())) {
                alert('Please enter valid dates');
                return false;
            }
            
            // Check if end date is after start date
            if (endDate <= startDate) {
                alert('End date must be after start date!');
                e.preventDefault();
                return false;
            }
            
            // Check if bid closing is before start date
            if (bidClose >= startDate) {
                if(!confirm('Bid closing date is on or after start date. This is unusual. Continue anyway?')) {
                    e.preventDefault();
                    return false;
                }
            }
            
            // Check if start date is Wednesday (3)
            var startDay = startDate.getDay(); // 0=Sunday, 3=Wednesday
            if (startDay !== 3) {
                if(!confirm('Start date is not a Wednesday. Continue anyway?')) {
                    e.preventDefault();
                    return false;
                }
            }
            
            return true;
        });
        
        // Auto-fill on page load if form is empty
        if (!$('#sdate').val()) {
            $('#autoFillBtn').click();
        }
        
        // Date change handlers to show day of week
        $('#sdate').change(function() {
            var date = new Date($(this).val());
            if (!isNaN(date.getTime())) {
                var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                $(this).next('.form-text').remove();
                $(this).after('<small class="form-text text-muted">' + days[date.getDay()] + '</small>');
            }
        });
        
        $('#edate').change(function() {
            var date = new Date($(this).val());
            if (!isNaN(date.getTime())) {
                var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                $(this).next('.form-text').remove();
                $(this).after('<small class="form-text text-muted">' + days[date.getDay()] + '</small>');
            }
        });
    });
    
    function logoutfunction() {
        if(confirm("Please Confirm To Logout!!")) {
            window.location.href = 'logout.php';
        }
    }
    </script>
</body>
</html>