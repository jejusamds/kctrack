<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Madmin/inc/top.php';

$code = $_GET['code'] ?? '';
if ($code !== 'aboutus' && $code !== 'etc') {
    error('잘못된 접근입니다.');
}

$dir_name = "images";
$this_table = $code === 'aboutus' ? 'df_site_aboutus_images' : 'df_site_onp_images';
$page_name = $code === 'aboutus' ? '회사소개' : '기타 하부주행 부품';

// 페이징 없이 전체 조회
$list = $db->query("SELECT * FROM {$this_table} ORDER BY prior DESC");
?>
<script>
    function onSelectAll(allChk) {
        document.querySelectorAll('.select_checkbox').forEach(ch => ch.checked = allChk.checked);
    }
    function deleteEntries() {
        var arr = []; document.querySelectorAll('.select_checkbox:checked').forEach(ch => arr.push(ch.value));
        if (arr.length === 0) { alert('삭제할 항목을 선택하세요.'); return; }
        if (confirm('선택한 항목을 삭제하시겠습니까?')) {
            location.href = '/Madmin/<?=$dir_name?>/<?=$dir_name?>_save.php?code=<?=$code?>&mode=delete&selidx=' + arr.join('|');
        }
    }
</script>
<div class="pageWrap">
    <div class="page-heading">
        <h3><?=$page_name?> 이미지 관리</h3>
        <ul class="breadcrumb">
            <li>이미지 관리</li>
            <li class="active">목록</li>
        </ul>
    </div>
    <div class="box comMTop20" style="width:1114px;">
        <div class="panel">
            <table class="table" cellpadding="0" cellspacing="0">
                <colgroup>
                    <col width="40" />
                    <col width="60" />
                    <col width="100" />
                    <col width="200" />
                    <col width="200" />
                    <col width="80" />
                    <col width="120" />
                    <col width="60" />
                </colgroup>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select_all" onclick="onSelectAll(this)"></th>
                        <th>번호</th>
                        <th>관리용 이름</th>
                        <th>PC 이미지</th>
                        <th>모바일 이미지</th>
                        <th>순서</th>
                        <th>작성일</th>
                        <th>수정</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($list) > 0):
                        foreach ($list as $i => $row): ?>
                            <tr>
                                <td><input type="checkbox" class="select_checkbox" value="<?= $row['idx'] ?>"></td>
                                <td><?= count($list) - $i ?></td>
                                <td class="comALeft">
                                    <?= htmlspecialchars($row['f_title'] ?? '') ?>
                                </td>
                                <td class="comACenter">
                                    <?php if ($row['upfile_pc']): ?>
                                        <img src="/userfiles/<?=$dir_name?>/<?= $row['upfile_pc'] ?>" style="height:50px;">
                                    <?php endif; ?>
                                </td>
                                <td class="comACenter">
                                    <?php if ($row['upfile_mobile']): ?>
                                        <img src="/userfiles/<?=$dir_name?>/<?= $row['upfile_mobile'] ?>" style="height:50px;">
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <ul style="width:40px;margin:0 auto;padding:0;list-style:none;">
                                        <li style="float:left;width:20px;height:12px;text-align:center;">
                                            <a
                                                href="<?=$dir_name?>_save.php?code=<?=$code?>&mode=prior&posi=upup&idx=<?= $row['idx'] ?>&prior=<?= $row['prior'] ?>">
                                                <img src="../img/upup_icon.gif" border="0" alt="10단계 위로">
                                            </a>
                                        </li>
                                        <li style="float:left;width:20px;height:12px;text-align:center;"></li>
                                        <li style="float:left;width:20px;height:12px;text-align:center;">
                                            <a
                                                href="<?=$dir_name?>_save.php?code=<?=$code?>&mode=prior&posi=up&idx=<?= $row['idx'] ?>&prior=<?= $row['prior'] ?>">
                                                <img src="../img/up_icon.gif" border="0" alt="1단계 위로">
                                            </a>
                                        </li>
                                        <li style="float:left;width:20px;height:12px;text-align:center;">
                                            <a
                                                href="<?=$dir_name?>_save.php?code=<?=$code?>&mode=prior&posi=down&idx=<?= $row['idx'] ?>&prior=<?= $row['prior'] ?>">
                                                <img src="../img/down_icon.gif" border="0" alt="1단계 아래로">
                                            </a>
                                        </li>
                                        <li style="float:left;width:20px;height:12px;text-align:center;"></li>
                                        <li style="float:left;width:20px;height:12px;text-align:center;">
                                            <a
                                                href="<?=$dir_name?>_save.php?code=<?=$code?>&mode=prior&posi=downdown&idx=<?= $row['idx'] ?>&prior=<?= $row['prior'] ?>">
                                                <img src="../img/downdown_icon.gif" border="0" alt="10단계 아래로">
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="clear"></div>
                                </td>
                                <td><?= substr($row['wdate'], 0, 10) ?></td>
                                <td>
                                    <button class="btn btn-success btn-sm"
                                        onclick="location.href='<?=$dir_name?>_input.php?idx=<?= $row['idx'] ?>&code=<?=$code?>';">수정</button>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="7" height="50" class="comACenter">등록된 데이터가 없습니다.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="box comMTop20 comMBottom20" style="width:1114px;">
        <div class="comPTop20 comPBottom20">
            <div class="comFLeft comALeft" style="width:10%; padding-left:10px;">
                <button class="btn btn-danger btn-sm" type="button" onclick="deleteEntries();">삭제</button>
            </div>
            <div class="comFRight comARight" style="width:15%; padding-right:10px;">
                <button class="btn btn-default btn-sm" type="button"
                    onclick="location.href='<?=$dir_name?>_input.php?code=<?=$code?>';">등록</button>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
</body>

</html>