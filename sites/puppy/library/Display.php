<?php

class Display extends Template {

	function __construct() {
		parent::__construct();
		import('Zappy.Util');
		$this->db = DB::instance();

		import('Zappy.Cache');
		$_c = new Cache();
        $localization = $_c->get('localization_'.$_SESSION['user']->site_lang);
        $this->assign('localization', $localization);
	}

    public function display_home() {
        $this->set_template('home');
    }

    public function signin() {
        // make sure we only hit login on https (SSL-protected) connection when on live server
		if (HOST_ROLE == HOST_PROD && !isset($_SERVER['HTTPS'])) {
			header('Location: '.SECURE_SITE_URL.'/signin');
			exit;
		}
		if (!empty($_POST['email']) && !empty($_POST['pass'])) {

			$_u = new User();
			if ($_u->getByEmailPassword($_POST['email'], $_POST['pass'])) {
				
				$_SESSION['logged_in'] = true;
				setcookie(LOGIN_COOKIE_NAME, $_u->token, time() + 60*60*24*7*365);
				session_write_close();

				if (isset($_SESSION['POST_LOGIN'])) {
					header("Location: ".$_SESSION['POST_LOGIN']);
					exit;
				}
				else {
					$this->set_template('home');
				}
			}
			else {
				$this->wipe_session();
                print 'test_4';
				header('Location: '.SITE_URL.'?badlogin=1');
				exit;
			}
		}
		if ($_SESSION['logged_in']) {
            $this->set_template('home');
		}
		else {
			$this->set_template('signin_login-form');
		}
	}

	public function extras() {
		$this->set_template('signin_extras');
	}

	public function reset() {
        if (isset($_POST['email']) && !empty($_POST['email'])) {
            $user = new User();
            $data = $user->forgotPassword($_POST['email']);

            if (is_array($data)) {
                require_once('../../../Frameworks/amazon/services/ses.php');
                $ses = new SimpleEmailService(AWS_KEY, AWS_SECRET_KEY);

                $message = new SimpleEmailServiceMessage();
                $message->addTo($_POST['email']);
                $message->setFrom('noreply@zappylab.com');

                $message->setSubject(PRODUCT_NAME.' Password Reset');
                $message->setMessageFromString(null, '<html><body>Hello!<p><br>Someone asked to reset your Parcelpuppy password.<br>'.
                                                     'If it was indeed you, please follow this url to reset your password:<br>'.
                                                     SITE_URL.'/newpassword?v='.$data['verification_token'].'&t='.$data['token'].
                                                     '<p><br>'.PRODUCT_NAME.'</body></html>');
                $test = $ses->sendEmail($message);
            }
            $this->set_template('signin_reset-verify-email');
        }
        else {
            $this->set_template('signin_reset-password');
        }
    }

    public function newpassword() {
        $user = new User();
        if (isset($_POST['v']) && !empty($_POST['v']) &&
            isset($_POST['t']) && !empty($_POST['t']) &&
            isset($_POST['password']) && !empty($_POST['password'])) {
                $id = $user->checkTokens(array('token' => $_POST['t'], 'verification_token' => $_POST['v']));
                if (!empty($id)) {
                    $user->resetPassword($_POST['password']);
                }
                else {
                    header("Location: /");
                }

                $this->assign('reset_completed', 1);
                $this->set_template('login_reset-completed');

        }
        else {
            $id = $user->checkTokens(array('token' => $_REQUEST['t'], 'verification_token' => $_REQUEST['v']));
            if (!empty($id)) {
                $this->set_template('signin_new-password');
            }
            else {
                header("Location: /");
            }
        }
    }

	public function thankyou() {
		if (!$_SESSION['logged_in']) {
			if (isset($_POST['email']) && !empty($_POST['email']) &&
				isset($_POST['pass']) && !empty($_POST['pass'])) {

				$this->wipe_session();

				$u = new User();
				$data = array('full_name' => $_POST['full_name'],
							  'email'      => $_POST['email'],
							  'password'   => $_POST['pass']);

				if ($u->validateEmail($_POST['email'])) {
					$verified_email = $u->checkVerifiedEmail($_POST['email']);
	        		if ($verified_email[0] == 0 && $verified_email[1] == 0) {
	        			$res = $u->createUser('site', $data);
						if (isset($res['email']) && isset($res['verification_token'])) {
							
							import('library.amazonAPI');
							$amazonAPI = new amazonAPI();
							$amazonAPI->sesEmail($res);
						}
						$this->set_template('signin_thankyou');
					}
					else {
						if ($u->getByEmailPassword($_POST['email'], $_POST['pass'])) {
							$_SESSION['logged_in'] = true;
							setcookie(LOGIN_COOKIE_NAME, $u->token, time() + 60*60*24*7*365);
							session_write_close();

							if (isset($_SESSION['POST_LOGIN'])) {
								header("Location: ".$_SESSION['POST_LOGIN']);
								exit;
							}
							else {
								header("Location: /");
								exit;
							}
						}
						else {
							$this->wipe_session();
							header('Location: '.SITE_URL.'?emailinuse=1');
							exit;
						}
					}
				}
				else {
					$this->set_template('signin_thankyou');
				}
			}
			else {
				$this->no_header = true;
				$this->no_footer = true;
				$this->set_template('home');
			}
		}
		else {
			header("Location: /");
		}
	}

	public function create() {
		if ($_SESSION['logged_in']) {
			$sql = 'SELECT region_id, region_name FROM regions ORDER BY region_name';
			$countries = $this->db->query($sql);

			$this->Assign('countries', $countries);
			$this->set_template('create');
		}
		else {
			header("Location: /");
		}
	}

	public function view() {
		import('library.Requests');
		$_req = new Requests();
		$this->assign('requests', $_req->get_requests());
		$this->set_template('view');
	}

	public function myrequests() {
		if ($_SESSION['logged_in']) {
			
			import('library.Requests');
			$_req = new Requests();
			$this->assign('requests', $_req->get_requests($_SESSION['user']->id));
			$this->set_template('view');

		}
		else {
			header("Location: /view");
		}
	}

	public function confirm() {
		if (!$_SESSION['logged_in'] &&
			isset($_REQUEST['v']) && !empty($_REQUEST['v']) &&
			isset($_REQUEST['t']) && !empty($_REQUEST['t'])) {

			$_u = new User();
			$user_id = $_u->verifyEmail(array('token' => $_REQUEST['t'], 'verification_token' => $_REQUEST['v']));

			if (!$_u->getByUserID($user_id)) {
				header("Location: /");
				exit;
			}
			else {
				$_SESSION['logged_in'] = true;
				setcookie(LOGIN_COOKIE_NAME, $_u->token, time() + 60*60*24*7*365);
				session_write_close();
			}
		}
		$this->set_template('login_confirm-email');
	}

	public function logout() {
		setcookie(LOGIN_COOKIE_NAME, '', time() - 60*60*24*7*365);
		session_start();
		session_destroy();

		header("Location: /");
		exit;
	}

	public function wipe_session() {
		unset($_SESSION);
		setcookie(LOGIN_COOKIE_NAME, '', time() - 60*60*24*7*365);
		session_start();
		session_destroy();
	}

}
