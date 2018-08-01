<?php

// ライブラリを読み込み
require_once(__DIR__ . '/../line-bot-sdk-php/vendor/autoload.php');

// POSTメソッドで渡される値の取得
$inputString = file_get_contents('php://input');
error_log($inputString);
