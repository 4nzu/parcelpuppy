<?php

class UserNetwork {

	public $file_list = null;
	public $file_count = null;
	private $user_id = null;

	function __construct($user_id = null) {
		
		if (is_int($user_id) && $user_id > 0) $this->user_id = $user_id;
		else $this->user_id = $_SESSION['user']->id;

		import('Zappy.Util');
		import('Zappy.Cache');
		$this->db = DB::instance();
	}

	public function set_invisible($user_id = null) {
		if (!empty($user_id)) {
			$sql = 'UPDATE users SET invisible = 1 WHERE user_id=?';
			$this->db->execute($sql, array($user_id));
			$_SESSION['user']->invisible = 1;
		}
		else
			return false;
	}

	public function set_visible($user_id = null) {
		if (!empty($user_id)) {
			$sql = 'UPDATE users SET invisible = 0 WHERE user_id=?';
			$this->db->execute($sql, array($user_id));
			$_SESSION['user']->invisible = 0;
		}
		else
			return false;
	}

	public function get_network_user($user_id = null) {
		if (!empty($user_id)) {
			if (HOST_NAME == HOST_PASSAGEO) {
				$sql = 'SELECT u.user_id,
							   IF (CONCAT(u.first_name, \' \', u.last_name) IS NULL, \'Anonymous\', CONCAT(u.first_name, \' \', u.last_name)) AS full_name,
							   u.profile_image, count(t.trek_id) AS treks_count
						FROM users u
						LEFT OUTER JOIN treks t
						ON t.user_id = u.user_id AND t.active = 1
						WHERE u.user_id = ?';
				$res = $this->db->query($sql, array($user_id));
				$res[0]['public_url'] = $this->get_public_uri($user_id);
			}
			else {
				$sql = 'SELECT u.user_id, CONCAT(u.first_name, \' \', u.last_name) AS full_name,
							   u.profile_image, u.affiliation, count(l.article_id) AS lib_size
						FROM users u
						LEFT OUTER JOIN libraries l
						ON l.user_id = u.user_id AND l.private = 0
						WHERE u.user_id=? AND u.invisible = 0';
				$res = $this->db->query($sql, array($user_id));
				$res[0]['public_url'] = $this->get_public_uri($user_id, $res[0]['full_name']);
			}

			return $res[0];
		}
		else
			return false;
	}

	public function get_subscribed_to($user_id = null, $asc = false) {
		if (!empty($user_id)) {
			if ($asc) $asc = 'ASC'; else $asc = 'DESC';
			$sql = 'SELECT u.user_id, CONCAT(u.first_name, \' \', u.last_name) AS full_name,
			               u.profile_image, u.affiliation, count(l.article_id) AS lib_size
					FROM users u, network_users nu
					LEFT OUTER JOIN libraries l
					ON l.user_id = nu.user_id2 AND l.private = 0
					WHERE nu.user_id1=? AND u.user_id = nu.user_id2 AND CONCAT(u.first_name, \' \', u.last_name) IS NOT NULL';
			if ($_SESSION['user']->id != $user_id) $sql .= ' AND u.invisible = 0 ';
			$sql .= ' GROUP BY nu.user_id2
					ORDER BY CONCAT(u.first_name, \' \', u.last_name) '.$asc;

			return $this->db->query($sql, array($user_id));
		}
		else
			return false;
	}

	public function get_subscribed_to_user($user_id = null, $asc = false) {
		if (!empty($user_id)) {
			if ($asc) $asc = 'ASC'; else $asc = 'DESC';
			$sql = 'SELECT u.user_id, CONCAT(u.first_name, \' \', u.last_name) AS full_name,
			               u.profile_image, u.affiliation, count(l.article_id) AS lib_size
					FROM users u, network_users nu
					LEFT OUTER JOIN libraries l
					ON l.user_id = nu.user_id1 AND l.private = 0
					WHERE nu.user_id2=? AND u.user_id = nu.user_id1 AND CONCAT(u.first_name, \' \', u.last_name) IS NOT NULL';
			if ($_SESSION['user']->id != $user_id) $sql .= ' AND u.invisible = 0 ';
			$sql .= ' GROUP BY nu.user_id1
					ORDER BY CONCAT(u.first_name, \' \', u.last_name) '.$asc;

			$res = $this->db->query($sql, array($user_id));
			foreach($res as &$r) {
				$r['url'] = $this->get_public_uri($r['user_id'], $r['full_name']);
			}
			unset($r);
			return $res;
		}
		else
			return false;
	}

	public function search($user_id, $key, $is_for_chat = false) {
		if (isset($user_id) && !empty($user_id)) {
			
			$_connect = mysql_connect(DB_HOST, DB_USER, DB_PASS);
			mysql_select_db(DB_NAME);
			$key = mysql_real_escape_string(strtolower($key));
			mysql_close($_connect);

			$sql = 'SELECT CONCAT(u.first_name, \' \', u.last_name) AS full_name,
						   u.user_id, u.affiliation, u.profile_image, count(l.article_id) AS lib_size, 
						   IF (nu.user_id2 IS NULL, 0, 1) AS subscribed ';
			if ($is_for_chat) $sql .= ', IF(uc.attempts IS NULL, 0, uc.attempts) AS attempts, IF(uc.invite_status IS NULL, 0, uc.invite_status) AS invite_status ';
			$sql .= 'FROM users u
					LEFT OUTER JOIN libraries l
					ON l.user_id = u.user_id AND l.private = 0
					LEFT OUTER JOIN network_users nu ON nu.user_id2 = u.user_id AND nu.user_id1 = ?';
			if ($is_for_chat) $sql .= ' LEFT OUTER JOIN users_chat uc ON uc.user_id1 = ? AND uc.user_id2 = u.user_id ';
			$sql .= "WHERE u.invisible = 0 AND u.verified = 1 AND u.user_id != ?
					AND CONCAT(u.first_name, ' ', u.last_name) LIKE '%".$key."%'
					OR LOWER(u.affiliation) LIKE '%".$key."%' 
					GROUP BY u.user_id
					ORDER BY CONCAT(u.first_name, ' ', u.last_name)";
			
			if ($is_for_chat) $params = array($user_id, $user_id, $user_id);
			else $params = array($user_id, $user_id);

			$res = $this->db->query($sql, $params);
			$res_out = array();
			
			foreach($res as $r) {
				if ($is_for_chat) {
					if ($r['invite_status'] != 3) {
						$r['chat_id'] = Util::simple_encrypt($r['user_id']);
						$res_out[] = $r;
					}
				}
				else {
					$r['url'] = $this->get_public_uri($r['user_id'], $r['full_name']);
					$res_out[] = $r;
				}
			}

			return $res_out;
		}
		else
			return false;
	}

	public function subscribe($user_id=null) {
		if ($_SESSION['logged_in'] && !empty($user_id)) {
			$original_user_url = $user_id;
			$user_id = $this->get_id_from_uri($user_id);
			$sql = 'INSERT IGNORE INTO network_users(user_id1, user_id2) VALUES(?, ?)';
			$this->db->execute($sql, array($_SESSION['user']->id, $user_id));

			import('Zappy.UserNotifications');
			$_un = new UserNotifications();
			$_un->generate_notificaion($user_id, (int)$_SESSION['user']->id, NOTI_SUBSCRIBED);

			$n_user = $this->get_network_user($user_id);
			$n_user['url'] = $original_user_url;
			return $n_user;
		}
	}

	public function is_subscribed_to($user_id1=null, $user_id2=null) {
		$sql = 'SELECT user_id1 FROM network_users WHERE user_id1 = ? AND user_id2 = ?';
		$res = $this->db->query($sql, array($user_id1, $user_id2));
		return count($res) == 1 ? true : false;
	}

	public function unsubscribe($user_id=null) {
		if ($_SESSION['logged_in'] && !empty($user_id)) {
			$user_id1 = $_SESSION['user']->id;
			$user_id2 = $this->get_id_from_uri($user_id);

			// Util::decode($user_id, $user_id1, $user_id2);
			if ($user_id1 == $_SESSION['user']->id && $this->is_subscribed_to($user_id1, $user_id2)) {
				$sql = 'DELETE FROM network_users WHERE user_id1=? AND user_id2=?';
				$this->db->execute($sql, array($user_id1, $user_id2));

				// generate notification
				import('Zappy.UserNotifications');
				$_un = new UserNotifications();
				$_un->generate_notificaion($user_id, (int)$_SESSION['user']->id, NOTI_UNSUBSCRIBED);
			}
		}
	}

	public function recommended_people($user_id=null) {
		if (isset($user_id) && !empty($user_id)) {
			$sql = 'SELECT u.user_id, IF(u.full_name IS NULL, CONCAT(u.first_name, \' \', u.last_name), u.full_name) AS full_name,
					u.profile_image, u.affiliation, count(l.article_id) AS lib_size, nu.user_id2
					FROM users u, users_graph g
					LEFT OUTER JOIN libraries l
					ON l.user_id = g.user_id2 AND l.private = 0
					LEFT OUTER JOIN network_users nu
					ON nu.user_id1 = g.user_id1 AND nu.user_id2 = l.user_id
					WHERE g.user_id1 = ?
					AND u.user_id = g.user_id2
					AND u.invisible = 0
					AND nu.user_id2 IS NULL
					GROUP BY g.user_id2
					ORDER BY g.distance ASC';
			
			$res = $this->db->query($sql, array($user_id));
			
			foreach($res as &$r) {
				if (is_null($r['full_name'])) $r['full_name'] = '';
				$r['url'] = $this->get_public_uri($r['user_id'], $r['full_name']);
			}
			
			unset($r);
			return $res;
		}
		else
			return false;
	}

	public function get_public_uri($user_id=null, $full_name=null) {
		if (HOST_NAME == HOST_PASSAGEO) {
			if (!empty($user_id))
				return Util::simple_encrypt((int)$user_id);
			else
				return false;
		}
		else {
			if (!empty($user_id) && !empty($full_name))
				return str_replace(' ', '-', preg_replace('/[^\da-z]/i', '', $full_name).'-'.Util::simple_encrypt((int)$user_id));
			else
				return false;
		}
	}

	public function get_id_from_uri($uri=null) {
		if (HOST_NAME == HOST_PASSAGEO) {
			if (isset($uri)) {
				return Util::simple_decrypt($uri);
			}
		}
		else {
			if (isset($uri)) {
				return Util::simple_decrypt(substr($uri, strrpos($uri, '-')+1, strlen($uri)));
			}	
		}
	}

	public function get_notifications($user_id=null, $pending=false, $limit=25, $read_only=false) {
		if (is_numeric($user_id)) {
			$sql = 'SELECT n.user_id2 as user_id, t.description, UNIX_TIMESTAMP(n.date_created) as date_created, n.is_read,
					n.notification_type_id,  IF(u.full_name IS NULL, CONCAT(u.first_name, \' \', u.last_name), u.full_name) AS full_name, u.profile_image
					FROM notifications n, notification_types t, users u
					WHERE t.notification_type_id = n.notification_type_id
					AND u.user_id = n.user_id2 AND n.user_id1 = ?';
			if ($pending) $sql .= ' AND n.is_read = 0 ';
			elseif ($read_only)  $sql .= ' AND n.is_read = 1 ';
			$sql .= ' ORDER BY is_read ASC, date_created DESC';
			if (is_numeric($limit)) $sql .= ' LIMIT '.$limit;
			$res = $this->db->query($sql, array($user_id));
			foreach($res as &$r) {
				$r['nid'] =  $this->get_public_uri($r['user_id'], $r['full_name']);
				$r['time'] = Util::get_verbal_time_ago($r['date_created']);
				unset($r['user_id']);
			}
			unset($r);
			return $res;
		}
	}

	public function get_notifications_count($user_id=null, $pending=false) {
		if (is_numeric($user_id)) {
			$sql = 'SELECT count(*) as notifications_count
					FROM notifications n, users u
					WHERE n.user_id1 = ? AND n.user_id2 = u.user_id';
			if ($pending) $sql .= ' AND n.is_read = 0';
			$res = $this->db->query($sql, array($user_id), 3);
			return empty($res[0]['notifications_count']) ? 0 : $res[0]['notifications_count'];
		}
	}

	public function set_as_read($user_id1=null, $user_id2=null) {
		if (is_numeric($user_id1)) {
			$sql = 'UPDATE notifications SET is_read = 1, date_marked_read = now()
					WHERE user_id1 = ?';
			$this->db->execute($sql, array($user_id1));
		}
	}

}

?>
