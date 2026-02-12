<?php include '../config.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <link rel="shortcut icon" href="../static/img/9.png" />
    <title>eSupplier-CDPLC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="../static/js/jquery-3.3.1.min.js"></script>

    <style>
        .form-card{
            font-size: x-small;
        }

        @media print {
            #print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <?php 
        $var_value = $_GET['supid'];
        $tenderno_val = $_GET['tno'];

        include '../config.php';
        $tsql = "SELECT mtd_tender_no ,mtd_start_date,mtd_end_date,mtd_bidclose_date, (select msd_supplier_name from mms_suppliers_details where msd_supplier_code = '".$var_value."') as sup_nameee  FROM mms_tender_details LEFT JOIN mms_tenderprice_transactions ON mms_tenderprice_transactions.mtt_tender_no = mms_tender_details.mtd_tender_no
        WHERE mms_tenderprice_transactions.mtt_supplier_code = '".$var_value."' AND mtd_tender_no = '".$tenderno_val ."'";

        $stmt = mysqli_query($con, $tsql);
        if ($stmt === false) {
            echo "Error in query";
            die("database error:" . mysqli_error($con));
        }
        while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {

            global $startdatee;

            $startdatee = $row ['mtd_start_date'];
            $enddatee = $row ['mtd_end_date'];
            $suppiler_name = $row ['sup_nameee'];
            $bidclose_date = $row['mtd_bidclose_date'];
            $supply_end_period = date('Y-m-d', strtotime($enddatee . ' + 7 days'));
            $supply_start_period = date('Y-m-d', strtotime($enddatee . ' + 1 day'));
            
        }
    ?>
    <div class="container">
        
        <div class="row">
            <div class="text-center">
                <button id="print" class="btn btn-success mt-3 mb-3" style="float: left;">Print</button>
            </div>

                <p class="text-center" style="font-size: smaller; font-weight: bold;">Colombo Dockyard PLC <br> P.O. Box: 906, Port of Colombo, Colombo 15 <br> Tender for the supply of Foods - Wrapping Papers</p>
                <div class="row">
                    <p style="font-size: smaller; font-weight: bold">Supplier Name and Address: <?php echo $suppiler_name  ?> </p>
                    <div class="col-4">
                        <p style="font-size: smaller; font-weight: bold;">Supply Period: <?php echo $supply_start_period ?> To <?php echo $supply_end_period?></p>
                    </div>
    
                    <div class="col-4">
                        <p style="font-size: smaller; font-weight: bold;">Bid Closed Date: <?php echo $bidclose_date ?> </p>
                    </div>
                    <div class="col-4">
                        <p style="font-size: smaller; font-weight: bold;">Time: 2.30 p.m </p>
                    </div>
                </div>
                <div class="col-12">
                    <table class=" table-bordered" style="font-size: 9px;">
                        <thead>
                            <tr>
                                <th style="padding-left: 20px; padding-right: 10px;">S/No</th>
                                <th style="padding-left: 30px; padding-right: 20px;">Item Name</th>
                                <th style="padding-left: 20px; padding-right: 10px;">Unit</th>
                                <th style="padding-left: 20px; padding-right: 10px;">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $n = 1;
                            // $tsql = "SELECT MMC_DESCRIPTION,MMC_UNIT, mtt_price FROM mms_tenderprice_transactions 
                            // LEFT JOIN mms_tender_details ON mms_tender_details.mtd_tender_no = mms_tenderprice_transactions.mtt_tender_no 
                            // LEFT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code 
                            // and mms_tender_details.mtd_status = 'A' 
                            // WHERE mms_tenderprice_transactions.mtt_supplier_code = '1656666451' and MMC_CAT_CODE in ('V')";

                            // $tsql = "SELECT `MMC_DESCRIPTION`, `MMC_UNIT` FROM `mms_material_catalogue` where `MMC_CAT_CODE` = 'S' LIMIT 37";
                            $tsql = "SELECT MMC_DESCRIPTION,MMC_UNIT, mtt_price FROM mms_tenderprice_transactions
                            RIGHT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code 
                            AND mms_tenderprice_transactions.mtt_supplier_code = '".$var_value."' 
                            AND mms_tenderprice_transactions.mtt_tender_no = '" . $tenderno_val . "'
                            WHERE MMC_CAT_CODE in ('W') 
                            GROUP BY mms_material_catalogue.MMC_MATERIAL_CODE
                            ORDER BY MMC_DESCRIPTION ASC";

                            $stmt = mysqli_query($con, $tsql);
                            if ($stmt === false) {
                                echo "Error in query";
                                die(print_r(mysqli_error($con), true));
                            }
                            while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
                            ?>
                                <tr>
                                    <td style="padding-left: 20px; padding-right: 10px;"><?php echo $n; ?></td>
                                    <td style="padding-left: 30px; padding-right: 20px;"><?php echo $row['MMC_DESCRIPTION'] ?></td>
                                    <td style="padding-left: 20px; padding-right: 10px;"><?php echo $row['MMC_UNIT'] ?></td>
                                    <td style="padding-left: 20px; padding-right: 10px;"><?php ?><?php echo $row['mtt_price']; ?></td>
                                </tr>

                            <?php
                                $n++;
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
 
                <!-- Terms and Conditions -->
                <fieldset >

                    <div class="form-card">
                        <br />
                        
                        <div class="row">
                            <br><br>
                            <div class="col-6">
                                <!--<h2 class="fs-title">වර්ගය තෝරන්න:</h2>-->
                                <p class="fs-title text-center" style="float:left">.................................... <br> Signature/Tender <br> (Company Seal)</p>
                            </div>
                            <div class="col-6">
                                <!--<p class="fs-title">වර්ගය තෝරන්න:</p>-->
                                <p class="fs-title text-center" style="float:right">................................... <br> Date</p>
                            </div>
                        </div>
                    </div>
                </fieldset>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="../js/printThis.js"></script>
    <script>
        $('#print').click(function() {
            $('.container').printThis({
                debug: false, // show the iframe for debugging
                importCSS: true, // import parent page css
                importStyle: true, // import style tags
                printContainer: true, // print outer container/$.selector
                loadCSS: "", // path to additional css file - use an array [] for multiple
                pageTitle: "  ", // add title to print page
                removeInline: false, // remove inline styles from print elements
                removeInlineSelector: "*", // custom selectors to filter inline styles. removeInline must be true
                printDelay: 333, // variable print delay
                header: "<h1></h1>", // prefix to html
                footer: null, // postfix to html
                base: false, // preserve the BASE tag or accept a string for the URL
                formValues: true, // preserve input/form values
                canvas: true, // copy canvas content
                doctypeString: '<!DOCTYPE html>', // enter a different doctype for older markup
                removeScripts: false, // remove script tags from print content
                copyTagClasses: true, // copy classes from the html & body tag
                copyTagStyles: true, // copy styles from html & body tag (for CSS Variables)
                beforePrintEvent: null, // callback function for printEvent in iframe
                beforePrint: null, // function called before iframe is filled
                afterPrint: null // function called before iframe is removed
            });
        })
    </script>


</body>

</html>