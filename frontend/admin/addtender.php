<?php include_once __DIR__ . '/../../backend/addtender_controller.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="../static/img/2.svg" />
    <link rel="canonical" href="https://demo-basic.adminkit.io/" />
    <title>eSupplier-CDL - Tender Creation</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link href="../static/css/app.css" rel="stylesheet">
    <link href="../static/css/main.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <script src="../static/js/jquery-3.3.1.min.js"></script>
    <script src="../static/js/jquery.validate.min.js"></script>
    <script src="../static/js/jquery.validate.unobtrusive.min.js"></script>
    <script src="../static/js/app.js"></script>

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
        <?php include 'components/adminsidenav.php' ?>
        <div class="main">
            <?php include 'components/adminnavbar.php' ?>
            
            <main class="content">
                <div class="container-fluid p-0">
                    <h1 class="h3 mb-3">
                        <strong>
                            <i class="fa fa-file-contract"></i> eSupplier Add Tender
                        </strong>
                    </h1>
                    
                    <!-- Auto-Calculation Section
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
                    </div> -->
                    
                    <!-- Manual Tender Creation Form -->
                    <div class="form-section">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fa fa-plus-circle"></i> Create New Tender</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST">

<div class="form-group">
    <label>Year</label>
    <select class="form-control" name="year">
        <?php
        $currentYear = date('Y');
        for ($y = $currentYear; $y <= $currentYear + 3; $y++) {
            echo "<option value='$y'>$y</option>";
        }
        ?>
    </select>
</div>

<div class="form-group">
    <label>Start Date (Wednesday)</label>
    <input type="date" class="form-control" name="sdate" id="sdate" required>
</div>

<div class="form-group">
    <label>End Date (Auto)</label>
    <input type="date" class="form-control" name="edate" id="edate" readonly required>
</div>

<div class="form-group">
    <label>Bid Closing (Auto - 2:30 PM)</label>
    <input type="datetime-local" class="form-control" name="bidclosedate" id="bidclosedate" readonly required>
</div>

<button type="submit" name="insertbtn" class="btn btn-success"
<?php if ($ButtonsDisabled) echo 'disabled'; ?>>
Save Tender
</button>

</form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php include 'components/adminfooter.php' ?>
        </div>
    </div>

   <script>
document.getElementById('sdate').addEventListener('change', function() {

    var startDate = new Date(this.value);

    if (isNaN(startDate)) return;

    // End date = start + 6 days
    var endDate = new Date(startDate);
    endDate.setDate(endDate.getDate() + 6);

    var endFormatted = endDate.toISOString().split('T')[0];
    document.getElementById('edate').value = endFormatted;

    // Bid close = 1 day before end at 2:30 PM
    var bidClose = new Date(endDate);
    bidClose.setDate(bidClose.getDate() - 1);
    bidClose.setHours(14, 30, 0, 0);

    var yyyy = bidClose.getFullYear();
    var mm = String(bidClose.getMonth() + 1).padStart(2, '0');
    var dd = String(bidClose.getDate()).padStart(2, '0');
    var hh = String(bidClose.getHours()).padStart(2, '0');
    var min = String(bidClose.getMinutes()).padStart(2, '0');

    document.getElementById('bidclosedate').value =
        yyyy + '-' + mm + '-' + dd + 'T' + hh + ':' + min;

});
</script>
</body>
</html>