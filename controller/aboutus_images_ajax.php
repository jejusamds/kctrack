<?php
header('Content-Type: application/json; charset=UTF-8');
include_once $_SERVER['DOCUMENT_ROOT'].'/inc/global.inc';

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage = 6;
$offset = ($page - 1) * $perPage;

$total = (int)$db->single("SELECT COUNT(*) FROM df_site_aboutus_images");
$rows = $db->query("SELECT * FROM df_site_aboutus_images ORDER BY prior DESC LIMIT {$offset}, {$perPage}");

$data = [];
foreach ($rows as $row) {
    $data[] = [
        'upfile_pc' => $row['upfile_pc'],
        'title' => $row['f_title'] ?? ''
    ];
}

echo json_encode([
    'total' => $total,
    'per_page' => $perPage,
    'page' => $page,
    'images' => $data
], JSON_UNESCAPED_UNICODE);
