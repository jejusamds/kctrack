<?php
include $_SERVER['DOCUMENT_ROOT'] . '/inc/global.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/inc/util_lib.inc';

header('Content-Type: application/json; charset=utf-8');

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage = 8;
$offset = ($page - 1) * $perPage;

$total = $db->single("SELECT COUNT(*) FROM df_site_aboutus_images");
$images = $db->query("SELECT * FROM df_site_aboutus_images ORDER BY prior DESC LIMIT :offset, :limit", ['offset' => $offset, 'limit' => $perPage]);

ob_start();
foreach ($images as $i => $img) {
    ?>
    <li>
        <a href="javascript:aboutus_sub01_info03_popup(<?php echo $i; ?>);">
            <div class="img_con" style="background-image:url('/userfiles/images/<?php echo $img['upfile_pc']; ?>');">
                <img src="/img/aboutus/aboutus_sub01_info03_list_img_con_blank_img.png" alt="블랭크 이미지" class="fx" />
            </div>
        </a>
    </li>
    <?php
}
$html = ob_get_clean();

$totalPage = ceil($total / $perPage);

echo json_encode(['html' => $html, 'total_page' => $totalPage]);
