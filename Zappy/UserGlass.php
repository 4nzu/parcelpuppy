<?php

class UserGlass {

	function __construct() {
		define('GLASS_DEVICE_ID', 3);

		import('Zappy.Util');
		import('Zappy.Cache');

		$this->db = DB::instance();
	}

	public function check_glass_connected($user_id, &$token = null) {
		if (is_numeric($user_id)) {
			$sql = 'SELECT glass_id FROM users_glass WHERE user_id = ?';
			$res = $this->db->query($sql, array($user_id));
			
			$token = $res[0]['glass_id'];

			return isset($res[0]['glass_id']) ? true : false;
		}
	}

	public function disconnect($glass_id, $user_id) {
		$sql = 'DELETE FROM users_glass WHERE glass_id = ? and user_id = ?';
		$this->db->execute($sql, array($glass_id, $user_id));

		return true;
	}

	public function connect($token, $uid) {
		if (isset($token) && isset($uid)) {

			import('Zappy.Cache');
			$_c = new Cache();

			$payload = $_c->get($token);

			Util::decode($payload, $time_sent, $user_id);
			if (time()-$time_sent <= GLASS_CONNECT_CACHE_TIME) {
				$_u = new User($user_id);
				if ($_u->user_id_exists($user_id) && !$this->check_glass_connected($user_id)) {
					$_u->attachDeviceToUser($uid, GLASS_DEVICE_ID);
					$full_name = $u->full_name;
					if (empty($full_name)) $full_name = $_u->first_name.' '.$_u->last_name;
					if (empty($full_name)) $full_name = '';

					if (strstr($_u->profile_image,'http')) $profile_image = $_u->profile_image;
					else $profile_image = SITE_URL.$_u->profile_image;

					$sql = 'INSERT INTO users_glass(user_id, glass_id, device_id, registered_on) VALUES(?, ?, ?, now())';
					$this->db->execute($sql, array($user_id, $token, $uid));
					return array('profile_image' => $profile_image, 'full_name' => $full_name, 'affiliation' => is_null($_u->affiliation) ? '' : $_u->affiliation);
				}
			}
		}
		return false;
	}
}
?>