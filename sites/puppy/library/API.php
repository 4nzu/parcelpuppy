<?php

import('Zappy.Cache');

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
}
