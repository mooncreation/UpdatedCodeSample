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

// IF SUCCESS THEN DISPLAY STATUSES OTHERWISE PRINT MESSAGE FOR UNAUTHORIZED.
if($result->message == ''){
	$status = $result->status;
	$payment_sql = mysqli_query($con, "select o.quote_id, q.entity_id, qp.method from mgwl_sales_order as o INNER JOIN mgwl_quote as q ON o.quote_id=q.entity_id INNER JOIN mgwl_quote_payment as qp ON q.entity_id=qp.quote_id where o.entity_id='".$_POST['id']."'");
	$payment_method = mysqli_fetch_array($payment_sql);
	$payment_method = $payment_method['method'];

	$status_sql = mysqli_query($con, "select status from mgwl_sales_order_status_history where parent_id='".$_POST['id']."'");
	$latest_status = mysqli_fetch_array($sql);
	$latest_status = $latest_status['status'];
	if($status == 'card_pending' || $status == 'pending'){
	    $statuses['accept'] = 'Accepted';
		$statuses['reject'] = 'Rejected';
	    $statuses = json_encode($statuses);
	    print_r($statuses);
	}
	if($status == 'accept'){
	    $statuses['in_kitchen'] = 'In Kitchen';
		$statuses['holded'] = 'On Hold';
	    $statuses = json_encode($statuses);
	    print_r($statuses);
	}
	if($status == 'reject'){
	    $statuses['null'] = 'null';
	    $statuses = json_encode($statuses);
	    print_r($statuses);
	}
	if($status == 'in_kitchen'){
	    $statuses['on_route'] = 'On Route';
		$statuses['holded'] = 'On Hold';
	    $statuses = json_encode($statuses);
	    print_r($statuses);
	}
	if($status == 'on_route'){
	    if($payment_method == 'cashondelivery'){
			$statuses['pending_payment'] = 'Cash Received';
		} else {
			$statuses['delivered'] = 'Delivered';
	    }
		$statuses['holded'] = 'On Hold';
	    $statuses = json_encode($statuses);
	    print_r($statuses);
	}
	if($status == 'pending_payment'){
	    $statuses['delivered'] = 'Delivered';
		$statuses['holded'] = 'On Hold';
	    $statuses = json_encode($statuses);
	    print_r($statuses);
	}
	if($status == 'canceled'){
	    $statuses['null'] = 'null';
	    $statuses = json_encode($statuses);
	    print_r($statuses);
	}
	if($status == 'delivered'){
	    $statuses['null'] = 'null';
	    $statuses = json_encode($statuses);
	    print_r($statuses);
	}
	if($status == 'holded'){
	    if($latest_status == ''){
			$statuses['pending_payment'] = 'Cash Received';
		}
	    if($latest_status == 'pending_payment'){
			$statuses['delivered'] = 'Delivered';
		}
	    if($latest_status == 'card_pending' || $latest_status == 'pending'){
			$statuses['accept'] = 'Accepted';
		}
	    if($latest_status == 'accept'){
		    $statuses['in_kitchen'] = 'In Kitchen';
		}
	    if($latest_status == 'reject'){
		    $statuses['null'] = 'null';
		}
	    if($latest_status == 'in_kitchen'){
		    $statuses['on_route'] = 'On Route';
		}
	    if($latest_status == 'on_route'){
		    if($payment_method == 'Cash On Delivery'){
				$statuses['pending_payment'] = 'Cash Received';
			} else {
				$statuses['delivered'] = 'Delivered';
			}
			$statuses['holded'] = 'On Hold';
		}
		
		$statuses['holded'] = 'On Hold';
	    $statuses = json_encode($statuses);
	    print_r($statuses);
	}
} else {
	echo "you are not authorized to do this operation.";
}
?>