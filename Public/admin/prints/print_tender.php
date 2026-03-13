<?php
require_once '../../../backend/common/config.php';

// Get parameters
$supId = isset($_GET['supid']) ? mysqli_real_escape_string($con, $_GET['supid']) : '';
$tenderNo = isset($_GET['tno']) ? mysqli_real_escape_string($con, $_GET['tno']) : '';
$catCode = isset($_GET['cat']) ? mysqli_real_escape_string($con, $_GET['cat']) : '';

if (!$supId || !$tenderNo || !$catCode) {
    die("Missing required parameters.");
}

// Category mapping
$category_map = [
    'V' => 'Vegetables',
    'S' => 'Spices',
    'F' => 'Fish',
    'D' => 'Dry Fish',
    'O' => 'Coconut oil and Creamer',
    'Y' => 'Dry Items',
    'C' => 'Coconut',
    'E' => 'Eggs',
    'R' => 'Rice',
    'H' => 'Meat',
    'M' => 'Miscellaneous Items',
    'P' => 'PVC Items',
    'I' => 'Medicine Items',
    'B' => 'Cables'
];

$categoryName = isset($category_map[$catCode]) ? $category_map[$catCode] : $catCode;

// Fetch Supplier Info
$supplierName = 'N/A';
$supplierAddr = 'N/A';
$sql = "SELECT msd_supplier_name FROM mms_suppliers_details WHERE msd_supplier_code = '$supId' LIMIT 1";
$res = mysqli_query($con, $sql);
if ($row = mysqli_fetch_assoc($res)) {
    $supplierName = $row['msd_supplier_name'];
}

// Fetch Tender Info
$supplyPeriod = 'N/A';
$bidCloseDate = 'N/A';
$bidCloseTime = '2:30 p.m'; // Based on screenshot

$sql = "SELECT mtd_start_date, mtd_end_date, mtd_bidclose_date FROM mms_tender_details WHERE mtd_tender_no = '$tenderNo' LIMIT 1";
$res = mysqli_query($con, $sql);
if ($row = mysqli_fetch_assoc($res)) {
    if ($row['mtd_start_date'] && $row['mtd_end_date']) {
        $supplyPeriod = $row['mtd_start_date'] . " To " . $row['mtd_end_date'];
    }
    if ($row['mtd_bidclose_date']) {
        $bidCloseDate = $row['mtd_bidclose_date'];
    }
}

// Fetch Items - Use RIGHT JOIN to ensure all items in the catalogue are listed
$items = [];
$sql = "SELECT mc.MMC_DESCRIPTION, mc.MMC_UNIT, tpt.mtt_price 
        FROM mms_tenderprice_transactions tpt
        RIGHT JOIN mms_material_catalogue mc ON mc.MMC_MATERIAL_CODE = tpt.mtt_material_code
        AND tpt.mtt_supplier_code = '$supId' 
        AND tpt.mtt_tender_no = '$tenderNo' 
        WHERE mc.MMC_CAT_CODE = '$catCode'
        GROUP BY mc.MMC_MATERIAL_CODE
        ORDER BY mc.MMC_DESCRIPTION ASC";

$res = mysqli_query($con, $sql);
while ($row = mysqli_fetch_assoc($res)) {
    $items[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>eSupplier-CDPLC - Print <?php echo $categoryName; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { font-size: 11px; font-family: Arial, sans-serif; }
        .header-text { font-weight: bold; text-align: center; margin-bottom: 20px; }
        .info-row { font-weight: bold; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid black; padding: 4px; }
        th { background-color: #f2f2f2; text-align: center; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        @media print {
            #printBtn { display: none; }
        }
    </style>
</head>
<body>
    <div class="container mt-3">
        <div class="d-flex justify-content-start mb-3">
            <button id="printBtn" class="btn btn-success btn-sm">Print</button>
        </div>

        <div class="header-text">
            Colombo Dockyard PLC <br>
            P.O. Box: 906, Port of Colombo, Colombo 15 <br>
            Tender for the supply of Foods - <?php echo $categoryName; ?>
        </div>

        <div class="info-row">
            Supplier Name and Address: <?php echo htmlspecialchars($supplierName); ?>
        </div>

        <div class="row info-row">
            <div class="col-6">Supply Period: <?php echo $supplyPeriod; ?></div>
            <div class="col-3">Bid Closed Date: <?php echo $bidCloseDate; ?></div>
            <div class="col-3 text-end">Time: <?php echo $bidCloseTime; ?></div>
        </div>

        <?php
        // Split items into two columns as per screenshot
        $totalItems = count($items);
        $midpoint = ceil($totalItems / 2);
        $leftItems = array_slice($items, 0, $midpoint);
        $rightItems = array_slice($items, $midpoint);
        ?>

        <div class="row">
            <!-- Left Table -->
            <div class="col-6">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 10%;">S/No</th>
                            <th style="width: 50%;">Item Name</th>
                            <th style="width: 15%;">Unit</th>
                            <th style="width: 25%;">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($leftItems as $index => $item): ?>
                        <tr>
                            <td class="text-center"><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($item['MMC_DESCRIPTION']); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($item['MMC_UNIT']); ?></td>
                            <td class="text-end"><?php echo number_format($item['mtt_price'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Right Table -->
            <div class="col-6">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 10%;">S/No</th>
                            <th style="width: 50%;">Item Name</th>
                            <th style="width: 15%;">Unit</th>
                            <th style="width: 25%;">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($rightItems as $index => $item): ?>
                        <tr>
                            <td class="text-center"><?php echo $midpoint + $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($item['MMC_DESCRIPTION']); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($item['MMC_UNIT']); ?></td>
                            <td class="text-end"><?php echo number_format($item['mtt_price'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $('#printBtn').click(function() {
            window.print();
        });
    </script>
</body>
</html>
