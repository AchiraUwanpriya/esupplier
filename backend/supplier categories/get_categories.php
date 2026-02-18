<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../common/db.php';
require_once __DIR__ . '/category_queries.php';

$categoryQueries = new CategoryQueries();
$categories = $categoryQueries->getAllCategories();

echo json_encode($categories);
?>