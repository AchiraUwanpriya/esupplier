<?php include 'config.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <link rel="shortcut icon" href="./static/img/9.png" />
    <title>eSupplier-CDPLC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="./static/js/jquery-3.3.1.min.js"></script>

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
    <div class="container">
        <div class="row">
                <p class="text-center" style="font-size: smaller; font-weight: bold;">Colombo Dockyard PLC <br> P.O. Box: 906, Port of Colombo, Colombo 15 <br> Tender for the supply of Foods - Vegetables</p>
                <div class="row">
                    <div class="col-4">
                    
                        <p style="font-size: smaller; font-weight: bold;">Supply Period: 2022.02.09 To 2022.02.15</p>
                    </div>
                    <div class="col-4">
                        <p style="font-size: smaller; font-weight: bold;">Closing Date: 2022.02.07 </p>
                    </div>
                    <div class="col-4">
                        <p style="font-size: smaller; font-weight: bold;">Time: 2.00 p.m </p>
                    </div>
                </div>
                <div class="col-6">
                    <table class=" table-bordered" style="font-size: 9px;">
                        <thead>
                            <tr>
                                <th>S/No</th>
                                <th>Description</th>
                                <th>Unit</th>
                                <th>Price</th>
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
                            AND mms_tenderprice_transactions.mtt_supplier_code = '1656666451' 
                            WHERE MMC_CAT_CODE in ('S') 
                            GROUP BY mms_material_catalogue.MMC_MATERIAL_CODE LIMIT 37";

                            $stmt = mysqli_query($con, $tsql);
                            if ($stmt === false) {
                                echo "Error in query";
                                die(print_r(mysqli_error($con), true));
                            }
                            while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
                            ?>
                                <tr>
                                    <td><?php echo $n; ?></td>
                                    <td><?php echo $row['MMC_DESCRIPTION'] ?></td>
                                    <td><?php echo $row['MMC_UNIT'] ?></td>
                                    <td><?php ?><?php echo $row['mtt_price']; ?></td>
                                </tr>

                            <?php
                                $n++;
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
                <div class="col-6">
                    <table class=" table-bordered" style="font-size: 9px;">
                        <thead>
                            <tr>
                                <th>S/No</th>
                                <th>Description</th>
                                <th>Unit</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $n = 38;
                            // $tsql = "SELECT MMC_DESCRIPTION,MMC_UNIT, mtt_price FROM mms_tenderprice_transactions 
                            // LEFT JOIN mms_tender_details ON mms_tender_details.mtd_tender_no = mms_tenderprice_transactions.mtt_tender_no 
                            // LEFT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code 
                            // and mms_tender_details.mtd_status = 'A' 
                            // WHERE mms_tenderprice_transactions.mtt_supplier_code = '1656666451' and MMC_CAT_CODE in ('V')";

                            // $tsql = "SELECT `MMC_DESCRIPTION`, `MMC_UNIT` FROM `mms_material_catalogue` where `MMC_CAT_CODE` = 'S' LIMIT 150 OFFSET 38";
                            $tsql = "SELECT MMC_DESCRIPTION,MMC_UNIT, mtt_price FROM mms_tenderprice_transactions
                            RIGHT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code 
                            AND mms_tenderprice_transactions.mtt_supplier_code = '1656666451' 
                            WHERE MMC_CAT_CODE in ('S') 
                            GROUP BY mms_material_catalogue.MMC_MATERIAL_CODE LIMIT 150 OFFSET 38";

                            $stmt = mysqli_query($con, $tsql);
                            if ($stmt === false) {
                                echo "Error in query";
                                die(print_r(mysqli_error($con), true));
                            }
                            while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
                            ?>
                                <tr>
                                    <td><?php echo $n; ?></td>
                                    <td><?php echo $row['MMC_DESCRIPTION'] ?></td>
                                    <td><?php echo $row['MMC_UNIT'] ?></td>
                                    <!-- <td><?php ?><?php echo $row['mtt_price']; ?></td> -->
                                </tr>

                            <?php
                                $n++;
                            }
                            ?>

                        </tbody>
                    </table> 
                </div>
                
                <!-- Terms and Conditions -->
                <fieldset>

                    <div class="form-card">
                        <br />

                        <div class="row">
                            <div class="col">
                                <!--<h2 class="fs-title">වර්ගය තෝරන්න:</h2>-->
                                <p class="fs-title" style="float:left; font-size: medium; font-weight: bold;">TERMS & CONDITIONS</p>
                            </div>
                        </div>
                        <!-- <br /> -->
                        <div class="row">
                            <ol>
                                <li class="justify-content-center">Successful tenderer should supply the ordered items on or before 2.00 p.m. on requested date as per CDPLC purchase order/s.</li>
                                <li class="justify-content-center">All items should be in acceptable quality. If reject (all lot or parts of supplied) supplier should remove the same immediately. </li>
                                <li class="justify-content-center">
                                    If rejected or failed to supply the order quantity within the required time / date (as per CDPLC purchase order) the selected supplier will be fined as follows.
                                    <ul class="" style="padding-left:40px">
                                        <li>a) If delay to supply (on or before 2.00 p.m.), the fine will be 5 % of the total purchase order value.</li>
                                        <li>b) If delay to supply (after 2.30 p.m.), the fine will be 10% against the total purchase order value.</li>
                                        <li>c) If failed to supply on required date as per our purchase order fine will 10 % from the total value of not supplied item/items.</li>
                                    </ul>
                                </li>
                                <li class="justify-content-center">If CDPLC agreed to amend the requested type of items against the CDPLC purchase orders as request by supplier, then the supplier should supply the alternative items at the lowest tendered price with 5 % discount. </li>
                                <li class="justify-content-center">The CDPLC reserve the right to accept / reject part of this offers or all.</li>
                                <li class="justify-content-center">CDPLC reserve the right to purchase the any of above item or all items from any other party / parties at its direction without assigning reason for same.</li>
                            </ol>
                            <strong>I/We agreed to abide by the terms & condition laid down pertaining to this tender.</strong>
                        </div>
                        <div class="row">
                            <label>
                                
                            </label>
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

                <div class="text-center">
                    <button id="print" class="btn btn-danger">print</button>
                </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="js/printThis.js"></script>
    <script>
        $('#print').click(function() {
            $('.container').printThis({
                debug: false, // show the iframe for debugging
                importCSS: true, // import parent page css
                importStyle: true, // import style tags
                printContainer: true, // print outer container/$.selector
                loadCSS: "", // path to additional css file - use an array [] for multiple
                pageTitle: " ", // add title to print page
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