<?php
include $_SERVER['DOCUMENT_ROOT'] . '/inc/global.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/inc/util_lib.inc';

$code = $_REQUEST['code'] ?? '';
if ($code !== 'aboutus' && $code !== 'etc') {
    error('잘못된 접근입니다.');
}

$table = $code === 'aboutus' ? 'df_site_aboutus_images' : 'df_site_onp_images';
$mode = $_REQUEST['mode'] ?? '';
$page = isset($_REQUEST['page']) ? (int) $_REQUEST['page'] : 1;


switch ($mode) {
    case 'insert':
        $prior = date('ymdHis');
        $params = [
            'prior' => $prior,
        ];
        $upfile_pc = '';
        $upfile_mobile = '';
        if (!empty($_FILES['upfile_pc']['name'])) {
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/userfiles/images';
            if (!is_dir($dir))
                mkdir($dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['upfile_pc']['name'], PATHINFO_EXTENSION));
            $upfile_pc = uniqid('pc_') . '.' . $ext;
            move_uploaded_file($_FILES['upfile_pc']['tmp_name'], "$dir/$upfile_pc");
        }
        if (!empty($_FILES['upfile_mobile']['name'])) {
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/userfiles/images';
            if (!is_dir($dir))
                mkdir($dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['upfile_mobile']['name'], PATHINFO_EXTENSION));
            $upfile_mobile = uniqid('m_') . '.' . $ext;
            move_uploaded_file($_FILES['upfile_mobile']['tmp_name'], "$dir/$upfile_mobile");
        }

        $params['f_title'] = $f_title;
        $params['upfile_pc'] = $upfile_pc;
        $params['upfile_mobile'] = $upfile_mobile;

        // var_dump($params);
        // echo "INSERT INTO {$table} (f_title, upfile_pc, upfile_mobile ,prior, wdate) VALUES (:f_title, :upfile_pc, :upfile_mobile, :prior, NOW())";
        // exit;
        
        $db->query(
            "INSERT INTO {$table} (f_title, upfile_pc, upfile_mobile ,prior, wdate) VALUES (:f_title, :upfile_pc, :upfile_mobile, :prior, NOW())",
            $params
        );
        complete('등록되었습니다.', '/Madmin/images/images_list.php?code=' . $code);
        break;
    case 'update':
        $idx = (int) $_POST['idx'];
        $row = $db->row("SELECT * FROM {$table} WHERE idx=:idx", ['idx' => $idx]);
        if (!$row)
            error('잘못된 접근입니다.');
        $sets = [
            'f_title' => 'f_title=:f_title',
        ];
        $params = [
            'idx' => $idx,
            'f_title' => $_POST['f_title'] ?? '',
        ];
        if (!empty($_FILES['upfile_pc']['name'])) {
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/userfiles/images';
            if (!is_dir($dir))
                mkdir($dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['upfile_pc']['name'], PATHINFO_EXTENSION));
            $new = uniqid('pc_') . '.' . $ext;
            move_uploaded_file($_FILES['upfile_pc']['tmp_name'], "$dir/$new");
            $sets[] = 'upfile_pc=:upfile_pc';
            $params['upfile_pc'] = $new;
        }
        if (!empty($_FILES['upfile_mobile']['name'])) {
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/userfiles/images';
            if (!is_dir($dir))
                mkdir($dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['upfile_mobile']['name'], PATHINFO_EXTENSION));
            $newm = uniqid('m_') . '.' . $ext;
            move_uploaded_file($_FILES['upfile_mobile']['tmp_name'], "$dir/$newm");
            $sets[] = 'upfile_mobile=:upfile_mobile';
            $params['upfile_mobile'] = $newm;
        }

        // var_dump($params);
        // var_dump($sets);
        // exit;
        
        $sql = "UPDATE {$table} SET " . implode(',', $sets) . " WHERE idx=:idx";
        $db->query($sql, $params);
        complete('수정되었습니다.', '/Madmin/images/images_list.php?code=' . $code);
        break;
    case 'delete':
        $arr = array_filter(array_map( 'intval', explode('|', $_REQUEST['selidx'] ?? '')));
        foreach ($arr as $id) {
            $db->query("DELETE FROM {$table} WHERE idx=:id", ['id' => $id]);
        }
        complete('삭제되었습니다.', '/Madmin/images/images_list.php?code=' . $code);
        break;
    case 'prior':
        // 순서 변경 (agency 로직 차용)
        $sql = " Select wp.* From {$table} wp Where 1 = 1 ";
        // 1단계 위로
        if ($posi == 'up') {
            $sql .= " And wp.prior >= '" . $prior . "' And wp.idx != '" . $idx . "' Order by wp.prior Asc Limit 1 ";
            if ($row = $db->row($sql)) {
                $prior = $row['prior'];
                $db->query("Update {$table} Set prior='" . $prior . "' Where idx='" . $idx . "'");
                $db->query("Update {$table} Set prior=prior-1 Where prior<='" . $prior . "' And idx!='" . $idx . "'");
            }
        }
        // 10단계 위로
        else if ($posi == 'upup') {
            $sql .= " And wp.prior >= '" . $prior . "' And wp.idx != '" . $idx . "' Order by wp.prior Asc Limit 10 ";
            $row = $db->query($sql);
            $total = count($row);
            for ($i = 0; $i < count($row); $i++) {
                $prior = $row[$i]['prior'];
            }
            if ($total > 0) {
                $db->query("Update {$table} Set prior='" . $prior . "' Where idx='" . $idx . "'");
                $db->query("Update {$table} Set prior=prior-1 Where prior<='" . $prior . "' And idx!='" . $idx . "'");
            }
        }
        // 1단계 아래로
        else if ($posi == 'down') {
            $sql .= " And wp.prior <= '" . $prior . "' And wp.idx != '" . $idx . "' Order by wp.prior Desc Limit 1 ";
            if ($row = $db->row($sql)) {
                $prior = $row['prior'];
                $db->query("Update {$table} Set prior='" . $prior . "' Where idx='" . $idx . "'");
                $db->query("Update {$table} Set prior=prior+1 Where prior>='" . $prior . "' And idx!='" . $idx . "'");
            }
        }
        // 10단계 아래로
        else if ($posi == 'downdown') {
            $sql .= " And wp.prior <= '" . $prior . "' And wp.idx != '" . $idx . "' Order by wp.prior Desc Limit 10 ";
            $row = $db->query($sql);
            $total = count($row);
            for ($i = 0; $i < count($row); $i++) {
                $prior = $row[$i]['prior'];
            }
            if ($total > 0) {
                $db->query("Update {$table} Set prior='" . $prior . "' Where idx='" . $idx . "'");
                $db->query("Update {$table} Set prior=prior+1 Where prior>='" . $prior . "' And idx!='" . $idx . "'");
            }
        }
        complete('순서를 변경하였습니다.', 'images_list.php?code=' . $code . '&page=' . $page);
        break;
    default:
        error('잘못된 모드입니다.');
}