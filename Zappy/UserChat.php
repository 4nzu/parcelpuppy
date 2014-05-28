<?php

class UserChat {

	function __construct() {
		import('Zappy.Util');
		import('Zappy.Cache');
		$this->db = DB::instance();
	}

	public function find_people($user_id, $key) {
		if (isset($user_id) && !empty($user_id)) {
			
			$_connect = mysql_connect(DB_HOST, DB_USER, DB_PASS);
			mysql_select_db(DB_NAME);
			$key = mysql_real_escape_string(strtolower($key));
			mysql_close($_connect);
			
			$sql = "SELECT CONCAT(u.first_name, ' ', u.last_name) AS full_name, u.user_id, u.affiliation, u.profile_image,
					IF(uc.attempts IS NULL, 0, uc.attempts) AS attempts,
					IF(uc.invite_status IS NULL, 0, uc.invite_status) AS invite_status FROM users u
					LEFT OUTER JOIN users_chat uc
					ON uc.user_id1 = ? AND uc.user_id2 = u.user_id
					WHERE u.verified = 1 AND u.user_id != ?
					AND CONCAT(u.first_name, ' ', u.last_name) LIKE '%".$key."%'
					OR LOWER(u.affiliation) LIKE '%".$key."%'
					GROUP BY u.user_id
					ORDER BY CONCAT(u.first_name, ' ', u.last_name)";
			
			$res = $this->db->query($sql, array($user_id, $user_id));
			$res_out = array();
			
			foreach($res as $r) {
				if ($r['invite_status'] != 3) {
					$r['chat_id'] = $this->get_id($r['user_id']);
					$res_out[] = $r;
				}
			}

			return $res_out;
		}
		else
			return false;
	}

	public function invite($user_id1, $user_id2) {
		if (is_numeric($user_id1) && is_numeric($user_id2) && $user_id1 > 0 && $user_id2 > 0) {

			$from_me_status = $this->check_invite_pending($user_id1, $user_id2);
			$to_me_status = $this->check_invite_pending($user_id2, $user_id1);

			if (is_array($from_me_status)) {
				if ($from_me_status['status'] == 1) {       // u1 invited u2, but u2 did not yet respond to the invitation
					$sql = 'UPDATE users_chat SET attempts = attempts + 1, last_invited = now()
						 	WHERE user_id1 = ? AND user_id2 = ?';
					$this->db->execute($sql, array($user_id1, $user_id2));
				}
				elseif($from_me_status['status'] == 2) {    // u1 invited u2, but u2 declined
					$sql = 'UPDATE users_chat SET invite_status = 1, attempts = attempts + 1 WHERE user_id1 = ? AND user_id2 = ?';
					$this->db->execute($sql, array($user_id1, $user_id2));
					return array('status' => 1, 'attempts' => $from_me_status['attempts']+1);
				}
				elseif($from_me_status['status'] == 3) {    // u1 invited u2, but u2 declined and blocked u1
					$sql = 'UPDATE users_chat SET invite_status = 1, attempts = attempts + 1 WHERE user_id1 = ? AND user_id2 = ?';
					$this->db->execute($sql, array($user_id1, $user_id2));
					return array('status' => 1, 'attempts' => $from_me_status['attempts']+1);
				}
				elseif($from_me_status['status'] == 4) {    // u1 invited u2 and u2 already accepted the invitation
					$sql = 'UPDATE users_chat SET attempts = attempts + 1 WHERE user_id1 = ? AND user_id2 = ?';
					$this->db->execute($sql, array($user_id1, $user_id2));
				}
				return $from_me_status;
			}
			else { // u1 did not yet invite u2, inviting now
				if (is_array($to_me_status)) {
					if ($to_me_status['status'] == 3) {
						return $to_me_status;
					}
					else {
						$sql = 'DELETE FROM users_chat WHERE user_id1 = ? AND user_id2 = ?';
						$this->db->execute($sql, array($user_id2, $user_id1));
					}
				}

				$sql = 'INSERT INTO users_chat(user_id1, user_id2) VALUES(?, ?)';
				$this->db->execute($sql, array($user_id1, $user_id2));

				//TODO: dispatch alert for u2

				return array('status' => 1, 'attempts' => 1);
			}
		}
	}

	public function accept_invite($user_id1, $user_id2, $invite_status) {
		if (is_numeric($user_id1) && isset($user_id2) && $invite_status > 0) {

			$sql = 'UPDATE users_chat SET invite_status = ?, action_date = now() WHERE user_id1 = ? AND user_id2 = ?';
			$this->db->execute($sql, array($invite_status, $user_id2, $user_id1));
			return $invite_status;
		}
		else {
			return false;
		}
	}

	public function check_invite_pending($user_id1, $user_id2) {
		if (is_numeric($user_id1) && is_numeric($user_id2)) {

			$sql = 'SELECT invite_status, attempts FROM users_chat WHERE user_id1 = ? AND user_id2 = ?';
			$res = $this->db->query($sql, array($user_id1, $user_id2));

			if (isset($res[0]['invite_status'])) {
				return array('status' => $res[0]['invite_status'], 'attempts' => $res[0]['attempts']);
			}
			else {
				return false;
			}
		}	
	}

	public function get_id($user_id) {
		return Util::simple_encrypt($user_id);
	}

	public function get_int_id($user_id) {
		return Util::simple_decrypt($user_id);
	}


	public function set_enabled($user_id, $enabled = true) {
		if (is_numeric($user_id) && $enabled) {
			$encrypted_id = Util::simple_encrypt($user_id);
			$sql = 'REPLACE INTO users_chat_status(user_id, encrypted_id, action_type, action_date) VALUES(?, ?, 1, now())';
			$this->db->execute($sql, array($user_id, $encrypted_id));
			return $encrypted_id;
		}
		elseif(is_numeric($user_id) && !$enabled) {
			$sql = 'UPDATE users_chat_status SET action_type = 2, action_date = now() WHERE user_id = ?';
			$this->db->execute($sql, array($user_id));
			return true;
		}
		else {
			return false;
		}
	}

	public function is_enabled($user_id) {
		if (is_numeric($user_id)) {
			$sql = 'SELECT action_type FROM users_chat_status WHERE user_id = ?';
			$res = $this->db->query($sql, array($user_id));

			return isset($res[0]['action_type']) && $res[0]['action_type'] == 1 ? 1 : false;
		}
	}

	public function remove($user_id1, $user_id2, $invite_status = 1) {
		if (is_numeric($user_id1) && is_numeric($user_id2)) {
			if ($invite_status < 0) $invite_status = 1;

			$sql = 'DELETE FROM users_chat WHERE user_id1 = ? AND user_id2 = ?';
			$this->db->execute($sql, array($user_id2, $user_id1));

			$sql = 'REPLACE INTO users_chat (user_id1, user_id2, invite_status, action_date) VALUES(?, ?, ?, now())';
			$this->db->execute($sql, array($user_id1, $user_id2, $invite_status));
			return true;
		}
		else {
			return false;
		}
	}

	public function unblock($user_id1, $user_id2) {
		if (is_numeric($user_id1) && is_numeric($user_id2)) {

			$sql = 'UPDATE users_chat SET invite_status = 4 WHERE user_id1 = ? AND user_id2 = ? AND invite_status = 3';
			$this->db->execute($sql, array((int)$user_id1, (int)$user_id2));
			return true;
		}
		else {
			return false;
		}
	}

}
?>