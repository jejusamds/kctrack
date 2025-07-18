<?php
// info.php

header('Content-Type: text/plain; charset=utf-8');
echo get_cfg_var('disable_functions');


echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";

// 1) PHP 버전
echo 'PHP Version: ' . PHP_VERSION . "<br>";

// 2) 서버 API (CLI, FPM, Apache 모듈 등)
echo 'Server API: ' . php_sapi_name() . "<br>";

// 3) OS 정보
echo 'Operating System: ' . PHP_OS . "<br>";

// 4) 로드된 확장 모듈 목록
echo 'Loaded Extensions:<br>';
echo implode(', ', get_loaded_extensions()) . "<br>";

// 5) 주요 ini 설정값
$keys = ['memory_limit','upload_max_filesize','post_max_size','max_execution_time'];
foreach($keys as $k){
    echo "$k = " . ini_get($k) . "<br>";
}
