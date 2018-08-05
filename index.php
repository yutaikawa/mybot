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
	// Buttonテンプレートメッセージを返信
	replyButtonsTemplate(
		$bot,
		$event->getReplyToken(),
		'お天気お知らせ - 今日は天気予報は晴れです',
		'https://' . $_SERVER['HTTP_HOST'] . '/imgs/template.jpg',
		'お天気お知らせ',
		'今日は天気予報は晴れです',
		new LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder(
			'明日の天気',
			'tomorrow'
		),
		new LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder(
			'週末の天気',
			'weekend'
		),
		new LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder(
			'webで見る',
			'http://google.jp'
		)
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