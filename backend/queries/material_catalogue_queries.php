<?php
/**
 * Material Catalogue Queries
 * Handles all database operations for material catalogue management
 */

/**
 * Insert a new material into the catalogue
 * 
 * @param object $con Database connection
 * @param string $MaterialCode Material code
 * @param string $Description Material description
 * @param string $MaterialSpec Material specification
 * @param string $Unit Unit of measurement
 * @param string $CatCode Category code
 * @return array Result array with status and message
 */
function insertMaterial($con, $MaterialCode, $Description, $MaterialSpec, $Unit, $CatCode) {
	$query = "INSERT INTO mms_material_catalogue 
        (MMC_MATERIAL_CODE, MMC_DESCRIPTION, MMC_MATERIAL_SPEC, MMC_UNIT, MMC_STATUS, MMC_CAT_CODE, created_date) 
        VALUES 
        ('$MaterialCode', '$Description', '$MaterialSpec', '$Unit', 'A', '$CatCode', CURDATE())";
	
	$query_run = mysqli_query($con, $query);
	
	if ($query_run) {
		return array('status' => true, 'message' => 'Data Inserted Successfully!');
	} else {
		return array('status' => false, 'message' => 'Error: ' . mysqli_error($con));
	}
}

/**
 * Update an existing material in the catalogue
 * 
 * @param object $con Database connection
 * @param string $MaterialCode Material code (primary key)
 * @param string $Description Material description
 * @param string $MaterialSpec Material specification
 * @param string $Status Material status (A/I)
 * @param string $UpdatedBy User ID who updated
 * @param string $UpdatedDate Date of update
 * @return array Result array with status and message
 */
function updateMaterial($con, $MaterialCode, $Description, $MaterialSpec, $Status, $UpdatedBy, $UpdatedDate) {
	$query = "UPDATE mms_material_catalogue SET 
	    MMC_DESCRIPTION='" . $Description . "', 
	    MMC_MATERIAL_SPEC='" . $MaterialSpec . "', 
	    MMC_STATUS='" . $Status . "',
	    updated_by='" . $UpdatedBy . "',
	    updated_date='" . $UpdatedDate . "' 
	WHERE MMC_MATERIAL_CODE='" . $MaterialCode . "'";
	
	$query_run = mysqli_query($con, $query);
	
	if ($query_run) {
		return array('status' => true, 'message' => 'Data Updated Successfully!');
	} else {
		return array('status' => false, 'message' => 'Error: ' . mysqli_error($con));
	}
}

/**
 * Get all materials from a specific category
 * 
 * @param object $con Database connection
 * @param string $CatCode Category code
 * @return array Result from query or false on error
 */
function getMaterialsByCategory($con, $CatCode) {
	$query = "SELECT * FROM mms_material_catalogue WHERE MMC_CAT_CODE = '$CatCode' AND MMC_STATUS = 'A'";
	return mysqli_query($con, $query);
}

/**
 * Get all active materials
 * 
 * @param object $con Database connection
 * @return array Result from query or false on error
 */
function getAllActiveMaterials($con) {
	$query = "SELECT * FROM mms_material_catalogue WHERE MMC_STATUS = 'A' ORDER BY MMC_CAT_CODE";
	return mysqli_query($con, $query);
}

/**
 * Get material by code
 * 
 * @param object $con Database connection
 * @param string $MaterialCode Material code
 * @return array Result from query or false on error
 */
function getMaterialByCode($con, $MaterialCode) {
	$query = "SELECT * FROM mms_material_catalogue WHERE MMC_MATERIAL_CODE = '$MaterialCode'";
	return mysqli_query($con, $query);
}
?>
