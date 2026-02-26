<?php
session_start();
if (!isset($_SESSION['sup_code'])) {
    header('Location: index.php');
}
include '../../../config.php';
include_once '../../../helper.php';
$suppliercode = $_SESSION['sup_code']; // 'CDPLC'

// Map CDPLC to numeric supplier ID
$numeric_supplier_id = '1682428362';

// Get current year
$currentYear = date('Y');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="../../../static/img/9.png" />
    <title>eSupplier-CDPLC - Tender Prices</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../static/css/main.css" rel="stylesheet">
    <link href="../../../static/css/app.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
</head>
<body>
    <div class="wrapper">
        <?php include '../../../components/sidenav.php' ?>
        <div class="main">
            <?php include '../../../components/navbar.php' ?>

            <!--Select Tender No -->
            <br>
            <div class="row container">
                <div class="col-4">
                    <select id="tendr" class="form-control" style="color: limegreen; font-weight: bolder; font-size: 16px;">
                        <option style="font-size: 16px" value="" selected="selected">
                            Select your Tender
                        </option>
                        <?php
                        // Get tenders from mms_suptender_details for current year
                        $sql = "SELECT msd_tender_no 
                                FROM mms_suptender_details 
                                WHERE msd_supplier_code = '$suppliercode' 
                                AND msd_tender_no LIKE '$currentYear-Week%'
                                ORDER BY msd_tender_no DESC 
                                LIMIT 10";
                        
                        $resultset = mysqli_query($con, $sql);
                        
                        if (!$resultset) {
                            echo '<option style="font-size: 16px" value="" disabled>Database error</option>';
                        } else if (mysqli_num_rows($resultset) == 0) {
                            echo '<option style="font-size: 16px" value="" disabled>No tenders found for ' . $currentYear . '</option>';
                        } else {
                            while ($rows = mysqli_fetch_assoc($resultset)) {
                                $tenderId = isset($_GET['tid']) ? $_GET['tid'] : '';
                                $tenderNo = $rows["msd_tender_no"];
                        ?>
                                <option style="font-size: 16px" value="<?php echo $tenderNo; ?>" <?php echo ($tenderId == $tenderNo) ? 'selected' : ''; ?>>
                                    <?php echo $tenderNo; ?>
                                </option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-8">
                    <a id="printLink" name="print" target="_blank">
                        <button style="font-size: 16px" type="button" id="printBtn" name="print" class="btn btn-success btn-lg">Print</button>
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
                        <tr>
                            <td colspan="3" class="text-center">Please select a tender to view details</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <?php include '../../../components/footer.php' ?>
        </div>
    </div>

    <script>
        let tenderId;
        $("#tendr").change(function() {
            tenderId = $(this).find(":selected").val();
            console.log("Selected Tender:", tenderId);

            if (!tenderId) {
                $('#gettbdata').html('<tr><td colspan="3" class="text-center">Please select a tender to view details</td></tr>');
                return;
            }

            // Update URL with tid parameter
            var newUrl = window.location.pathname + "?tid=" + encodeURIComponent(tenderId);
            window.history.pushState({ path: newUrl }, '', newUrl);

            $.ajax({
                type: 'GET',
                url: 'getTenderHistory.php',
                data: { tid: tenderId },
                dataType: 'json',
                success: function(response) {
                    console.log("Response:", response);
                    
                    if (response.error) {
                        $('#gettbdata').html('<tr><td colspan="3" class="text-center text-danger">Error: ' + response.error + '</td></tr>');
                    } else if (response && response.length > 0) {
                        let rowData = '';
                        response.forEach(element => {
                            let categoryName = element.CategoryName || 'Vegetables';
                            let description = element.MMC_DESCRIPTION || 'N/A';
                            let price = element.mtt_price || '0.00';
                            
                            rowData += `<tr>
                                            <td>${categoryName}</td>
                                            <td>${description}</td>
                                            <td>${price}</td>
                                        </tr>`;
                        });
                        $('#gettbdata').html(rowData);
                        $('#printLink').attr('href', 'prints/printAll.php?supid=<?php echo $_SESSION["sup_code"]; ?>&tno=' + encodeURIComponent(tenderId));
                    } else {
                        $('#gettbdata').html('<tr><td colspan="3" class="text-center">No data found for this tender</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    console.log("AJAX Error:", error);
                    console.log("Response:", xhr.responseText);
                    $('#gettbdata').html('<tr><td colspan="3" class="text-center text-danger">Error loading data: ' + error + '</td></tr>');
                }
            });
        });
        
        // Auto-load if tender ID is in URL
        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const tid = urlParams.get('tid');
            if (tid) {
                $('#tendr').val(tid).trigger('change');
            }
        });
    </script>

    <script src="../../../js/sessionUnset.js"></script>
    <script src="../../../static/js/app.js"></script>
    <script src="../../../js/translate.js"></script>
</body>
</html>