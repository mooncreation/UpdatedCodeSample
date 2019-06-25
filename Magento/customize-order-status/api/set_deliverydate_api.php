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
$url = 'http://eatfully.com/index.php/rest/V1/orders/'.$_POST['id'];
 
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

// IF SUCCESS THEN DISPLAY STATUSES OTHERWISE PRINT MESSAGE FOR UNAUTHORIZED.
if($result->message == ''){
	if($_POST['expected_delivery_date'] != '' || $_POST['actual_delivery_date'] != ''){
		$expected = '';
		if($_POST['expected_delivery_date'] != ''){
			$expected .= "`expected_delivery_date` = '".$_POST['expected_delivery_date']."'";
		}
		$actual = '';
		if($_POST['actual_delivery_date'] != ''){
			$actual .= "`actual_delivery_date` = '".$_POST['actual_delivery_date']."'";
		}
		$comma = '';
		if($_POST['expected_delivery_date'] != '' && $_POST['actual_delivery_date'] != ''){
			$comma .= ',';
		}
		$set_deliverydate_sql = mysqli_query($con, "UPDATE `mgwl_sales_order` SET ".$expected.$comma.$actual." where entity_id='".$_POST['id']."'");
		echo "Date updated successfully.";
	} else {
		echo "Please provide appropriate data.";
	}
} else {
	echo "you are not authorized to do this operation.";
}
?>