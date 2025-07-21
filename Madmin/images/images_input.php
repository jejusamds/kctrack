<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Madmin/inc/top.php';

$code = $_GET['code'] ?? '';
if ($code !== 'aboutus' && $code !== 'etc') {
    error('잘못된 접근입니다.');
}

$dir_name = "images";
$this_table = $code === 'aboutus' ? 'df_site_aboutus_images' : 'df_site_onp_images';
$page_name = $code === 'aboutus' ? '회사소개' : '기타 하부주행 부품';

$idx = isset($_GET['idx']) ? (int) $_GET['idx'] : 0;
$mode = 'insert';
$row = [
    'upfile_pc' => '',
    'upfile_mobile' => '',
    'f_title' => '',
];
if ($idx) {
    $row = $db->row("SELECT * FROM {$this_table} WHERE idx=:idx", ['idx' => $idx]);
    if (!$row) {
        echo "<script>alert('잘못된 접근입니다.');location.href='{$dir_name}_list.php?code={$code}';</script>";
        exit;
    }
    $mode = 'update';
}
?>
<div class="pageWrap">
    <div class="page-heading">
        <h3><?= $page_name ?> <?= $mode === 'insert' ? '등록' : '수정' ?></h3>
        <ul class="breadcrumb">
            <li>메인 관리</li>
            <li class="active">메인 슬라이드 <?= $mode === 'insert' ? '등록' : '수정' ?></li>
        </ul>
    </div>
    <form action="/Madmin/<?= $dir_name ?>/<?= $dir_name ?>_save.php" method="post" enctype="multipart/form-data"
        onsubmit="return confirm('저장하시겠습니까?');">
        <input type="hidden" name="mode" value="<?= $mode ?>">
        <input type="hidden" name="code" value="<?= $code ?>">
        <input type="hidden" name="idx" value="<?= $idx ?>">
        <div class="box" style="width:978px;">
            <div class="panel">
                <table class="table orderInfo" cellpadding="0" cellspacing="0">
                    <col width="20%">
                    <col width="80%">
                    <tr>
                        <th>관리용 이름</th>
                        <td class="comALeft">
                            <input type="text" name="f_title" class="form-control" value="<?= htmlspecialchars($row['f_title'] ?? '') ?>"
                                style="width:60%;" placeholder="관리용 이름을 입력하세요.">

                        </td>
                    </tr>
                    <tr>
                        <th>PC ( * )</th>
                        <td class="comALeft">
                            <input type="file" name="upfile_pc" class="form-control" style="width:60%;">
                            <?php if ($mode == 'update' && $row['upfile_pc']): ?>
                                <a href="/userfiles/<?= $dir_name ?>/<?= $row['upfile_pc'] ?>"
                                    target="_blank"><?= $row['upfile_pc'] ?></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Mobile ( * )</th>
                        <td class="comALeft">
                            <input type="file" name="upfile_mobile" class="form-control" style="width:60%;">
                            <?php if ($mode == 'update' && $row['upfile_mobile']): ?>
                                <a href="/userfiles/<?= $dir_name ?>/<?= $row['upfile_mobile'] ?>"
                                    target="_blank"><?= $row['upfile_mobile'] ?></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="box comMTop10 comMBottom20" style="width:978px;">
            <div class="comPTop10 comPBottom10">
                <div class="comFLeft comACenter" style="width:10%;">
                    <button class="btn btn-primary btn-sm" type="button"
                        onclick="location.href='<?= $dir_name ?>_list.php?code=<?=$code?>';">목록</button>
                </div>
                <div class="comFRight comARight" style="width:85%; padding-right:20px;">
                    <button class="btn btn-info btn-sm" type="submit"><?= $mode === 'insert' ? '등록' : '저장' ?></button>
                    <?php if ($mode == 'update'): ?>
                        <button class="btn btn-danger btn-sm" type="button"
                            onclick="if(confirm('삭제하시겠습니까?'))location.href='<?= $dir_name ?>_save.php?code=<?=$code?>&mode=delete&selidx=<?= $idx ?>';">삭제</button>
                    <?php endif; ?>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </form>
</div>
</body>

</html>