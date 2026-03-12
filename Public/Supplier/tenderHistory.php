<?php
session_start();
if (!isset($_SESSION['sup_code'])) {
    header('Location: ../../index.php');
    exit();
}

$__root = "../../";
$sbase = "../../";

require_once $__root . 'backend/common/config.php';
require_once $__root . 'backend/common/helper.php';
require_once $__root . 'backend/supplier/tender_history_queries.php';

$suppliercode = $_SESSION['sup_code'];
$queries = new TenderHistoryQueries($con);
$recentTenders = $queries->getRecentTenderNumbers($suppliercode);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="shortcut icon" href="<?= $sbase ?>static/img/9.png" />

    <title>eSupplier-CDPLC - Tender Prices</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link href="<?= $sbase ?>static/css/main.css" rel="stylesheet">
    <link href="<?= $sbase ?>static/css/app.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

</head>

<body>
    <div class="wrapper">
        <!-- sidenav -->
        <?php include './components/sidenav.php' ?>
        <div class="main">
            <!-- navbar -->
            <?php include './components/navbar.php' ?>

            <!--Select Tender No -->
            <br>
            <div class="row container">
                <div class="col-4">
                    <select id="tendr" class="form-control" style="color: limegreen; font-weight: bolder; font-size: 16px;">
                        <option style="font-size: 16px" value="" selected="selected">
                            Select your Tender
                        </option>
                        <?php
                        foreach ($recentTenders as $tender) {
                            $tenderNo = $tender["msd_tender_no"];
                        ?>
                            <option style="font-size: 16px" value="<?php echo $tenderNo; ?>"><?php echo $tenderNo; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-8">
                    <button style="font-size: 16px" type="button" id="printBtn" name="print" class="btn btn-success btn-lg">Print</button>
                </div>

            </div>
            <br>
            <div class="container-fluid" style="height:100%; overflow-y: scroll;">
                <table class="display table table-hover table-bordered border-primary">
                    <thead>
                        <tr class="fixed">
                            <th class="bg-success text-center">
                                <h3 class="fw-bold">Category Name</h3>
                            </th>
                            <th class="bg-success text-center">
                                <h3 class="fw-bold">Description</h3>
                            </th>
                            <th class="bg-success text-center">
                                <h3 class="fw-bold">Price (Rs.)</h3>
                            </th>
                        </tr>
                    </thead>

                    <tbody id="gettbdata">
                    </tbody>
                </table>
            </div>

            <!-- footer -->
            <?php
            include './components/footer.php'
            ?>
        </div>
    </div>

    <script>
        let tenderId;
        $("#tendr").change(function() {
            tenderId = $(this).find(":selected").val();
            console.log(tenderId);

            if (!tenderId) {
                $('#gettbdata').html('');
                return;
            }

            // Update URL with tid parameter
            var newUrl = window.location.pathname + "?tid=" + encodeURIComponent(tenderId);
            window.history.pushState({
                path: newUrl
            }, '', newUrl);

            var dataString = 'tid=' + tenderId;

            $.ajax({
                type: 'get',
                url: 'getTenderHistory.php',
                data: dataString,
                success: function(tdata) {
                    if (tdata) {
                        let list = JSON.parse(tdata);
                        let rowData = ''
                        list.forEach(element => {
                            rowData += `<tr><td>${element.CategoryName}</td><td>${element.MMC_DESCRIPTION}</td><td>${element.mtt_price}</td></tr>`
                        });
                        $('#gettbdata').html(rowData);
                    }
                },
            });
        });

        // Print button click handler
        $('#printBtn').click(function() {
            if (!tenderId) {
                alert('Please select a tender first');
                return;
            }
            // Navigate to print page with correct path from root
            window.open('<?= $sbase ?>Public/Supplier/pages/prints/printAll.php?supid=<?php echo $_SESSION["sup_code"]; ?>&tno=' + encodeURIComponent(tenderId), '_blank');
        });
    </script>

    <!-- timer script sessionUnset -->
    <script src="<?= $sbase ?>js/sessionUnset.js"></script>
    <script src="<?= $sbase ?>static/js/app.js"></script>
    <script src="<?= $sbase ?>js/translate.js"></script>

</body>

</html>