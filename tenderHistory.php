<?php
session_start();
if (!isset($_SESSION['sup_code'])) {
    header('Location: index.php');
}
include 'config.php';
include_once 'helper.php';
$suppliercode = $_SESSION['sup_code'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="shortcut icon" href="./static/img/9.png" />

    <title>eSupplier-CDPLC - Tender Prices</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


    <link href="./static/css/main.css" rel="stylesheet">
    <link href="./static/css/app.css" rel="stylesheet">

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

                        // $sql = "SELECT msd_tender_no, CONVERT(REPLACE(msd_tender_no, '2023-Week', ''), UNSIGNED INTEGER) AS Number FROM `mms_suptender_details` WHERE msd_supplier_code = '$suppliercode' ORDER BY Number DESC LIMIT 10";
                           $sql = "SELECT msd_tender_no, Year FROM ( SELECT msd_tender_no, CONVERT(SUBSTRING_INDEX(SUBSTRING_INDEX(msd_tender_no, 'Week', -1), '-', 1), UNSIGNED INTEGER) AS Week, CONVERT(SUBSTRING_INDEX(msd_tender_no, '-', 1), UNSIGNED INTEGER) AS Year FROM `mms_suptender_details` WHERE msd_supplier_code = '$suppliercode' ) AS SortedTenders ORDER BY Year DESC, Week DESC LIMIT 10";

                        $resultset = mysqli_query($con, $sql);
                        while ($rows = mysqli_fetch_assoc($resultset)) {
                            $tenderId = $_GET['tid'];
                        ?>

                            <option style="font-size: 16px" value="<?php echo $rows["msd_tender_no"]; ?>"><?php echo $rows["msd_tender_no"]; ?></option>

                        <?php
                        }
                        ?>

                    </select>
                </div>
                <div class="col-8">
                    <a id="print" name="print" target="_blank">
                        <button style="font-size: 16px" type="button" id="print" name="print" class="btn btn-success btn-lg">Print</button>
                    </a>
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

                        // Update Print button href
                        $('#print').attr('href', 'prints/printAll.php?supid=<?php echo $_SESSION["sup_code"]; ?>&tno=' + encodeURIComponent(tenderId));
                    }
                },
                // error: function(response) {
                //     console.log("error");
                // },
            });
        });
    </script>

    <!-- timer script sessionUnset -->
    <script src="js/sessionUnset.js"></script>

    <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script> -->
    <script src="static/js/app.js"></script>
    <script src="js/translate.js"></script>

</body>

</html>