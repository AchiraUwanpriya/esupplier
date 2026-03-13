<?php
// backend/admin/tender_queries.php

require_once __DIR__ . '/../common/db.php';

class TenderQueries {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getSuppliersForTender($tender_no) {
        $query = "SELECT DISTINCT mtt_supplier_code, msd_supplier_name 
                  FROM mms_tenderprice_transactions 
                  LEFT JOIN mms_suppliers_details ON mms_suppliers_details.msd_supplier_code = mtt_supplier_code 
                  LEFT JOIN mms_suptender_details ON mms_suptender_details.msd_supplier_code = mtt_supplier_code 
                  WHERE mms_suptender_details.msd_tender_no = ?
                  AND mms_suptender_details.msd_status = 'A' 
                  AND mms_suppliers_details.msd_supplier_code IS NOT NULL";
        
        return $this->query($query, [$tender_no]);
    }

    public function getFullPriceSchedule($tender_no, $suppliers) {
        // Build the dynamic SQL for MAX(CASE...) part
        $dynamicCols = "";
        foreach ($suppliers as $supplier) {
            $supplier_name = $supplier['msd_supplier_name'];
            $escaped_name = str_replace("'", "''", $supplier_name);
            $dynamicCols .= ", MAX(CASE WHEN d.msd_supplier_name = '$escaped_name' THEN t.mtt_price END) AS `$escaped_name`";
        }

        $query = "SELECT
          ROW_NUMBER() OVER (ORDER BY c.mmc_description) AS Serial_Number,
          t.mtt_material_code AS Material_Code,
          c.mmc_description AS Material_Description,
          c.mmc_unit AS Unit,
          c.mmc_material_spec AS Material_Spec
          $dynamicCols
         FROM
          mms_tenderprice_transactions t
          JOIN mms_material_catalogue c ON t.mtt_material_code = c.mmc_material_code
          JOIN mms_suppliers_details d ON t.mtt_supplier_code = d.msd_supplier_code
        WHERE
          mtt_tender_no = ?
        GROUP BY
          t.mtt_tender_no,
          t.mtt_material_code,
          c.mmc_description,
          c.mmc_material_spec,
          c.mmc_unit
        ORDER BY
          c.mmc_description";

        return $this->query($query, [$tender_no]);
    }

    private function query($sql, $params = []) {
        $stmt = mysqli_prepare($this->db, $sql);
        if ($stmt === false) {
            return [];
        }
        if ($params) {
            $types = str_repeat('s', count($params)); 
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($res === false) {
            return [];
        }
        return mysqli_fetch_all($res, MYSQLI_ASSOC);
    }
}
