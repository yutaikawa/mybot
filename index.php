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
	// Confirmメッセージを返信
	replyLocationMessage(
		$bot,
		$event->getReplyToken(),
		'maxim',
		'兵庫県神戸市中央区磯辺通３丁目２−１１ 8F 三宮ファーストビル',
		34.689580,
		135.198358
	);
}

// テキストを返信。引数はLINEBot,返信先,テキスト
function replyTextMessage($bot, $replyToken, $text)
{
	$response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($text));

	// レスポンスが異常な場合
	if (!$response->isSucceeded()) {
		// エラーを出力
		error_log('Failed! ' . $response->getHTTPStatus . ' ' . $response->getRawBody());
	}
}

// 画像を返信
function replyImageMessage($bot, $replyToken, $originalImageUrl, $previewImageUrl)
{
	$response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\ImageMessageBuilder($originalImageUrl, $previewImageUrl));
	if (!$response->isSucceeded()) {
		// エラーを出力
		error_log('Failed! ' . $response->getHTTPStatus . ' ' . $response->getRawBody());
	}
}

// Bttonテンプレートを返信
function replyButtonsTemplate($bot, $replyToken, $alternativeText, $imageUrl, $title, $text, ...$actions)
{
	$actionArray = [];
	// アクションを追加
	foreach ($actions as $action) {
		array_push($actionArray, $action);
	}

	$builder = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder(
		$alternativeText,
		new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder($title, $text, $imageUrl, $actionArray)
	);
	$response = $bot->replyMessage($replyToken, $builder);
	if (!$response->isSucceeded()) {
		// エラーを出力
		error_log('Failed! ' . $response->getHTTPStatus . ' ' . $response->getRawBody());
	}
}

// 位置情報を返信
function replyLocationMessage($bot, $replyToken, $title, $address, $lat, $lon)
{
	$response = $bot->replyMessage($replyToken, new LINE\LINEBot\MessageBuilder\LocationMessageBuilder($title, $address, $lat, $lon));
	if (!$response->isSucceeded()) {
		error_log('Failed!'. $response->getHTTPStatus . ' ' . $response->getRawBody());
	}
}

// Confirmテンプレートを返信
function replyConfirmTemplate($bot, $replyToken, $alternativeText, $text, ...$actions)
{
	$actionArray = [];
	// アクションを追加
	foreach ($actions as $action) {
		array_push($actionArray, $action);
	}

	$builder = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder(
		$alternativeText,
		new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder($text, $actionArray)
	);
	$response = $bot->replyMessage($replyToken, $builder);
	if (!$response->isSucceeded()) {
		// エラーを出力
		error_log('Failed! ' . $response->getHTTPStatus . ' ' . $response->getRawBody());
	}
}