<?php

class User {

    public $username   = null;
    public $id         = null;
    public $email      = null;
    public $full_name  = null;
    public $first_name = null;
    public $last_name  = null;
	public $site_lang  = null;
    public $gender     = null;
    public $token      = null;
    public $created    = null;
    public $verification_token = null;
    public $profile_image = null;
    public $profile_url = null;
    public $days_back = null;
	public $show_splash = null;
	public $people_cluster_id = null;
	public $optin = null;
	public $last_papers_upload = null;
    public $device_type_id = 0;
    public $invisible = null;
    public $affiliation = null;
    public $initials = null;
    public $mendeley_token = null;
    public $affiliation_url = null;
    public $bio = null;
    public $city = null;
    public $country = null;

    function __construct($id = null) {
		$this->db = DB::instance();
        if (!defined('PASSWORD_SALT_1')) define('PASSWORD_SALT_1', 'bEan');
        if (!defined('PASSWORD_SALT_2')) define('PASSWORD_SALT_2', 'saCk');
        if (!defined('TOKEN_SALT_1')) define('TOKEN_SALT_1', ';r$0');
        if (!defined('TOKEN_SALT_2')) define('TOKEN_SALT_2', 'yR-1');
		
        if (is_numeric($id)) {
            $this->getByUserID((int)$id);
        }
        else {
            if (empty($_SESSION['user'])) {
                $this->getByCookie();
            }
            else {
                $this->username 	       = $_SESSION['user']->username;
                $this->full_name 	       = $_SESSION['user']->full_name;
                $this->first_name          = $_SESSION['user']->first_name;
                $this->last_name           = $_SESSION['user']->last_name;
				$this->site_lang           = $_SESSION['user']->site_lang;
                $this->gender   	       = $_SESSION['user']->gender;
                $this->id       	       = $_SESSION['user']->id;
                $this->email    	       = $_SESSION['user']->email;
                $this->token    	       = $_SESSION['user']->token;
                $this->created  	       = $_SESSION['user']->created;
                $this->profile_image       = $_SESSION['user']->profile_image;
                $this->profile_url         = $_SESSION['user']->profile_url;
                $this->verification_token  = $_SESSION['user']->verification_token;
                $this->days_back           = $_SESSION['user']->days_back;
                $this->show_splash         = $_SESSION['user']->show_splash;
                $this->people_cluster_id   = $_SESSION['user']->people_cluster_id;
				$this->optin               = $_SESSION['user']->optin;
				$this->last_papers_upload  = $_SESSION['user']->last_papers_upload;
                $this->device_type_id      = $_SESSION['user']->device_type_id;
                $this->invisible           = $_SESSION['user']->invisible;
                $this->affiliation         = $_SESSION['user']->affiliation;
                $this->initials            = $_SESSION['user']->initials;
                $this->mendeley_token      = $_SESSION['user']->mendeley_token;
                $this->affiliation_url     = $_SESSION['user']->affiliation_url;
                $this->bio                 = $_SESSION['user']->bio;
            }
        }
    }

    public function secureEncrypt($str, $offset = 48) {
        return substr(md5(PASSWORD_SALT_1).md5($str).md5(PASSWORD_SALT_2), strlen($str), $offset);
    }

    public function generateToken($id) {
        return md5(TOKEN_SALT_1.$id.TOKEN_SALT_2);
    }

