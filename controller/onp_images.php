<?php
include $_SERVER['DOCUMENT_ROOT'] . '/inc/global.inc';

header('Content-Type: application/json; charset=utf-8');

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage = 4;
$offset = ($page - 1) * $perPage;

$totalImages = $db->single("SELECT COUNT(*) FROM df_site_onp_images");
$rows = $db->query("SELECT * FROM df_site_onp_images ORDER BY prior DESC LIMIT {$offset}, {$perPage}");

$result = [];
foreach ($rows as $i => $row) {
    $result[] = [
        'index' => $offset + $i,
        'upfile_pc' => $row['upfile_pc']
    ];
}

echo json_encode([
    'result' => 'ok',
    'items'  => $result,
    'total'  => (int)$totalImages
]);
