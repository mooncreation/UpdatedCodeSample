<?php
// MAKE A CONNECTION WITH DATABASE
$con = mysqli_connect('HOST','USER_NAME','PASSWORD') or die(mysql_error()); // DB connection
$db =  mysqli_select_db($con, 'DATABASE_NAME');

function sign($method, $url, $data, $consumerSecret, $tokenSecret)
{
	$url = urlEncodeAsZend($url); 
	$data = urlEncodeAsZend(http_build_query($data, '', '&'));
	$data = implode('&', [$method, $url, $data]); 
	$secret = implode('&', [$consumerSecret, $tokenSecret]); 
	return base64_encode(hash_hmac('sha1', $data, $secret, true));
}
 
function urlEncodeAsZend($value)
{
	$encoded = rawurlencode($value);
	$encoded = str_replace('%7E', '~', $encoded);
	return $encoded;
}

// REPLACE WITH YOUR ACTUAL DATA OBTAINED WHILE CREATING NEW INTEGRATION
$consumerKey = $_POST['consumerKey'];
$consumerSecret = $_POST['consumerSecret'];
$accessToken = $_POST['accessToken'];
$accessTokenSecret = $_POST['accessTokenSecret'];
 
$method = 'GET';
$url = 'http://example.com/index.php/rest/V1/orders/'.$_POST['id'];
 
$data = [
	'oauth_consumer_key' => $consumerKey,
	'oauth_nonce' => md5(uniqid(rand(), true)),
	'oauth_signature_method' => 'HMAC-SHA1',
	'oauth_timestamp' => time(),
	'oauth_token' => $accessToken,
	'oauth_version' => '1.0',
];
 
$data['oauth_signature'] = sign($method, $url, $data, $consumerSecret, $accessTokenSecret);
 
$curl = curl_init();
 
curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $url,
	CURLOPT_HTTPHEADER => [
		'Authorization: OAuth ' . http_build_query($data, '', ',')
	]
]);
 
$result = curl_exec($curl);
curl_close($curl);
$result = json_decode($result);


// IF SUCCESS THEN DISPLAY RESULTS OTHERWISE PRINT MESSAGE FOR UNAUTHORIZED.
if($result->message == ''){
	//QUERY TO SELECT EXPECTED AND ACTUAL DELIVERY DATE
	$sql = mysqli_query($con, "select expected_delivery_date,actual_delivery_date from mgwl_sales_order where entity_id='".$_POST['id']."'");
	$delivery_date = mysqli_fetch_array($sql);
	$expected_delivery_date = $delivery_date['expected_delivery_date'];
	$actual_delivery_date = $delivery_date['actual_delivery_date'];

	$delivery_dates['expected_delivery_date'] = $delivery_date['expected_delivery_date'];
	$delivery_dates['actual_delivery_date'] = $delivery_date['actual_delivery_date'];
	$delivery_dates = json_encode($delivery_dates);
	print_r($delivery_dates);
} else {
	echo "you are not authorized to do this operation.";
}
?>