    private function _set($res) {
    	if (empty($res)) {
			unset($this->id);
			unset($_SESSION['user']);
			return false;
		}

        if (strlen($res[0]['user_id']) > 0) {
            $this->username            = $res[0]['username'];
            $this->full_name           = $res[0]['full_name'];
            $this->first_name          = $res[0]['first_name'];
            $this->last_name           = $res[0]['last_name'];
			$this->site_lang           = $res[0]['site_lang'];
            $this->gender              = $res[0]['gender'];
            $this->id                  = $res[0]['user_id'];
            $this->email               = $res[0]['email'];
            $this->created             = $res[0]['created'];
            $this->profile_image       = $res[0]['profile_image'];
            $this->profile_url         = $res[0]['profile_url'];
            $this->token               = $this->generateToken($this->id);
            $this->verification_token  = $res[0]['verification_token'];
			$this->days_back           = $res[0]['days_back'];
			$this->show_splash         = $res[0]['show_splash'];
			$this->people_cluster_id   = $res[0]['people_cluster_id'];
			$this->optin               = $res[0]['optin'];
			$this->last_papers_upload  = $res[0]['last_papers_upload'];
            $this->invisible           = $res[0]['invisible'];
            $this->affiliation         = $res[0]['affiliation'];
            $this->initials            = $res[0]['initials'];
            $this->mendeley_token      = $res[0]['mendeley_token'];
            $this->affiliation_url     = $res[0]['affiliation_url'];
            $this->bio                 = $res[0]['bio'];
            if (isset($res[0]['device_type_id']))
                $this->device_type_id = $res[0]['device_type_id'];
            

			$session_this = clone $this;
			unset($session_this->db);
            $_SESSION['user'] = $session_this;

            if (isset($res[0]['last_login']) && substr($res[0]['last_login'], 0, 4) == '0000' && empty($this->verification_token))
                $_SESSION['first_login'] = true;
            else
                $_SESSION['first_login'] = false;

			// user successfully logged in
			$this->db->execute('UPDATE users SET last_login = now(), ip_address = ?
                                WHERE user_id = ?', array($_SERVER['REMOTE_ADDR'], $this->id));

            return true;
        }
        else {
            return false;
        }
    }

    public function user_id_exists($user_id) {
        $sql = 'SELECT user_id FROM users WHERE user_id = ?';
        $res = $this->db->query($sql, array($user_id));

        return isset($res[0]['user_id']) ? true : false;
    }

    public function verifyToken($token) {
		$res = $this->db->query('SELECT user_id FROM users WHERE verified = 1 '.
								'AND md5(concat(concat(\''.TOKEN_SALT_1.'\',user_id),\''.TOKEN_SALT_2.'\')) = ?',
								array($token));
		return isset($res[0]['user_id']);
    }

    // Attempts to get User from the cookie
    public function getByCookie() {
        if (isset($_COOKIE[LOGIN_COOKIE_NAME])) {
            $this->_set($this->db->query('SELECT username, user_id, email, created, verification_token, gender,
                                        full_name, first_name, last_name, site_lang, profile_image,
										days_back, last_login,
										show_splash,
										people_cluster_id,
										optin,
										last_papers_upload,
                                        invisible, affiliation, initials, mendeley_token, profile_url, affiliation_url, bio
										FROM users WHERE verified = 1 AND
										md5(concat(concat(\''.TOKEN_SALT_1.'\',user_id),\''.TOKEN_SALT_2.'\')) = ?',
										array($_COOKIE[LOGIN_COOKIE_NAME]), false));
        }
        if (is_null($this->id)) {
            return false;
        }
        else {
            return true;
        }
    }

    public function getByToken($token) {
        if (!empty($token)) {
            $this->_set($this->db->query('SELECT username, user_id, email, created, verification_token, gender,
                                        full_name, first_name, last_name, site_lang, profile_image,
                                        days_back, last_login,
                                        show_splash,
                                        people_cluster_id,
                                        optin,
                                        last_papers_upload,
                                        invisible, affiliation, initials, mendeley_token, profile_url, affiliation_url, bio
                                        FROM users WHERE verified = 1
                                        AND md5(concat(concat(\''.TOKEN_SALT_1.'\',user_id),\''.TOKEN_SALT_2.'\')) = ?', array($token), false));
        }
        if (is_null($this->id)) {
            return false;
        }
        else {
            return true;
        }
    }

    // Attempts to get User from the database
    public function getByUserID($id) {
        if (!empty($id)) {
            $this->_set($this->db->query('SELECT username, user_id, email, created, verification_token, gender,
                                        full_name, first_name, last_name, site_lang, profile_image,
										days_back, last_login,
                                        show_splash,
										people_cluster_id,
										optin,
										last_papers_upload,
                                        invisible, affiliation, initials, mendeley_token, profile_url, affiliation_url, bio
										FROM users WHERE verified = 1 AND user_id = ?', array($id), false));
        }
        if (is_null($this->id)) {
            return false;
        }
        else {
            return true;
        }
    }

	// Attempts to get User from the database by their device id
    public function getByDeviceID($id, $type_id = null) {
        if (!empty($id)) {
            $sql = 'SELECT u.username, u.user_id, u.email, u.created, u.verification_token, u.gender, u.affiliation_url, u.bio,
                                         u.full_name, u.first_name, u.last_name, u.site_lang, u.profile_image, u.days_back, u.show_splash, u.people_cluster_id,
                                         u.optin, u.last_papers_upload, u.invisible, d.device_type_id, u.affiliation, u.initials, u.mendeley_token, u.profile_url
                                         FROM users u, devices d
                                         WHERE d.device_id = ? AND d.user_id != 0 AND
                                         d.user_id = u.user_id';
            $this->_set($this->db->query($sql, array($id), false));
        }
        if (is_null($this->id)) {
            return false;
        }
        else {
            return true;
        }
    }

	public function attachDeviceToUser($device_id, $device_type_id) {
		if (!empty($device_id) && $this->id > 0) {
            if ($device_type_id != IOS_DEVICE_ID && $device_type_id != ANDROID_DEVICE_ID && $device_type_id != GLASS_DEVICE_ID) $device_type_id = IOS_DEVICE_ID;
			$sql = "SELECT user_id FROM devices WHERE device_id = ? AND user_id = 0";
			$res = $this->db->query($sql, array($device_id));

			if (count($res) == 0 && $this->id > 0) {
				$sql = "REPLACE INTO devices(user_id, device_id, device_type_id, people_cluster_id) values(?, ?, ?, 4)";
				$this->db->execute($sql, array($this->id, $device_id, $device_type_id));
			}
			else {
				$sql = "UPDATE devices SET user_id = ?, people_cluster_id = 4, device_type_id = ? WHERE device_id = ?";
				$this->db->execute($sql, array($this->id, $device_type_id, $device_id));
			}
            return true;
		}
        else {
            return false;
        }
	}

    public function getByFacebookID($id) {
    	if (!empty($id)) {
    	    $this->_set($this->db->query('SELECT username, user_id, email, created, verification_token, gender,
                                        full_name, first_name, last_name, site_lang, profile_image,
    									days_back,
                                        show_splash,
    									people_cluster_id,
    									optin,
    									last_papers_upload,
                                        invisible, affiliation, initials, mendeley_token, profile_url, affiliation_url, bio
    									FROM users WHERE verified = 1 AND facebook_id = ?', array($id), false));
    	}
    	if (is_null($this->id)) {
    	    return false;
    	} else {
    	    return true;
    	}
    }

    public function getByTokenAndID($token, $id, $oauth_provider) {
    	if (!empty($id)) {
    		$this->_set($this->db->query('SELECT username, user_id, email, created, verification_token, gender,
                                        full_name, first_name, last_name, site_lang, profile_image,
                                        days_back,
                                        show_splash,
                                        people_cluster_id,
                                        optin,
                                        last_papers_upload,
                                        invisible, affiliation, initials, mendeley_token, profile_url, affiliation_url, bio
                                        FROM users WHERE '.$oauth_provider.'_id = ? and auth_token = ?', array($id, $token)));
    	}
	if (is_null($this->id)) {
	    return false;
	} else {
	    return true;
    	}
    }

    public function getEmailByTokenAndID($token, $id, $oauth_provider) {
        if (!empty($id) && !empty($token)) {

            $res = $this->db->query('SELECT email FROM users WHERE '.$oauth_provider.'_id = ? and auth_token = ?', array($id, $token));
            if (isset($res[0]['email'])) {
                return $res[0]['email'];
            }
        }
        return '';
    }

    public function getByEmail($email) {
        if (!empty($email)) {
            $this->_set($this->db->query('SELECT username, user_id, email, created, verification_token, gender,
                                        full_name, first_name, last_name, site_lang, profile_image,
                                        days_back,
                                        show_splash,
                                        people_cluster_id,
                                        optin,
                                        last_papers_upload,
                                        invisible, affiliation, initials, mendeley_token, profile_url, affiliation_url, bio
										FROM users WHERE verified = 1 AND email = ?', array($email), false));
        }
        if (is_null($this->id)) {
            return false;
        }
        else {
            return true;
        }
    }

    public function getByEmailPassword($email, $pwd) {
		// error_log('ID='.$this->id);
        if (!empty($email)) {
			$data = $this->db->query('SELECT username, user_id, email, created, verification_token, gender,
                                    full_name, first_name, last_name, site_lang, profile_image,
									days_back, last_login,
                                    show_splash,
                                    people_cluster_id,
                                    optin,
                                    last_papers_upload,
                                    invisible, affiliation, initials, mendeley_token, profile_url, affiliation_url, bio
                                    FROM users WHERE verified = 1 AND email = ? AND password = ?',
                                    array($email, $this->secureEncrypt($pwd)), false);
			// error_log('RECORDS='.count($data).' FOR EMAIL='.$email.' PWD='.$this->secureEncrypt($pwd));
            $this->_set($data);
        }
        if (is_null($this->id)) {
			// error_log('FAIL='.$this->id);
            return false;
        }
        else {
			// error_log('OK='.$this->id);
            return true;
        }
    }

    public function createUser($source, $data) {
        if ($source == SNT_FACEBOOK || $source == SNT_GOOGLEPLUS || $source == SNT_TWITTER) {
            
            $verification_token = md5($data['email'].microtime());
			
            $query = 'INSERT INTO users(email, first_name, last_name, full_name, '.$source.'_id, auth_token,
                                        gender, verified, created, ip_address, profile_image, site_id, verification_token, last_login)
                      VALUES(?, ?, ?, ?, ?, ?, ?, ?, now(), ?, ?, ?, ?, now())';
            $new_id = $this->db->insert($query, array($data['email'], $data['first_name'], $data['last_name'], $data['full_name'], $data['id'],
                                                     $data['token'], $data['gender'], 1, $_SERVER["REMOTE_ADDR"],
                                                     empty($data['profile_image']) ? $this->generate_profile_image() : $data['profile_image'], SITE_ID, $verification_token));

            $this->getByUserID($new_id);
            $this->preset_email_settings($new_id);
            $this->setup_user_profile_url($new_id);

            return array('id' => $new_id,
                         'token' => md5(TOKEN_SALT_1.$new_id.TOKEN_SALT_2),
                         'verification_token' => $verification_token,
                         'email' => $data['email']);
        }
        elseif ($source == 'site') {
            $verified_email = $this->checkVerifiedEmail($data['email']);
            if ($verified_email[0] == 0 && $verified_email[1] == 0) {
                if ($this->validateEmail($data['email']) && !empty($data['password'])) {
                    $verification_token = md5($data['email'].microtime());
                    
                    $query = 'INSERT INTO users(email, password, profile_image, verification_token, verified, created, site_id)
                              VALUES(?, ?, ?, ?, ?, now(), ?)';

                    $new_id = $this->db->insert($query, array($data['email'],
                                                              $this->secureEncrypt($data['password']),
                                                              $this->generate_profile_image(),
                                                              $verification_token,
                                                              isset($data['verified'])?$data['verified']:0,
                                                              SITE_ID));
                        
                    $this->preset_email_settings($new_id);
                    $this->setup_user_profile_url($new_id);

                    return array('id' => $new_id,
								 'token' => md5(TOKEN_SALT_1.$new_id.TOKEN_SALT_2),
								 'verification_token' => $verification_token,
                                 'email' => $data['email']);
                }
            }
            else {
                import('Zappy.Cache');
                $_c = new Cache();
                $cache_key = md5($data['email'].'_new_user_account');
                if (!$attempt_number = $_c->get($cache_key)) $attempt_number = 1; else $attempt_number++;
                $_c->set($cache_key, $attempt_number, 1200);
                if ($attempt_number < 10) {
                    $query = 'select user_id, verification_token from users where email = ?';
                    $em_res = $this->db->query($query, array($data['email']));
                    if (count($em_res) == 1) {
                        if (empty($em_res[0]['verification_token'])) {                            
                            $verification_token = md5($data['email'].microtime());
                            $query = 'update users set verification_token=? where user_id=?';
                            $this->db->execute($query, array($verification_token, $em_res[0]['user_id']));
                        }
                        else
                            $verification_token = $em_res[0]['verification_token'];

                        if (isset($verification_token))
                            return array('id' => $em_res[0]['user_id'],
                                         'token' => md5(TOKEN_SALT_1.$em_res[0]['user_id'].TOKEN_SALT_2),
                                         'verification_token' => $verification_token,
                                         'email' => $data['email']);
                        else
                            return false;
                    }
                    else
                        return false;
                }
                else {
                    return false;
                }
            }
            return false;
        }
    }

    public function preset_email_settings($user_id=null) {
        if (is_numeric($user_id)) {
            $sql = 'INSERT IGNORE INTO users_email_settings(user_id, message_type_id, active)
                    SELECT ?, message_type_id, active FROM email_settings es WHERE es.active=1';
            $this->db->execute($sql, array($user_id));
        }
    }

    public function generate_profile_image() {
        $r_number = rand(1, 18);
        if ($r_number < 10) $profile_image = '/img/avatars/00'.$r_number.'.png';
        else $profile_image = '/img/avatars/0'.$r_number.'.png';

        return $profile_image;
    }

    public function _setInfo($source, $data) {
        if ($source == 'facebook') {
            $query = 'UPDATE users SET first_name=?, last_name=?, full_name=?, facebook_id=?, gender=?, verified=1 WHERE user_id=?';
            $this->db->execute($query, array($data['first_name'], $data['last_name'], $data['name'], $data['id'], $data['gender'], $this->id));
        }
        elseif($source == 'google') {
            $query = 'UPDATE users SET first_name=?, last_name=?, full_name=?, google_id=?, gender=?, verified=1 WHERE user_id=?';
            $this->db->execute($query, array($data['givenName'], $data['familyName'], $data['displayName'], $data['id'], $data['gender'], $this->id));
        }
    }

    public function updateFacebookUser($fb) {
        if (!empty($fb['email']) && !empty($fb['id'])) {

            $data['email'] = $fb['email'];
            $data['first_name'] = $fb['first_name'];
            $data['last_name'] = $fb['last_name'];
            $data['full_name'] = $fb['name'];
            $data['id'] = $fb['id'];
            $data['gender'] = $fb['gender'];

            if ($this->getByEmail($fb['email'])) {

                // update facebook_id
                $sql = 'UPDATE users SET facebook_id = ? WHERE user_id = ?';
                $this->db->execute($sql, array($data['id'], $this->id));

				$status = false;
            }
            else {
                $this->createUser('facebook', $data);
				$status = true;
            }

	        return $status;
        }
    }

    public function updateGoogleUser($g) {
        if (!empty($g['emails'][0]['value']) && !empty($g['id'])) {

            $data['first_name'] = $g['givenName'];
            $data['last_name'] = $g['familyName'];
            $data['full_name'] = $g['displayName'];
            $data['id'] = $g['id'];
            
            foreach($g['emails'] as $email) {
                $data['email'] = $email['value'];
                if ($this->getByEmail($data['email'])) {
                    
                    // update google_id
                    $sql = 'UPDATE users SET google_id = ? WHERE user_id = ?';
                    $this->db->execute($sql, array($data['id'], $this->id));
                    
                    return false;
                }
                else {
                    $this->createUser('google', $data);
                    return true;
                }
            }
        }
    }

    public function updateTwitterUser($t) {
    	if (!empty($t)) {
    	    $this->createUser('twitter', $t);
    	}
    }

    public function updateUserInfo($data) {
        if (!empty($this->id)) {

            $query = 'UPDATE users SET email=?, full_name=?, first_name=?, site_lang=?, last_name=?,
                                       days_back=?, people_cluster_id=?,
                                       profile_image=?, username=?
					  WHERE user_id=?';
            $this->db->execute($query, array($data['email'], $data['full_name'], $data['first_name'],$data['site_lang'], 
											$data['last_name'],$data['days_back'], $data['people_cluster_id'],
											$data['profile_image'], $data['username'], $this->id));
            $this->getByUserID($this->id);
            return true;
        }
        return false;
    }

	public function updateLastPapersUploadDate() {
        if (!empty($this->id)) {
            $query = 'UPDATE users SET last_papers_upload = now() WHERE user_id=?';
            $this->db->execute($query, array($this->id));
            $this->getByUserID($this->id);
            return true;
        }
        return false;
    }

    public function updatePassword($new_password) {
        if (!(empty($new_password)) && strlen($new_password) > 5) {
            $query = 'update users set password=? where user_id=?';
            $this->db->execute($query, array($this->secureEncrypt($new_password), $this->id));
            return true;
        }
        return false;
    }

    public function destroy() {
        unset($this->token);
        unset($this->id);
        unset($this->token);
        unset($this->username);
        unset($_SESSION['user']);
    }

    public function validateUsername($username) {
        $pattern = "/^[a-zA-Z0-9]+$/i";
        if (!preg_match($pattern, $username))
            return false;
        if (strlen($username) > 2 && strlen($username) < 19) {
	    import('Zappy.Profanity');
            $p = new Profanity();
            return $p->isClean($username);
        }
        return false;
    }

    public function validateEmail($email) {
        $pattern = "|^([0-9a-zA-Z]([-\.\w]*[0-9a-zA-Z])*@([0-9a-zA-Z][-\w]*[0-9a-zA-Z]\.)+[a-zA-Z]{2,9})$|";
        if (preg_match($pattern, $email))
            return true;
        return false;
    }

    public function checkVerifiedEmail($email) {
        if ($this->validateEmail($email)) {
            $query = 'SELECT verified FROM users WHERE email = ?';
            $res = $this->db->query($query, array($email));
            $cnt = count($res[0]);
            return array(($cnt == 1) ? 1 : 0, ($cnt == 1) ? $res[0]['verified'] : 0);
        }
        return 0;
    }

    public function checkUsername($username) {
        if ($this->username == $username) return 0;
        //if ($this->validateUsername($username)) {
        if (isset($username)) {
            $query = 'select username from users where username = ?';
            $res = $this->db->query($query, array($username));
            return (count($res[0]) == 1) ? 1 : 0;
        }
        return 1;
    }

    public function verifyEmail($data) {

        $verified_id = $this->checkTokens($data);
        
        if (is_null($verified_id)) {
            return false;
        }
        else {
            
            $sql = 'UPDATE users SET verified=1, verification_token = NULL WHERE user_id = ?';
            $this->db->execute($sql, array($verified_id));

            return $verified_id;
        }
    }

    // returns user_id if tokens check out or null if they don't check out
    public function checkTokens($data) {
        $verified_id = null;
        if (isset($data['token']) && isset($data['verification_token'])) {
            $res = $this->db->query('SELECT user_id FROM users
                                     WHERE md5(concat(concat(\''.TOKEN_SALT_1.'\',user_id),\''.TOKEN_SALT_2.'\')) = ?
                                     and verification_token = ?',
                                     array($data['token'], $data['verification_token']), false);
            $verified_id = $res[0]['user_id'];
            $this->token = $data['token'];
            $this->verification_token = $data['verification_token'];
        }
        return $verified_id;
    }

    public function forgotPassword($email) {
        if ($this->validateEmail($email)) {
            // get user id
            $query = 'SELECT user_id FROM users WHERE email = ?';
            $res = $this->db->query($query, array($email));

            if (count($res) > 0) {

                // generate verification token
                $verification_token = md5($email.microtime());

                // record verification token in users table
                $query = 'UPDATE users SET verification_token=? WHERE user_id=?';
                $this->db->execute($query, array($verification_token, $res[0]['user_id']));

                return array('id' => $res[0]['user_id'],
    						 'email' => $email,
                             'verification_token' => $verification_token,
                             'token' => $this->generateToken($res[0]['user_id']));
            }
            else {
                return false;
            }
        }
        return false;
    }

    public function checkPassword($oldpass) {
        if (isset($oldpass)) {
            $res = $this->db->query("SELECT user_id FROM users WHERE password = ? AND user_id = ?",
                   array($this->secureEncrypt($oldpass), $this->id));
            return (count($res) > 0) ? true : false;
        }
        else
            return false;
    }

    public function resetPassword($pwd) {
        if (isset($this->token) && isset($this->verification_token)) {
            $this->db->execute('UPDATE users SET password=?, verification_token=NULL, verified=1
                            WHERE md5(concat(concat(\''.TOKEN_SALT_1.'\',user_id),\''.TOKEN_SALT_2.'\')) = ?
                            and verification_token = ?',
                            array($this->secureEncrypt($pwd), $this->token, $this->verification_token));
            return true;
        }
        return false;
    }

	public function unsubscribe($token) {
		$this->db->execute('UPDATE users SET optin=0, unsubscribed=now() WHERE md5(concat(concat(\''.TOKEN_SALT_1.'\',user_id),\''.TOKEN_SALT_2.'\')) = ?', array($token));
	}

    public function change_optin($flag) {
        if (is_numeric($flag) && is_numeric($this->id)) {
            $this->db->execute('UPDATE users SET optin=?, unsubscribed=now() WHERE user_id=?', array($flag, $this->id));
        }
    }

    public function updateMobileSettings($days_back, $people_cluster_id) {
        if ($this->id && is_numeric($days_back) && is_numeric($people_cluster_id)) {
            $this->db->execute('UPDATE users SET days_back=?, people_cluster_id=? where user_id=?', array($days_back, $people_cluster_id, $this->id));
            $this->days_back = $days_back;
            $this->people_cluster_id = $people_cluster_id;
        }
    }

    public function hide_library_splash() {
        $sql = 'UPDATE users set show_splash = 0 WHERE user_id = ?';
        $this->db->execute($sql, array($this->id));

        $this->show_splash = 0;
        $_SESSION['user']->show_splash = 0;
    }

    public static function get_social_account($user_id = null) {
        if (!empty($user_id)) {
            $sql = 'SELECT facebook_id, google_id FROM users WHERE user_id = ?';
            $res = DB::instance()->query($sql, array($user_id));
            return array('facebook_id' => $res[0]['facebook_id'], 'google_id' => $res[0]['google_id']);
        }
        return false;
    }

    public function update_initials($initials=null) {
        if ($this->id) {
            return $this->update_single_field('initials', $initials);
        }
        else
            return false;
    }

    public function update_affiliation($affiliation=null) {
        if ($this->id) {
            return $this->update_single_field('affiliation', $affiliation);
        }
        else
            return false;
    }

    public function update_affiliation_url($affiliation_url=null) {
        if ($this->id) {
            return $this->update_single_field('affiliation_url', $affiliation_url);
        }
        else
            return false;
    }

    public function update_bio($bio=null) {
        if ($this->id) {
            return $this->update_single_field('bio', $bio);
        }
        else
            return false;
    }

    public function update_first_name($first_name=null) {
        if ($this->id) {
            return $this->update_single_field('first_name', $first_name);
        }
        else
            return false;
    }

    public function update_last_name($last_name=null) {
        if ($this->id) {
            return $this->update_single_field('last_name', $last_name);
        }
        else
            return false;
    }

    public function update_email($email=null) {
        if ($this->id) {
            return $this->update_single_field('email', $email);
        }
        else
            return false;
    }

    public function update_city($city=null) {
        if ($this->id) {
            return $this->update_single_field('city', $city);
        }
        else
            return false;
    }

    public function update_country($country=null) {
        if ($this->id) {
            return $this->update_single_field('country', $country);
        }
        else
            return false;
    }

    public function update_site_lang($site_lang=null) {
        if (!empty($site_lang) && $this->id) {
            return $this->update_single_field('site_lang', $site_lang);
        }
        else
            return false;
    }

    public function update_password($password=null) {
        if ($this->id) {
            return $this->updatePassword($password);
        }
        else
            return false;
    }

    

    private function update_single_field($field_name, $field_value) {
        if (isset($field_name) && !empty($field_name) && isset($field_value) && $this->id) {
            $res = $this->db->query('DESCRIBE users', null, 600);
            $fields = array();
            foreach($res as $r) {
                $fields[$r['Field']] = $r;
            }
            if (isset($fields[$field_name])) {
                $sql = 'UPDATE users SET '.$field_name.'= ? WHERE user_id = ?';
                $this->db->execute($sql, array($field_value, $this->id));
                $this->getByUserID($this->id);
                return true;
            }
            else {
                return false;
            }
        }
    }

    public function load_single_field($field_name) {
        if (isset($field_name) && !empty($field_name) && $this->id) {
            $res = $this->db->query('DESCRIBE users', null, 600);
            $fields = array();
            foreach($res as $r) {
                $fields[$r['Field']] = $r;
            }
            if (isset($fields[$field_name])) {
                $sql = 'SELECT '.$field_name.' FROM users WHERE user_id = ?';
                $res = $this->db->query($sql, array($this->id));
                if (isset($res[0][$field_name])) {
                    $this->$field_name = $res[0][$field_name];
                    $_SESSION['user']->$field_name = $res[0][$field_name];
                }
                return true;
            }
            else {
                return false;
            }
        }
    }

    public function update_names($first_name, $last_name, $full_name, $u_id = null) {
        if (empty($u_id) && isset($this->id)) $u_id = $this->id;
        if ($u_id) {
            $sql = 'UPDATE users SET first_name = ?, last_name = ?, full_name = ? WHERE user_id = ?';
            $res = $this->db->execute($sql, array($first_name, $last_name, $full_name, $u_id));
            if ($res) {
                $this->getByUserID($u_id);
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }
    
    public function update_profile_pic($profile_image) {
        if ($this->id) {
            $sql = 'UPDATE users SET profile_image = ? WHERE user_id = ?';
            $this->db->execute($sql, array($profile_image, $this->id));
            $this->getByUserID($this->id);
            return true;
        }
        else {
            return false;
        }
    }
    
    public function update_mendeley_token($token) {
        if ($this->id) {
            $sql = 'UPDATE users SET mendeley_token = ? WHERE user_id = ?';
            $this->db->execute($sql, array($token, $this->id));
            $this->getByUserID($this->id);
            return true;
        }
        else {
            return false;
        }
    }

    public function setup_user_profile_url($user_id = null) {
        if (is_numeric($user_id)) {
            
            import('Zappy.UserNetwork');
            $_un = new UserNetwork();
            
            $sql = 'UPDATE users SET profile_url = ? WHERE user_id = ?';
            $this->db->execute($sql, array($_un->get_profile_id((int)$user_id), (int)$user_id));

            return true;
        }
        else {
            return false;
        }
    }

    public function update_profile_url($profile_url) {
        if ($this->id) {

            $sql = 'UPDATE users SET profile_url = ? WHERE user_id = ?';
            $this->db->execute($sql, array($profile_url, (int)$this->id));
            
            return true;
        }
        else {
            return false;
        }
    }


    public function social_only($email, $new = 1) {
        if (isset($email) && !empty($email)) {
            $sql = 'SELECT facebook_id, google_id, twitter_id, password, user_id FROM users WHERE email = ?';
            $res = $this->db->query($sql, array($email));

            if (isset($res[0]['user_id'])) {
                if ((!empty($res[0]['facebook_id']) || !empty($res[0]['google_id']) || !empty($res[0]['twitter_id'])) && empty($res[0]['password'])) {
                    if (!empty($res[0]['facebook_id'])) 
                        return 'facebook';
                    elseif (!empty($res[0]['google_id']))
                        return 'google';
                    elseif (!empty($res[0]['twitter_id']))
                        return 'twitter';
                    else
                        return false;
                }
                elseif(!empty($res[0]['password'])) {
                    return 'badlogin';
                }
                else {
                    return true;
                }
            }
            else {
                if ($new == 1)
                    return true;
                else
                    return 'nologin';
            }
        }
        else {
            return 'nologin';
        }
    }

    public function verify_email_pass($email, $pass) {
        if (isset($email) && isset($pass)) {
            $sql = 'SELECT user_id FROM users WHERE email = ? AND password = ?';
            $res = $this->db->query($sql, array($email, $this->secureEncrypt($pass)));

            if (isset($res[0]['user_id'])) {
                return true;
            }
            else {
                return false;
            }
        }
        else 
            return false;
    }

    public function getBySocialNetwork($email, $social_id, $first_name, $last_name, $img_url, $token, $social_network_type) {
        if ((!empty($email) && !empty($social_id)) ||
            (!empty($token) && !empty($social_id))) {

            $data['email'] = $email;
            $data['first_name'] = $first_name;
            $data['last_name'] = $last_name;
            $data['full_name'] = $first_name.' '.$last_name;
            $data['profile_image'] = $img_url;
            $data['token'] = $token;
            $data['id'] = $social_id;

            if ((isset($data['email']) && $this->getByEmail($data['email'])) ||
                (isset($data['token']) && $this->getByTokenAndID($data['token'], $data['id'], $social_network_type))) {
                
                $sql = 'UPDATE users SET '.$social_network_type.'_id = ?, auth_token = ?, verified = 1 WHERE user_id = ?';
                $this->db->execute($sql, array($data['id'], $data['token'], $this->id));
                
                return true;
            }
            else {
                $this->createUser($social_network_type, $data);
                return true;
            }
        }
        else {
            return false;
        }
    }
}
?>
