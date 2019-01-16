<?php

// Composerでインストールしたライブラリを一括読み込み
require_once __DIR__ . '/vendor/autoload.php';

use \LINE\LINEBot;
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot\Constant\HTTPHeader;

use \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;

use \LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;

use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;

use \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;

use \LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use \LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use \LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use \LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder;

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
$bot = new LINE\LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);
// 署名を取得
$signature = $_SERVER['HTTP_' . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];
// 署名のチェック
$events = $bot->parseEventRequest(file_get_contents('php://input'), $signature);


/* ImageMapサンプル */
$lineBot = new LINE\LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);
// カルーセル生成
$columns = array(
	new CarouselColumnTemplateBuilder("カットショルダーブラウス(全3色) [422C]","2,990円","https://files.isnt-she.com/images_set02/goods_images/goods_detail/422c_1.jpg",[new UriTemplateActionBuilder('商品詳細',"https://www.isnt-she.com/products/detail/465")]),
	new CarouselColumnTemplateBuilder("ハイネッククロシェビキニ(全2色) [163S]","6,990円","https://files.isnt-she.com/images_set02/goods_images/goods_detail/163s_1.jpg",[new UriTemplateActionBuilder('商品詳細',"https://www.isnt-she.com/products/detail/449")]),
	new CarouselColumnTemplateBuilder("ハーフスリーブ刺繍レトロブラウス(全2色)[212C]","6,990円","https://files.isnt-she.com/images_set02/goods_images/goods_detail/212c_1.jpg",[new UriTemplateActionBuilder('商品詳細',"https://www.isnt-she.com/products/detail/289")]),
	new CarouselColumnTemplateBuilder("ウエストラッピングフレアスカート(全3色)[389M]","5,990円","https://files.isnt-she.com/images_set02/goods_images/goods_detail/389m_1.jpg",[new UriTemplateActionBuilder('商品詳細',"https://www.isnt-she.com/products/detail/531")]),
);
$carousel = new CarouselTemplateBuilder($columns);
$carousel_message = new TemplateMessageBuilder("ランキング",$carousel);

// イメージマップ生成
$imagemap = new ImagemapMessageBuilder(
	'https://ars-linebot-php-test.herokuapp.com/images/',
	'ImageMap',
	new BaseSizeBuilder(680,1040),
	array(
		new ImagemapMessageActionBuilder('a',new AreaBuilder(10,10,320,320)),
		new ImagemapMessageActionBuilder('b',new AreaBuilder(360,10,320,320)),
		new ImagemapMessageActionBuilder('c',new AreaBuilder(710,10,320,320)),
		new ImagemapMessageActionBuilder('d',new AreaBuilder(10,330,320,320)),
		new ImagemapMessageActionBuilder('e',new AreaBuilder(360,330,320,320)),
		new ImagemapMessageActionBuilder('f',new AreaBuilder(710,330,320,320)),
	)
);

// 大量にメッセージが送られると複数分のデータが同時に送られてくるため、foreachをしている。
foreach($events as $event)
{
	$msg = $event->getText();
	if( !empty( $msg ) )
	{
		switch( $msg )
		{
			case 'ランキング':
				{
					$message = new MultiMessageBuilder();
					$message->add($carousel_message);
					$lineBot->replyMessage($event->getReplyToken(), $message);
				}
				break;

			case 'a':
			case 'b':
			case 'c':
			case 'd':
			case 'e':
			case 'f':
				{
					$lineBot->replyMessage($event->getReplyToken(), new TextMessageBuilder('ボタンを押しましたね'));
				}
				break;

			default:
				{
					$lineBot->replyMessage($event->getReplyToken(), $imagemap);
				}
				break;
		}
	}
}

//// 各イベントをループで処理
//foreach ($events as $event) {
//
//	$actionArray = [
//		new \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder('詳細を見る', '詳細を見る'),
//		new \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder('コーデを見る', 'コーデを見る'),
//	];
//
//	$columnArray = [
//		new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder(
//			'選べる4TYPE★５分袖リブニット[C3147]',
//			'1,590 円(税込) ',
//			'https://files.lettuce.co.jp/images_set02/goods_images/goods_detail/c3147.jpg',
//			[
//				new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('詳細を見る', 'https://www.lettuce.co.jp/products/detail/10729'),
//				new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('コーデを見る', 'https://www.lettuce.co.jp/coordinate/list?word=C3147&height=&name=&layer=1&sort=1'),
//			]
//		),
//		new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder(
//			'【2018AW】選べるTブラウス [C3441]',
//			'1,690円（税込）',
//			'https://files.lettuce.co.jp/images_set02/goods_images/goods_detail/c3441.jpg',
//			[
//				new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('詳細を見る', 'https://www.lettuce.co.jp/products/detail/11492'),
//				new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('コーデを見る', 'https://www.lettuce.co.jp/coordinate/list?word=C3441&height=&name=&layer=1&sort=1'),
//			]
//		),
//	];
//
//	replyCarouselTemplate($bot, $event->getReplyToken(), 'おすすめ商品', $columnArray);
//
//	/*
//	replyButtonsTemplate(
//		$bot,
//		$event->getReplyToken(),
//		'お天気お知らせ - 天気予報',
//		'https://' . $_SERVER['HTTP_HOST'] . '/imgs/template.jpg',
//		'天気お知らせ',
//		'今日は天気予報は晴れです',
//		new LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder(
//			'明日の天気', 'tomorrow'
//		),
//		new LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder(
//			'週末の天気', 'weekend'
//		),
//		new LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder(
//			'webで見る', 'http://google.jp'
//		)
//	);
//	*/
//
//}

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