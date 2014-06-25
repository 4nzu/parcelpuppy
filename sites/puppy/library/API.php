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

		if (isset($_POST['email']) && isset($_POST['pass']) &&
			!empty($_POST['email']) && !empty($_POST['pass'])) {

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
				else {
					$this->json_out(array('request' => 'ERROR', 'social' => $social));
				}
			}

			if ($social == 'nologin' && strlen($_POST['pass']) < 5) $this->json_out(array('request' => 'ERROR', 'social' => 'nopass'));
		}
		$this->json_out(array('request' => 'ERROR', 'social' => $social));
	}

// 	description: string,
// 	region_id: int,
// 	shipping string (‘standard’ or ‘express’),
// 	items: [
// 		{
// 			name: string,
// 			brand: string,
// 			quantity: integer,
// 			details: string
// 		}, …
// }

	public function validate_request() {
		if ($_SESSION['logged_in']) {

			if (!isset($_REQUEST['description']))
				$this->json_out(array('request' => 'ERROR: bad or missing \'description\'.'));
			
			if (!isset($_REQUEST['shipping']) || empty($_REQUEST['shipping']))
				$this->json_out(array('request' => 'ERROR: bad or missing \'shipping\'.'));
			
			if (!isset($_REQUEST['region_id']) || !is_numeric($_REQUEST['region_id']))
				$this->json_out(array('request' => 'ERROR: bad or missing \'region_id\'.'));
			

			if (!isset($_REQUEST['items']) || !is_array($_REQUEST['items']))
				$this->json_out(array('request' => 'ERROR: bad or missing \'items\'.'));

			$i = 0;
			foreach($_REQUEST['items'] as $item) {

				if (!isset($item['name']) || empty($item['name']))
					$this->json_out(array('request' => 'ERROR: bad or missing \'name\' for item ['.$i.'].'));

				if (!isset($item['brand']) || empty($item['brand']))
					$this->json_out(array('request' => 'ERROR: bad or missing \'brand\' for item ['.$i.'].'));

				if (!isset($item['details']) || empty($item['details']))
					$this->json_out(array('request' => 'ERROR: bad or missing \'details\' for item ['.$i.'].'));

				if (!isset($item['name']) || !is_numeric($item['quantity']) || empty($item['quantity']))
					$this->json_out(array('request' => 'ERROR: bad or missing quantity for item ['.$i.'].'));

				$i++;
			}
		}
		else {
			$this->json_out(array('request' => 'ERROR: Not authorized'));
		}
	}

	public function new_request() {
		if ($_SESSION['logged_in']) {

			$this->validate_request();

			if ($_REQUEST['shipping'] == 'express') $shipping = 2; else $shipping = 1;

			$sql = 'INSERT INTO requests(region_id, description, shipping_type_id, user_id, created_on) VALUES(?, ?, ?, ?, now())';
			$request_id = $this->db->insert($sql, array($_REQUEST['region_id'], $_REQUEST['description'], $shipping, $_SESSION['user']->id));

			foreach($_REQUEST['items'] as $item) {

				$sql = 'INSERT INTO products(name, brand, details) VALUES(?, ?, ?)';
				$product_id = $this->db->insert($sql, array($item['name'], $item['brand'], $item['details']));

				$sql = 'INSERT INTO requests_products(product_id, request_id, quantity) VALUES(?, ?, ?)';
				$this->db->execute($sql, array($product_id, $request_id, $item['quantity']));

			}
			$this->json_out(array('request' => 'OK', 'request_id' => $request_id));
		}
		else {
			$this->json_out(array('request' => 'ERROR: Not authorized'));
		}
	}

    public function paypal_ipn() {
        $_p = new Paypal();
        $_p->IPNHandler($_POST);
    }

    public function pay() {

        $_p = new Paypal();
        $_p->StartPayment("parcelpuppy-developer@gmail.com", 10, "Parcelpuppy item");
    }

    public function pay_commit() {
        $_p = new Paypal();
        $_p->CompletePayment($_GET['paykey']);
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

	public function update_password() {
		if (!$_SESSION['logged_in']) $this->json_out(array('request' => 'ERROR: Not authorized'));
		if (isset($_POST['newpass']) && !empty($_POST['newpass'])) {
			if (isset($_POST['oldpass']) && !empty($_POST['oldpass'])) {
				if ($u->checkPassword($_POST['oldpass'])) {
					$u->updatePassword($_POST['newpass']);
					$this->json_out(array('request' => 'OK'));
				}
				else {
					$this->json_out(array('request' => 'ERROR oldpass did not match', 'var' => 'oldpass'));
				}
			}
			else {
				$this->json_out(array('request' => 'ERROR: missing oldpass', 'var' => 'oldpass'));
			}
		}
		else {
			$this->json_out(array('request' => 'ERROR: missing newpass', 'var' => 'newpass'));
		}
	}

	public function save_settings() {
		if (isset($_POST['email']) && !empty($_POST['email']) && $_SESSION['logged_in']) {
			$u = new User();
			
			if (!$u->validateEmail($_POST['email'])) {
				$this->json_out(array('request' => 'ERROR', 'var' => 'email'));
			}

			$email_exists = $u->checkVerifiedEmail($_POST['email']);

			// check if this email already in use
			if (strcasecmp($u->email, $_POST['email']) == 0 || ($email_exists[0] == 0 && $email_exists[1] == 0)) {

				if (isset($_POST['optin']) && $_POST['optin'] == '1') $optin = 1; else $optin = 0;
				if (isset($_POST['profile_image']) && substr($_POST['profile_image'], 0, 14) === '/img/avatars/0')
					$profile_pic = $_POST['profile_image'];
				elseif(isset($_POST['profile_image']) && strstr($_POST['profile_image'], 'https://s3.amazonaws.com/'.AVATAR_S3_BUCKET.'/'.$u->id.'_')) {
					$profile_pic = $_POST['profile_image'];
				}
				else
					$profile_pic = '/img/avatars/0'.rand(10, 30).'.png';

				$data = array('first_name' 			=> strip_tags($_POST['first_name']),
							  'last_name' 			=> strip_tags($_POST['last_name']),
							  'email'      			=> $_POST['email']);

				$u->updateUserInfo($data);
				
				if (isset($_POST['address_1'])) {
					$u->update_address_1($_POST['address_1']);
				}
				if (isset($_POST['address_2'])) {
					$u->update_address_2($_POST['address_2']);
				}
				if (isset($_POST['zip_code'])) {
					$u->update_zip_code($_POST['zip_code']);
				}
				if (isset($_POST['state'])) {
					$u->update_state($_POST['state']);
				}
				if (isset($_POST['bio'])) {
					$u->update_bio($_POST['bio']);
				}
				if (isset($_POST['more_info'])) {
					$u->update_more_info($_POST['more_info']);
				}
				

				$this->json_out(array('request' => 'OK'));
			} else {
				$this->json_out(array('request' => 'ERROR', 'var' => 'email'));
			}
		}
		else {
			$this->json_out(array('request' => 'ERROR', 'var' => 'email'));
		}
	}

}
