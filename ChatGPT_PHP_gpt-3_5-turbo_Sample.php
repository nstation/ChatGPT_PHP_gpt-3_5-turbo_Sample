<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ChatGPT API Chatサンプル</title>
</head>
<body>

<?php
//
// OpenAI のアカウントを作成 ( https://beta.openai.com/signup )
// API Key を発行 ( https://beta.openai.com/account/api-keys )
//
$API_KEY = '取得したAPIキーを入力';

$messages[] = [
	'role'		=> 'system',
	'content'	=> '日本語で回答。参考URLを表示して下さい。このタスクで最高の結果をだすために、もっと情報が必要な場合は、ドンドン質問をしてください。'
];

// 最初の質問

$message = '人気YouTuberになるには';
echo "<h2>$message</h2>";
post_params($message);

// 次の質問
$message = '更新頻度はどの位が良い？';
echo "<h2>$message</h2>";
post_params($message);


function post_params($message)
{
	global $API_KEY;
	global $messages;

	$curl = curl_init('https://api.openai.com/v1/chat/completions');

	$header = array(
		'Authorization: Bearer '.$API_KEY,
		'Content-type: application/json',
	);

	$messages[] = [
		'role'		=> 'user',
		'content'	=>	$message
	];
	$params =  [
		'model'		=> 'gpt-3.5-turbo',
		'messages'	=>	$messages
	];

	$options = array(
		CURLOPT_POST => true,
		CURLOPT_HTTPHEADER =>$header,
		CURLOPT_POSTFIELDS => json_encode($params,JSON_UNESCAPED_UNICODE),
		CURLOPT_RETURNTRANSFER => true,
	);
	curl_setopt_array($curl, $options);
	$response = curl_exec($curl);

	$json_array = json_decode($response, true);

	$choices = $json_array['choices'];

	echo '<p>';
	foreach($choices as $v){
		$messages[] = $v['message'];
		echo nl2br($v['message']['content']).'<br />';
	}
	echo '</p>';

	return;
}
?>
</body>
</html>