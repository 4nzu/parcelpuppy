<?php

import('Zappy.Cache');
import('Zappy.Paypal');

class API extends Template {

	function __construct($version = null) {
		$this->no_header = true;
		$bad_request = false;

		if (isset($_GET['version']) && strstr(SUPPORTED_API_VERSIONS, $_GET['version']))
			$this->version = $_GET['version'];
		elseif (!empty($version) && strstr(SUPPORTED_API_VERSIONS, ''.$version)) {
			$this->version = $version;
		}
		else
			$bad_request = true;
		if ($bad_request) {
			die;
		}

		$this->db = DB::instance();
	}

	public function update_extras() {
		if ($_SESSION['logged_in']) {
			$_params = array('first_name', 'last_name', 'address_1', 'address_2', 'city', 'country', 'state', 'zip_code');
			foreach($_params as $_p) {
				if (!isset($_POST[$_p])) {
					$this->json_out(array('request' => 'ERROR: missing parameter', 'var' => $_p));
				}
				else {
					$_u = new User();
					if (!$_u->update_single_field($_p, $_POST[$_p])) {
						$this->json_out(array('request' => 'ERROR: failed to update', 'var' => $_p));
					}
				}
			}
			$this->json_out(array('request' => 'OK'));
		}
		else {
			$this->json_out(array('request' => 'ERROR: Not authorized'));	
		}
	}

	public function verify_login() {
    	$social = 'badlogin';

		if (isset($_POST['email']) && isset($_POST['pass']) && !empty($_POST['email']) && !empty($_POST['pass'])) {

			$_POST['email'] = substr($_POST['email'], 0, 256);
			$_SESSION['last_attempt_email'] = $_POST['email'];
			
			$u = new User();
			$social = $u->social_only($_POST['email'], $_POST['new']);

			if ($social === true) {
				$this->json_out(array('request' => 'OK'));
			}
			else {
				if ($u->getByEmailPassword($_POST['email'], $_POST['pass'])) {
					$this->json_out(array('request' => 'OK'));
				}
			}

			if ($social == 'nologin' && strlen($_POST['pass']) < 5) $this->json_out(array('request' => 'ERROR', 'social' => 'nopass'));
			else $this->json_out(array('request' => 'OK'));
		}
		$this->json_out(array('request' => 'ERROR', 'social' => $social));
	}

	public function create() {
		if ($_SESSION['logged_in']) {

			$error_code = null;
			if (empty($_REQUEST['pr_name'])) {
				$error_code = '#pr_name';
			}
			if (empty($_REQUEST['pr_city'])) {
				if (empty($error_code)) $error_code = '#pr_city';
				else $error_code .= ', #pr_city';
			}

			if (!is_null($error_code)) $this->json_out(array('request' => 'ERROR', 'error_code' => $error_code));

			$sql = 'INSERT INTO products(name, price) VALUES(?, ?)';
			$product_id = $this->db->insert($sql, array($_REQUEST['pr_name'], $_REQUEST['pr_price']));

			$sql = 'INSERT INTO requests(puppy_fee, region_id, city, description, user_id, created_on) VALUES(?, ?, ?, ?, ?, now())';
			$request_id = $this->db->insert($sql, array($_REQUEST['pr_fee'], $_REQUEST['pr_country'], $_REQUEST['pr_city'], $_REQUEST['pr_description'], $_SESSION['user']->id));

			$sql = 'INSERT INTO requests_products(product_id, request_id) VALUES(?, ?)';
			$this->db->execute($sql, array($product_id, $request_id));

			$this->json_out(array('request' => 'OK'));
		}
		else {
			$this->json_out(array('request' => 'ERROR: Not authorized'));
		}
	}

    //do not rename this function
    public static function updatePaymentDetails($response) {
        //TODO save correlation id and other details into db
        //var_dump($response);
        //exit;
    }

    public function pay() {

        $_p = new Paypal();
        $_p->StartPayment("parcelpuppy-developer@gmail.com", 10);
    }

    public function cancel_payment() {
        echo "Payment cancelled";
    }

    public function success_payment() {
//        $_p = new Paypal();

        //$_p->StartPayment("parcelpuppy-developer@gmail.com", 10);
    }

    public function fb_login() {
		if (isset($_POST['name']) && !empty($_POST['name']) &&
			isset($_POST['email']) && !empty($_POST['email']) &&
			isset($_POST['id']) && !empty($_POST['id'])) {

            require_once('../../../Frameworks/facebook-php/src/facebook.php');

            $config = array();
            $config['appId'] = FACEBOOK_APPID;
            $config['secret'] = FACEBOOK_APPSECRET;

            $facebook = new Facebook($config);
            $params = array('scope' => 'email', 'next' => SITE_URL.'/logout');
            
            $fb_access_token =  $facebook->getAccessToken();

            if (!empty($fb_access_token)) {
            	$u = new User();
				$u->updateFacebookUser($_POST);
            	$_SESSION['logout_url'] = $logoutUrl = 'https://www.facebook.com/logout.php?next='.SITE_URL.'/logout&access_token='.$fb_access_token;
            }
            else
            	$_SESSION['logout_url'] = $facebook->getLogoutUrl($params);

            if (!empty($u->token) && !empty($u->token)) {
				$_SESSION['logged_in'] = true;
				$this->json_out(array('request' => 'OK', 'c' => LOGIN_COOKIE_NAME, 't' => $u->token, 'e' => date('Y M d H:i:s', time() + 60*60*24*7*365)));
			}
			else {
				$this->json_out(array('request' => 'ERROR'));
			}
			exit;
		}
	}

	public function g_login() {

        if(isset($_POST['code'])) {
            require_once('../../../Frameworks/google-api-php/src/Google_Client.php');
            require_once('../../../Frameworks/google-api-php/src/contrib/Google_PlusService.php');

            $client = new Google_Client();
            $client->setClientId(GOOGLE_CLIENTID);
            $client->setClientSecret(GOOGLE_CLIENTSECRET);
            $client->setDeveloperKey(GOOGLE_SERVER_KEY);
            $client->setRedirectUri('postmessage');
            $client->setAccessType('offline');
            $client->setScopes(array('https://www.googleapis.com/auth/plus.login','https://www.googleapis.com/auth/plus.me', 'https://www.googleapis.com/auth/userinfo.email'));

            $plus = new Google_PlusService($client);

            $client->authenticate($_POST['code']);
            if($client->getAccessToken()) {
                $me = $plus->people->get('me');
                $u = new User();

                $u->updateGoogleUser($me);

                $_SESSION['logout_url'] = '/logout';

                if (!empty($u->token)) {
                    $_SESSION['logged_in'] = true;
                    $this->json_out(array('request' => 'OK', 'c' => LOGIN_COOKIE_NAME, 't' => $u->token, 'e' => date('Y M d H:i:s', time() + 60*60*24*7*365)));
                }
                exit;
            }
        }
	}

}
