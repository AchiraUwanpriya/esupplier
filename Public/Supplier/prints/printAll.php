<?php
include '../../../backend/common/config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="../static/img/9.png" />
    <title>eSupplier-CDPLC - Tender Print</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        /* Modern Print Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fb;
            margin: 0;
            padding: 20px;
        }
        
        .print-card {
            max-width: 1100px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: none;
        }
        
        .print-header {
            background: linear-gradient(135deg, #2c3e50 0%, #1a472a 100%);
            color: white;
            padding: 25px 30px;
            position: relative;
        }
        
        .print-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .print-header h3 {
            margin: 5px 0 0;
            font-size: 16px;
            font-weight: 400;
            opacity: 0.9;
        }
        
        .print-header .company-logo {
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 14px;
            text-align: right;
        }
        
        .info-grid {
            display: flex;
            flex-wrap: wrap;
            background: #f8fafc;
            padding: 20px 30px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .info-item {
            flex: 1 1 33%;
            margin-bottom: 10px;
        }
        
        .info-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        
        .info-value {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .info-value.highlight {
            color: #1a472a;
        }
        
        .table-container {
            padding: 20px 30px;
        }
        
        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .modern-table thead tr {
            background: linear-gradient(135deg, #e8f0e8 0%, #d0e0d0 100%);
        }
        
        .modern-table th {
            padding: 14px 16px;
            font-size: 14px;
            font-weight: 600;
            color: #1a472a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            text-align: left;
        }
        
        .modern-table td {
            padding: 14px 16px;
            font-size: 14px;
            border-bottom: 1px solid #e9ecef;
            background-color: white;
        }
        
        .modern-table tbody tr:hover td {
            background-color: #f8fafc;
        }
        
        .modern-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .sno-col {
            width: 60px;
            color: #6c757d;
            font-weight: 500;
        }
        
        .price-col {
            font-weight: 600;
            color: #1a472a;
            text-align: right;
        }
        
        .unit-col {
            text-align: center;
            color: #6c757d;
        }
        
        .total-row {
            background: linear-gradient(135deg, #f8fafc 0%, #e9ecef 100%);
            font-weight: 600;
        }
        
        .total-row td {
            font-size: 15px;
            border-top: 2px solid #1a472a !important;
        }
        
        .footer-note {
            padding: 20px 30px 30px;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
            border-top: 1px dashed #d0e0d0;
            margin-top: 10px;
        }
        
        .print-btn-modern {
            display: inline-block;
            padding: 12px 28px;
            background: linear-gradient(135deg, #1a472a 0%, #2c5e2c 100%);
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(26, 71, 42, 0.3);
            margin: 20px 30px;
            border: 1px solid rgba(255,255,255,0.1);
        }
        
        .print-btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 71, 42, 0.4);
            background: linear-gradient(135deg, #1f5230 0%, #2e6e2e 100%);
        }
        
        .print-btn-modern i {
            margin-right: 8px;
        }
        
        @media print {
            body {
                background-color: white;
                padding: 0;
            }
            
            .print-card {
                box-shadow: none;
                border-radius: 0;
                max-width: 100%;
            }
            
            .print-header {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .modern-table thead tr {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .print-btn-modern {
                display: none;
            }
            
            .info-grid {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <?php
    $__root = __DIR__ . '/../../../';
    include_once $__root . 'backend/common/config.php';

    $var_value = mysqli_real_escape_string($con, $_GET['supid']);
    $tenderno_val = mysqli_real_escape_string($con, $_GET['tno']);

    // Get supplier name
    $suppiler_name = 'N/A';
    
    // Try mms_suppliers_details first
    $supplier_sql = "SELECT msd_supplier_name 
                     FROM mms_suppliers_details 
                     WHERE msd_supplier_code = '$var_value'
                     LIMIT 1";
    $supplier_result = mysqli_query($con, $supplier_sql);
    
    if ($supplier_result && mysqli_num_rows($supplier_result) > 0) {
        $supplier_data = mysqli_fetch_assoc($supplier_result);
        $suppiler_name = $supplier_data['msd_supplier_name'];
    } else {
        // Try mms_supplier_pending_details as fallback
        $supplier_sql2 = "SELECT msd_supplier_name 
                          FROM mms_supplier_pending_details 
                          WHERE msd_supplier_code = '$var_value'
                          LIMIT 1";
        $supplier_result2 = mysqli_query($con, $supplier_sql2);
        if ($supplier_result2 && mysqli_num_rows($supplier_result2) > 0) {
            $supplier_data2 = mysqli_fetch_assoc($supplier_result2);
            $suppiler_name = $supplier_data2['msd_supplier_name'];
        } else {
            $suppiler_name = $var_value;
        }
    }
    
    // Get tender information
    $tsql = "SELECT mtd_tender_no, mtd_start_date, mtd_end_date, mtd_bidclose_date 
            FROM mms_tender_details 
            WHERE mtd_tender_no = '$tenderno_val'
            LIMIT 1";

    $stmt = mysqli_query($con, $tsql);
    
    $startdatee = '';
    $enddatee = '';
    $bidclose_date = '';
    
    if ($stmt && mysqli_num_rows($stmt) > 0) {
        $row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);
        $startdatee = isset($row['mtd_start_date']) ? $row['mtd_start_date'] : '';
        $enddatee = isset($row['mtd_end_date']) ? $row['mtd_end_date'] : '';
        $bidclose_date = isset($row['mtd_bidclose_date']) ? $row['mtd_bidclose_date'] : '';
    }

    $supply_end_period = $enddatee ? date('Y-m-d', strtotime($enddatee . ' + 7 days')) : 'N/A';
    $supply_start_period = $enddatee ? date('Y-m-d', strtotime($enddatee . ' + 1 day')) : 'N/A';
    
    // Get current date for print date
    $print_date = date('Y-m-d H:i:s');
    ?>

    <div class="print-card">
        <button id="printBtn" class="print-btn-modern">
            <i class="fas fa-print"></i> Print Document
        </button>

        <!-- Header -->
        <div class="print-header">
            <h1>Colombo Dockyard PLC</h1>
            <h3>P.O. Box: 906, Port of Colombo, Colombo 15</h3>
            <div class="company-logo">
                <div>Est. 1974</div>
            </div>
        </div>

        <!-- Title -->
        <div style="padding: 10px 30px 0;">
            <h2 style="color: #1a472a; margin: 0; font-size: 22px; font-weight: 600;">Tender for the Supply of Foods</h2>
        </div>

        <!-- Information Grid -->
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Supplier Name</div>
                <div class="info-value highlight"><?php echo htmlspecialchars($suppiler_name ?: 'N/A'); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Tender Number</div>
                <div class="info-value"><?php echo htmlspecialchars($tenderno_val); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Print Date</div>
                <div class="info-value"><?php echo $print_date; ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Supply Period</div>
                <div class="info-value highlight"><?php echo $supply_start_period; ?> to <?php echo $supply_end_period; ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Bid Closing Date</div>
                <div class="info-value"><?php echo $bidclose_date ? $bidclose_date . ' at 2:30 PM' : 'N/A'; ?></div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-container">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th class="sno-col">#</th>
                        <th>Item Name</th>
                        <th class="unit-col">Unit</th>
                        <th class="price-col">Price (Rs.)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Query to get items
                    $items_sql = "SELECT 
                                    mc.MMC_DESCRIPTION, 
                                    mc.MMC_UNIT, 
                                    tpt.mtt_price 
                                FROM mms_tenderprice_transactions tpt
                                LEFT JOIN mms_material_catalogue mc 
                                    ON mc.MMC_MATERIAL_CODE = tpt.mtt_material_code 
                                WHERE tpt.mtt_supplier_code = '1682428362' 
                                    AND tpt.mtt_tender_no = '$tenderno_val'  
                                ORDER BY mc.MMC_DESCRIPTION ASC";

                    $items_result = mysqli_query($con, $items_sql);
                    
                    if (!$items_result) {
                        echo "<tr><td colspan='4' style='text-align: center; padding: 40px; color: #dc3545;'>
                                <i class='fas fa-exclamation-triangle'></i> Database Error: " . mysqli_error($con) . "
                              </td></tr>";
                    } else if (mysqli_num_rows($items_result) > 0) {
                        $n = 1;
                        $total = 0;
                        
                        while ($item = mysqli_fetch_assoc($items_result)) {
                            $price = floatval($item['mtt_price']);
                            $total += $price;
                            ?>
                            <tr>
                                <td class="sno-col"><?php echo str_pad($n, 2, '0', STR_PAD_LEFT); ?></td>
                                <td><?php echo htmlspecialchars($item['MMC_DESCRIPTION'] ?? 'N/A'); ?></td>
                                <td class="unit-col"><?php echo htmlspecialchars($item['MMC_UNIT'] ?? 'N/A'); ?></td>
                                <td class="price-col"><?php echo number_format($price, 2); ?></td>
                            </tr>
                            <?php
                            $n++;
                        }
                        
                        // Total row
                        ?>
                        <tr class="total-row">
                            <td colspan="2" style="text-align: right; font-weight: 600;">Total Items: <?php echo ($n-1); ?></td>
                            <td class="unit-col">Total</td>
                            <td class="price-col" style="font-size: 16px;"><?php echo number_format($total, 2); ?></td>
                        </tr>
                        <?php
                    } else {
                        ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 40px; color: #6c757d;">
                                <i class="fas fa-info-circle"></i> No items found for this tender (Tender: <?php echo htmlspecialchars($tenderno_val); ?>)
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Footer Note -->
        <div class="footer-note">
            <p style="margin: 0 0 5px;">This is a computer-generated document. No signature is required.</p>
            <p style="margin: 0;">&copy; <?php echo date('Y'); ?> Colombo Dockyard PLC. All rights reserved.</p>
        </div>
    </div>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
    
    <!-- Simple print script that works -->
    <script>
        $(document).ready(function() {
            // Make sure print button works
            $('#printBtn').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Print button clicked - opening print dialog...');
                
                // Small delay to ensure everything is ready
                setTimeout(function() {
                    window.print();
                }, 100);
            });
            
            console.log('Print page ready - button should work');
        });
    </script>
</body>

</html>
