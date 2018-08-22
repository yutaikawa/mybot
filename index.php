<?php

// Composerでインストールしたライブラリを一括読み込み
require_once __DIR__ . '/vendor/autoload.php';

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
$bot = new LINE\LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);
// 署名を取得
$signature = $_SERVER['HTTP_' . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];
// 署名のチェック
$events = $bot->parseEventRequest(file_get_contents('php://input'), $signature);

// 各イベントをループで処理
foreach ($events as $event) {

	$actionArray = [
		new \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder('詳細を見る', '詳細を見る'),
		new \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder('コーデを見る', 'コーデを見る'),
	];

	$columnArray = [
		new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder(
			'切り替えレースブラウス [C3387]',
			'1,890円（税込）',
			'https://files.lettuce.co.jp/images_set02/goods_images/goods_detail/c3387.jpg',
			[
				new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('詳細を見る', 'https://www.lettuce.co.jp/products/detail/11484')
			]
		),
		new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder(
			'袖ボリュームスキッパーシャツ [C3397]',
			'1,690円（税込）',
			'https://files.lettuce.co.jp/images_set02/goods_images/goods_detail/c3397.jpg',
			[
				new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('詳細を見る', 'https://www.lettuce.co.jp/products/detail/11413')
			]
		),
	];

	replyCarouselTemplate($bot, $event->getReplyToken(), 'おすすめ商品', $columnArray);

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

function replyCarouselImage($bot, $replyToken, $alternativeText, $imagesArray)
{
//	$imageArray = [];
//	// 画像を追加
//	foreach ($imagesArray as $image) {
//		array_push($imageArray, $image);
//	}

	$builder = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder(
		$alternativeText,
		new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder(
			$imagesArray
		)
	);
	$response = $bot->replyMessage($replyToken, $builder);
	if (!$response->isSucceeded()) {
		error_log('Failed!'. $response->getHTTPStatus . ' ' . $response->getRawBody());
	}
}

// Carouselテンプレートを返信。引数はLINEBot、返信先、代替テキスト、
// ダイアログの配列
function replyCarouselTemplate($bot, $replyToken, $alternativeText, $columnArray) {
	$builder = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder(
		$alternativeText,
		// Carouselテンプレートの引数はダイアログの配列
		new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder (
			$columnArray)
	);
	$response = $bot->replyMessage($replyToken, $builder);
	if (!$response->isSucceeded()) {
		error_log('Failed!'. $response->getHTTPStatus . ' ' . $response->getRawBody());
	}
}