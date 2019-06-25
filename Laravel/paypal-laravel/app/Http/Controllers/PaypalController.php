<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use Config;
use View;

class PaypalController extends Controller {

    public function __construct() {
        // Get paypal config data
         $this->paypaldata = Config::get('paypal');
    }
    
    // This method call when you are at your home page
    public function index(Request $request) {       
        
        /* Create Product data array
         * Here you can execute your databse query and apply that array also
         */
       $productarray=array();
       $productarray['id']="1";
       $productarray['title']="Mobile";
       $productarray['description']="Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.";
       $productarray['price']="10";
       $productarray['image']="images/mobile.jpeg";

       //Call Product index view with Product and Paypal conf data
        return View::make('product.index', ['productarray'=> $productarray, 'paypal_conf' => $this->paypaldata]);
                        
    }
   
    // Notify url method
    public function afterPayment() {       
        /*
         * Read POST data
         * reading posted data directly from $_POST causes serialization
         * issues with array data in POST.
         * Reading raw POST data from input stream instead.
         */
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
            $keyval = explode('=', $keyval);
            if (count($keyval) == 2)
                $myPost[$keyval[0]] = urldecode($keyval[1]);
        }


      // Read the post from PayPal system and add 'cmd'
        $req = 'cmd=_notify-validate';
        if (function_exists('get_magic_quotes_gpc')) {
            $get_magic_quotes_exists = true;
        }
        foreach ($myPost as $key => $value) {
            if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
                $value = urlencode(stripslashes($value));
            } else {
                $value = urlencode($value);
            }
            $req .= "&$key=$value";
        }
        /*
         * Post IPN data back to PayPal to validate the IPN data is genuine
         * Without this step anyone can fake IPN data
         */
        $paypalURL = $this->paypaldata['paypal_url'];
        $ch = curl_init($paypalURL);
        if ($ch == FALSE) {
            return FALSE;
        }
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);

        // Set TCP timeout to 30 seconds
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close', 'User-Agent: company-name'));
        $res = curl_exec($ch);

        /*
         * Inspect IPN validation result and act accordingly
         * Split response headers and payload, a better way for strcmp
         */
        $tokens = explode("\r\n\r\n", trim($res));
        $res = trim(end($tokens));

        // Check if payment is done or not
        if (strcmp($res, "VERIFIED") == 0 || strcasecmp($res, "VERIFIED") == 0) {

            //Payment data
            $item_number = $_POST['item_number'];
            $txn_id = $_POST['txn_id'];
            $payment_gross = $_POST['mc_gross'];
            $currency_code = $_POST['mc_currency'];
            $payment_status = $_POST['payment_status'];

            /* Add/edit rcord in your order table.
             * Also you can add your logic for email
             * 
             */
        }

        // View success template file
        return view('product.success');
    }

    public function cancelPayment() {
        
        // View cancel template file
        return view('product.cancel');
    }

    public function successPayment() {
        
        // View success template file
        return view('product.success');
    }

}
