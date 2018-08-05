<?php

// ライブラリを読み込み
require_once(__DIR__ . '/line-bot-sdk-php/vendor/autoload.php');

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
$bot = new LINE\LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);
// 署名を取得
$signature = $_SERVER['HTTP_' . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];
// 署名のチェック
$events = $bot->parseEventRequest(file_get_contents('php://input'), $signature);

// 各イベントをループで処理
foreach ($events as $event) {
	// テキストを返信
	$bot->replyText($event->getReplyToken(), 'TextMessage');
}
