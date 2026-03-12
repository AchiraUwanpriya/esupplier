<?php
// SQL query helpers for addfood

function get_active_units_sql() {
    return "SELECT unit_code, unit_name FROM mms_units WHERE unit_status = 'A' ORDER BY unit_name";
}

function get_materials_by_category_sql($catCode) {
    return "SELECT MMC_MATERIAL_CODE, MMC_DESCRIPTION, MMC_MATERIAL_SPEC, MMC_UNIT, MMC_STATUS
            FROM mms_material_catalogue
            WHERE MMC_CAT_CODE = '" . $catCode . "'
            ORDER BY MMC_DESCRIPTION";
}
?>
