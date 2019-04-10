<?php
class PaypalHelper {
	private static $instance = null;
    private static $config = null;

	public static function getInstance(){
		if(!self::$instance){
			Son::loadFile("ext.paypal.PaypalHelper",true,true);
			$config = Util::param2("accounts","paypal");
            require __DIR__  . '/autoload.php';
            $apiContext = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential(
                    $config["client_id"],     // ClientID
                    $config["secret"]      // ClientSecret
                )
            );
            self::$config = $config;
			self::$instance = $apiContext;
		}
		return self::$instance;
	}

    public static function verifyIPN(){
        // CONFIG: Enable debug mode. This means we'll log requests into 'ipn.log' in the same directory.
        // Especially useful if you encounter network errors or other intermittent problems with IPN (validation).
        // Set this to 0 once you go live or don't require logging.
        self::getInstance();

        define("DEBUG", self::$config["debug"] ? 1 : 0);

        // Set to 0 once you're ready to go live
        define("USE_SANDBOX", self::$config["debug"]);


        define("LOG_FILE", "./logs/ipn.log");


        // Read POST data
        // reading posted data directly from $_POST causes serialization
        // issues with array data in POST. Reading raw POST data from input stream instead.
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
            $keyval = explode ('=', $keyval);
            if (count($keyval) == 2)
                $myPost[$keyval[0]] = urldecode($keyval[1]);
        }
        // read the post from PayPal system and add 'cmd'
        $req = 'cmd=_notify-validate';
        if(function_exists('get_magic_quotes_gpc')) {
            $get_magic_quotes_exists = true;
        }
        foreach ($myPost as $key => $value) {
            if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
                $value = urlencode(stripslashes($value));
            } else {
                $value = urlencode($value);
            }
            $req .= "&$key=$value";
        }

        // Post IPN data back to PayPal to validate the IPN data is genuine
        // Without this step anyone can fake IPN data

        //ipnpb

        if(USE_SANDBOX == true) {
            $paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
        } else {
            $paypal_url = "https://www.paypal.com/cgi-bin/webscr";
        }

        /*if(USE_SANDBOX == true) {
            $paypal_url = "https://ipnpb.sandbox.paypal.com/cgi-bin/webscr";
        } else {
            $paypal_url = "https://ipnpb.paypal.com/cgi-bin/webscr";
        }*/

        $ch = curl_init($paypal_url);
        if ($ch == FALSE) {
            return FALSE;
        }

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);

        if(DEBUG == true) {
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
        }

        // CONFIG: Optional proxy configuration
        //curl_setopt($ch, CURLOPT_PROXY, $proxy);
        //curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);

        // Set TCP timeout to 30 seconds
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

        // CONFIG: Please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path
        // of the certificate as shown below. Ensure the file is readable by the webserver.
        // This is mandatory for some environments.

        //$cert = __DIR__ . "./cacert.pem";
        //curl_setopt($ch, CURLOPT_CAINFO, $cert);

        $res = curl_exec($ch);
        if (curl_errno($ch) != 0) // cURL error
            {
            if(DEBUG == true) {	
                error_log(date('[Y-m-d H:i e] '). "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL, 3, LOG_FILE);
            }
            curl_close($ch);
            exit;

        } else {
                // Log the entire HTTP response if debug is switched on.
                if(DEBUG == true) {
                    error_log(date('[Y-m-d H:i e] '). "HTTP request of validation request:". curl_getinfo($ch, CURLINFO_HEADER_OUT) ." for IPN payload: $req" . PHP_EOL, 3, LOG_FILE);
                    error_log(date('[Y-m-d H:i e] '). "HTTP response of validation request: $res" . PHP_EOL, 3, LOG_FILE);
                }
                curl_close($ch);
        }

        // Inspect IPN validation result and act accordingly

        // Split response headers and payload, a better way for strcmp
        $tokens = explode("\r\n\r\n", trim($res));
        $res = trim(end($tokens));

        if (strcmp ($res, "VERIFIED") == 0) {
            // check whether the payment_status is Completed
            // check that txn_id has not been previously processed
            // check that receiver_email is your PayPal email
            // check that payment_amount/payment_currency are correct
            // process payment and mark item as paid.

            // assign posted variables to local variables
            $item_name = $_POST['item_name'];
            $item_number = $_POST['item_number'];
            $payment_status = $_POST['payment_status'];
            $payment_amount = $_POST['mc_gross'];
            $payment_currency = $_POST['mc_currency'];
            $txn_id = $_POST['txn_id'];
            $receiver_email = $_POST['receiver_email'];
            $payer_email = $_POST['payer_email'];
            
            if(DEBUG == true) {
                error_log(date('[Y-m-d H:i e] '). "Verified IPN: $req ". PHP_EOL, 3, LOG_FILE);
            }

            return array(
                "item_name" => $item_name,
                "item_number" => $item_number,
                "payment_status" => $payment_status,
                "payment_amount" => $payment_amount,
                "payment_currenPublish the signed, encrypted HTML code for the payment button to the website.	cy" => $payment_currency,
                "txn_id" => $txn_id,
                "receiver_email" => $receiver_email,
                "payer_email" => $payer_email
            );
        } else if (strcmp ($res, "INVALID") == 0) {
            // log for manual investigation
            // Add business logic here which deals with invalid IPN messages
            if(DEBUG == true) {
                error_log(date('[Y-m-d H:i e] '). "Invalid IPN: $req" . PHP_EOL, 3, LOG_FILE);
            }
        }

        return false;
    }

    public static function encrypt($arr){
        self::getInstance();

        $MY_KEY_FILE = Util::getFullPath("/nonpublic/my-prvkey.pem");
        # public certificate file to use
        $MY_CERT_FILE = Util::getFullPath("/nonpublic/my-pubcert.pem");

        # Paypal's public certificate
        $PAYPAL_CERT_FILE = Util::getFullPath("/nonpublic/paypal_cert_pem.txt");

        # path to the openssl binary
        $OPENSSL = "/usr/bin/openssl";


        $form = $arr;

        $form["cert_id"] = self::$config["cert_id"];
        $form["business"] = self::$config["account"];

        if (!file_exists($MY_KEY_FILE)) {
            die("ERROR: MY_KEY_FILE $MY_KEY_FILE not found\n");
        }
        if (!file_exists($MY_CERT_FILE)) {
            die("ERROR: MY_CERT_FILE $MY_CERT_FILE not found\n");
        }
        if (!file_exists($PAYPAL_CERT_FILE)) {
            die("ERROR: PAYPAL_CERT_FILE $PAYPAL_CERT_FILE not found\n");
        }

        //Assign Build Notation for PayPal Support
        //$form['bn']= 'StellarWebSolutions.PHP_EWP2';

        $params = array();
        foreach ($form as $key => $value) {
            if ($value != "") {
                //echo "Adding to blob: $key=$value\n";
                //$data .= "$key=$value\n";
                $params[] = "$key=$value";
            }
        }
        $data = implode("\n",$params);

        //var_dump($data); die();

        $openssl_cmd = "($OPENSSL smime -sign -signer $MY_CERT_FILE -inkey $MY_KEY_FILE " .
                            "-outform der -nodetach -binary <<_EOF_\n$data\n_EOF_\n) | " .
                            "$OPENSSL smime -encrypt -des3 -binary -outform pem $PAYPAL_CERT_FILE";

        $encrypted = shell_exec($openssl_cmd);

        return $encrypted;
    }
}