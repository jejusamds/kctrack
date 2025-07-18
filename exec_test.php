<?php
// check_exec.php
header('Content-Type: text/plain; charset=utf-8');

// 1) disable_functions 리스트 확인
echo "disable_functions = " . ini_get('disable_functions') . "\n";

// 2) 함수 존재 & 호출 가능 여부
echo "function_exists('exec') = " . (function_exists('exec') ? 'true' : 'false') . "\n";
echo "is_callable('exec')     = " . (is_callable('exec')    ? 'true' : 'false') . "\n";

// 3) 실제 echo 명령 실행 테스트
$output = [];
$returnVar = null;
exec('echo HELLO_WORLD', $output, $returnVar);
echo "exec('echo HELLO_WORLD') → output = [" . implode(',', $output) . "], returnVar = {$returnVar}\n";

// 4) (선택) suhosin 확장으로 차단된 함수 확인
if (extension_loaded('suhosin')) {
    echo "suhosin.executor.func.blacklist = " . ini_get('suhosin.executor.func.blacklist') . "\n";
}
