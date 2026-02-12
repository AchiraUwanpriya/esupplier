<?php
session_start();
?>
<?php
include_once 'config.php';
$tender_no = $_GET["tender_no"];
$query = "SELECT DISTINCT mtt_supplier_code, msd_supplier_name FROM mms_tenderprice_transactions 
LEFT JOIN mms_suppliers_details ON mms_suppliers_details.msd_supplier_code = mtt_supplier_code 
LEFT JOIN mms_suptender_details ON mms_suptender_details.msd_supplier_code = mtt_supplier_code 
WHERE msd_tender_no = '$tender_no'
AND mms_suptender_details.msd_status = 'A' 
AND mms_suppliers_details.msd_supplier_code IS NOT NULL";

global $con;
$query_run = mysqli_query($con, $query);
$datalist = [];
if ($query_run) {
    while ($row = mysqli_fetch_array($query_run, MYSQLI_ASSOC)) {
        array_push($datalist, $row);
    }
} else {
    print_r(mysqli_error($con));
}
// print_r($datalist);
$query = "SELECT
ROW_NUMBER() OVER (ORDER BY c.mmc_description) AS Serial_Number,
  t.mtt_material_code AS Material_Code,
  c.mmc_description AS Material_Description,
  c.mmc_unit AS Unit";
$suppliers = [];

foreach ($datalist as $key => $value) {
    $supplier_name = mysqli_real_escape_string($con, $value["msd_supplier_name"]); // Escape the supplier name
    $query .= ", MAX(CASE WHEN d.msd_supplier_name = '$supplier_name' THEN t.mtt_price END) AS `$supplier_name`";
}
$query .= " FROM
  mms_tenderprice_transactions t
  JOIN mms_material_catalogue c ON t.mtt_material_code = c.mmc_material_code
  JOIN mms_suppliers_details d ON t.mtt_supplier_code = d.msd_supplier_code
WHERE
  mtt_tender_no = '$tender_no'
GROUP BY
  t.mtt_tender_no,
  t.mtt_material_code,
  c.mmc_description
ORDER BY
 c.mmc_description;";
// echo ($query);
$query_run = mysqli_query($con, $query);
$table_data = [];
if ($query_run) {
    while ($row = mysqli_fetch_array($query_run, MYSQLI_ASSOC)) {
        array_push($table_data, $row);
    }
} else {
    print_r(mysqli_error($con));
}
//print_r($datalist);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="./static/img/2.svg" />
    <title>eSupplier-CDL</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css" crossorigin="anonymous" />
    <link href="./static/css/app.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>

    <style>
        .table-wrapper {
            overflow: auto;
            max-height: 800px;
            /* Set a max height for the table wrapper to enable scrolling */
        }

        .sticky-header th {
            position: sticky;
            top: 0;
            /* background-color: #17a2b8; */
            z-index: 1;
        }

        .custom-control-label::before,
        .custom-control-label::after {
            top: .9rem;
            width: 1.25rem;
            height: 1.25rem;
        }

        th {
            border: 1px solid #ddd;
            text-align: center;
            padding: 15px;
            background-color: cornflowerblue;
            color: white;
        }

        td {
            border: 1px solid #ddd;
            text-align: right;
            padding: 15px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }
    </style>
</head>

<body>
    <main id="main">
        <section class="inner-page">
            <div class="container-fluid">
                <div>
		    <br/>
                    <h2 style="color: green; font-weight: bolder; text-align: center;">Full Price Schedule - <?php echo $tender_no ?></h2>
                    <br />
                    <div style="display: flex; justify-content: space-between;">
                        <button type="button" id="exportBtn" class="btn btn-success" style="margin-bottom: 15px; height: 40px; width: 250px;">Export to EXCEL</button>
                        <a href="tenderview.php">
                            <button type="button" id="exportBtn" class="btn btn-info" style="margin-bottom: 15px; height: 40px; width: 100px;">Back</button>
                        </a>
                    </div>
                </div>

                <div class="table-wrapper">
                    <table style="width: 100%; font-size: small;" id="myTable">
                        <thead>
                            <tr class="sticky-header">
                                <th style="background-color: #17a2b8; color: black;">SERIAL NO</th>
                                <th style="background-color: #17a2b8; color: black;">MATERIAL CODE</th>
                                <th style="background-color: #17a2b8; color: black;">MATERIAL NAME</th>
                                <th style="background-color: #17a2b8; color: black;">UNIT</th>

                                <?php
                                foreach ($datalist as $key => $value) {
                                    echo "<th>" . $value['msd_supplier_name'] . "</th>";
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // foreach ($table_data as $key => $value) {
                            //     echo "<tr>";
                            //     foreach ($value as $key2 => $val) {
                            //         // Apply left alignment to the Material Name column
                            //         if ($key2 === 'Material_Description' || $key2 === 'Serial_Number') {
                            //             echo "<td style='text-align: left'>" . $val . "</td>";
                            //         } else {
                            //             echo "<td>" . $val . "</td>";
                            //         }
                            //     }
                            //     echo "</tr>";
                            // }
                            foreach ($table_data as $key => $value) {
                                echo "<tr>";
                                foreach ($value as $key2 => $val) {
                                    if ($key2 === 'mmc_unit') {
                                        echo "<td>" . $val . "</td>";
                                    } else {
                                        echo "<td>" . $val . "</td>";
                                    }
                                }
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
    <!-- End #main -->
    <script>
        function myFunction() {
            // Declare variables
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        function downloadXLSX(data, filename) {
            var worksheet = XLSX.utils.aoa_to_sheet(data);
            var workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, "Sheet1");
            var xlsxFile = XLSX.write(workbook, {
                bookType: "xlsx",
                type: "array"
            });
            var blob = new Blob([xlsxFile], {
                type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
            });

            var downloadLink = document.createElement("a");
            downloadLink.href = window.URL.createObjectURL(blob);
            downloadLink.download = filename;
            downloadLink.style.display = "none";
            document.body.appendChild(downloadLink);
            downloadLink.click();
        }
        document.getElementById("exportBtn").addEventListener("click", function() {
            var table = document.getElementById("myTable");
            var data = [];
            var rows = table.getElementsByTagName("tr");

            // Process each row
            for (var i = 0; i < rows.length; i++) {
                var row = [];
                var cols = rows[i].querySelectorAll("td, th");

                // Process each column
                for (var j = 0; j < cols.length; j++) {
                    var cellValue = cols[j].innerText;
                    row.push(cellValue);
                }

                data.push(row);
            }
            var tenderNo = "<?php echo $tender_no; ?>";
            var filename = "Full Price List - Tender No (" + tenderNo + ").xlsx";

            downloadXLSX(data, filename);
        });
    </script>

    <!-- footer -->
    <?php include './components/footer.php' ?>
    <!-- End Footer -->
    <script src="./static/js/jquery-3.3.1.min.js"></script>
    <script src="./static/js/jquery.validate.min.js"></script>
    <script src="./static/js/jquery.validate.unobtrusive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
</body>

</html>