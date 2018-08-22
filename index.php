<?php

// Composerでインストールしたライブラリを一括読み込み
require_once __DIR__ . '/vendor/autoload.php';

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
$bot = new LINE\LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);
// 署名を取得
$signature = $_SERVER['HTTP_' . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];
// 署名のチェック
$events = $bot->parseEventRequest(file_get_contents('php://input'), $signature);


//$columns = [];// カルーセルに表示する項目
//foreach ($lists as $list) {
//	$action = new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('クリックしてね', 'https://www.lettuce.co.jp/products/detail/11484?top/new/photo');
//	$column = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder('切り替えレースブラウス [C3387]', '', 'https://files.lettuce.co.jp/images_set02/goods_images/goods_detail/c3387.jpg', [$action]);
//	$columns[] = $column;
//}
//
//$carousel = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder($columns);
//$carousel_message = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder('メッセージタイトル', $carousel);
//
//
//// 「はい」ボタン
//$yes_post = new PostbackTemplateActionBuilder("はい", "page={$page}");
//// 「いいえ」ボタン
//$no_post = new PostbackTemplateActionBuilder("いいえ", "page=-1");
//// Confirmテンプレートを作る
//$confirm = new ConfirmTemplateBuilder("メッセージ", [$yes_post, $no_post]);
//// Confirmメッセージを作る
//$confirm_message = new TemplateMessageBuilder("メッセージのタイトル", $confirm);
//
//
//$message = new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder();
//$message->add($carousel_message);
//$message->add($confirm_message);
//$res = $bot->replyMessage($event->getReplyToken(), $message);



//
//
//// 画像を保存する配列
//$image_array = [
////	'type' => 'image_carousel',
//	'columns' => [
//		[
//			'imageUrl' => 'https://images-na.ssl-images-amazon.com/images/I/51uci%2BizOLL.jpg',
//			'action' => [
//				'type' => 'postback',
//				'label' => 'Buy',
//				'data' => 'action=buy&itemid=111'
//			]
//		],
//		[
//			'imageUrl' => 'https://i.pinimg.com/736x/25/d8/06/25d8066d88186212920e775d9a7140bb--popart-google-search.jpg',
//			'action' => [
//				'type' => 'postback',
//				'label' => 'Buy',
//				'data' => 'action=buy&itemid=111'
//			]
//		],
//		[
//			'imageUrl' => 'https://d2jv9003bew7ag.cloudfront.net/uploads/Andy-Warhol-Elizabeth-Taylor-Liz-number-5.jpg',
//			'action' => [
//				'type' => 'postback',
//				'label' => 'Buy',
//				'data' => 'action=buy&itemid=111'
//			]
//		],
//	]
//];
//
//$image_array = [
//	'https://images-na.ssl-images-amazon.com/images/I/51uci%2BizOLL.jpg',
//	'https://i.pinimg.com/736x/25/d8/06/25d8066d88186212920e775d9a7140bb--popart-google-search.jpg',
//	'https://d2jv9003bew7ag.cloudfront.net/uploads/Andy-Warhol-Elizabeth-Taylor-Liz-number-5.jpg',
//];
//
//$columnArray = [];
//$action_array = [];
//array_push($action_array, new \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder(''));
//$column1 = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder(
//	'test',
//	'test text',
//	'https://images-na.ssl-images-amazon.com/images/I/51uci%2BizOLL.jpg',
//	$action_array
//);
//array_push($columnArray, $column1);
//$column2 = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder(
//	'tes2',
//	'test tex2',
//	'https://i.pinimg.com/736x/25/d8/06/25d8066d88186212920e775d9a7140bb--popart-google-search.jpg',
//	$action_array
//);
//array_push($columnArray, $column2);

// 各イベントをループで処理
foreach ($events as $event) {

	$columnArray = [];
	$actionArray = [];
	for ($i = 0; $i < 5; $i++) {
		$actionArray = [];
		array_push($actionArray, new \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder(
			'ボタン'.$i.'-'. 1, 'c-'.$i.'-'. 1));
		array_push($actionArray, new \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder(
			'ボタン'.$i.'-'. 2, 'c-'.$i.'-'. 2));
		array_push($actionArray, new \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder(
			'ボタン'.$i.'-'. 3, 'c-'.$i.'-'. 3));

		$column = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder(
			($i+1).'日後の天気',
			'はれ',
			'https://'.$_SERVER['HTTP_HOST'].'/imgs/template.jpg',
			$actionArray
		);
		array_push($columnArray, $column);

		$actionArray = [
			new \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder(
				''
			),
			new \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder(
				''
			),
		];

		$columnArray = [
			new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder(
				'切り替えレースブラウス [C3387]',
				'1,890円（税込）',
				'https://files.lettuce.co.jp/images_set02/goods_images/goods_detail/c3387.jpg',
				$actionArray
			),
			new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder(
				'袖ボリュームスキッパーシャツ [C3397]',
				'1,690円（税込）',
				'https://files.lettuce.co.jp/images_set02/goods_images/goods_detail/c3397.jpg',
				$actionArray
			),
		];


	}
	replyCarouselTemplate($bot, $event->getReplyToken(), '今後の天気', $columnArray);



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