<?php
session_start();
// Public/admin/fullpricelist.php - Refactored

require_once '../../backend/common/config.php';
require_once '../../backend/admin/tender_queries.php';

$tender_no = $_GET["tender_no"] ?? '';
if (!$tender_no) {
    die("Tender number is required.");
}

$queries = new TenderQueries();

// Get suppliers for this tender
$datalist = $queries->getSuppliersForTender($tender_no);

// Get the full price schedule data
$table_data = $queries->getFullPriceSchedule($tender_no, $datalist);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="../../static/img/2.svg" />
    <title>eSupplier-CDL</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css" crossorigin="anonymous" />
    <link href="../../static/css/app.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>

    <style>
        .table-wrapper {
            overflow: auto;
            max-height: 800px;
        }

        .sticky-header th {
            position: sticky;
            top: 0;
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
                    <br />
                    <h2 style="color: green; font-weight: bolder; text-align: center;">Full Price Schedule - <?= htmlspecialchars($tender_no) ?></h2>
                    <br />
                    <div style="display: flex; justify-content: space-between;">
                        <button type="button" id="exportBtn" class="btn btn-success" style="margin-bottom: 15px; height: 40px; width: 250px;">Export to EXCEL</button>
                        <a href="tenderview.php">
                            <button type="button" id="exportBtnBack" class="btn btn-info" style="margin-bottom: 15px; height: 40px; width: 100px;">Back</button>
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
                                <th style="background-color: #17a2b8; color: black;">MAT SPEC</th> <!-- New MAT SPEC column -->

                                <?php
                                foreach ($datalist as $value) {
                                    echo "<th>" . htmlspecialchars($value['msd_supplier_name']) . "</th>";
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($table_data as $value) {
                                echo "<tr>";
                                foreach ($value as $key2 => $val) {
                                    // Display the columns with left alignment for Material Name and Spec
                                    if ($key2 === 'Material_Description' || $key2 === 'Material_Spec' || $key2 === 'Serial_Number') {
                                        echo "<td style='text-align: left'>" . htmlspecialchars($val) . "</td>";
                                    } else {
                                        echo "<td>" . htmlspecialchars($val) . "</td>";
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
            var tenderNo = "<?= $tender_no; ?>";
            var filename = "Full Price List - Tender No (" + tenderNo + ").xlsx";

            downloadXLSX(data, filename);
        });
    </script>

    <!-- footer -->
    <?php include 'components/footer.php' ?>
    <!-- End Footer -->
    <script src="../../static/js/jquery-3.3.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>

</html>
