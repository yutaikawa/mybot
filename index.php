<?php

// Composerでインストールしたライブラリを一括読み込み
require_once __DIR__ . '/vendor/autoload.php';

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
$bot = new LINE\LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);
// 署名を取得
$signature = $_SERVER['HTTP_' . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];
// 署名のチェック
$events = $bot->parseEventRequest(file_get_contents('php://input'), $signature);

// 画像を保存する配列
$image_array = [
//	'type' => 'image_carousel',
	'columns' => [
		[
			'imageUrl' => 'https://images-na.ssl-images-amazon.com/images/I/51uci%2BizOLL.jpg',
			'action' => [
				'type' => 'postback',
				'label' => 'Buy',
				'data' => 'action=buy&itemid=111'
			]
		],
		[
			'imageUrl' => 'https://i.pinimg.com/736x/25/d8/06/25d8066d88186212920e775d9a7140bb--popart-google-search.jpg',
			'action' => [
				'type' => 'postback',
				'label' => 'Buy',
				'data' => 'action=buy&itemid=111'
			]
		],
		[
			'imageUrl' => 'https://d2jv9003bew7ag.cloudfront.net/uploads/Andy-Warhol-Elizabeth-Taylor-Liz-number-5.jpg',
			'action' => [
				'type' => 'postback',
				'label' => 'Buy',
				'data' => 'action=buy&itemid=111'
			]
		],
	]
];

$image_array = [
	'https://images-na.ssl-images-amazon.com/images/I/51uci%2BizOLL.jpg',
	'https://i.pinimg.com/736x/25/d8/06/25d8066d88186212920e775d9a7140bb--popart-google-search.jpg',
	'https://d2jv9003bew7ag.cloudfront.net/uploads/Andy-Warhol-Elizabeth-Taylor-Liz-number-5.jpg',
];

$columnArray = [];
$action_array = [];
array_push($action_array, new \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder(''));
$column1 = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder(
	'test',
	'test text',
	'https://images-na.ssl-images-amazon.com/images/I/51uci%2BizOLL.jpg',
	$action_array
);
array_push($columnArray, $column1);
$column2 = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder(
	'tes2',
	'test tex2',
	'https://i.pinimg.com/736x/25/d8/06/25d8066d88186212920e775d9a7140bb--popart-google-search.jpg',
	$action_array
);
array_push($columnArray, $column2);

// 各イベントをループで処理
foreach ($events as $event) {
//	$user_text = $event->getText();
//	$search_word = '住所';
//
//	if (mb_strpos($user_text, $search_word) !== false) {
//		// Confirmメッセージを返信
//		replyLocationMessage(
//			$bot,
//			$event->getReplyToken(),
//			'maxim',
//			'兵庫県神戸市中央区磯辺通３丁目２−１１ 8F 三宮ファーストビル',
//			34.689580,
//			135.198358
//		);
//	}



	replyCarouselImage(
		$bot,
		$event->getReplyToken(),
		'maxim image',
		$columnArray
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