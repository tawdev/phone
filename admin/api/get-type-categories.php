<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../classes/TypeCategory.php';

header('Content-Type: application/json');

if (!isAdminLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

if ($category_id <= 0) {
    echo json_encode([]);
    exit;
}

$typeCategory = new TypeCategory();
$types = $typeCategory->getByCategoryId($category_id);

echo json_encode($types);

