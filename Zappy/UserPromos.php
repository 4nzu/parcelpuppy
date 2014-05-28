<?php

class UserPromos {
	private $user_id = null;

	function __construct($user_id = null) {
		
		if (is_int($user_id) && $user_id > 0) $this->user_id = $user_id;
		else $this->user_id = $_SESSION['user']->id;

		import('Zappy.Util');
		import('Zappy.Cache');
		$this->db = DB::instance();
	}

	public function get_id_by_code($promo_code) {
		$sql = 'SELECT promo_id FROM promos WHERE promo_code = ? AND (good_until > now() OR good_until IS NULL)';
		$res = $this->db->query($sql, array($promo_code), 3600);
		if (isset($res[0]['promo_id'])) return $res[0]['promo_id'];
		else return false;
	}

	public function apply_promo_code($promo_id=null, $promo_code=null, $user_id=null) {
		if (!empty($user_id) && (!empty($promo_id) || !empty($promo_code))) {
			$sql = 'REPLACE INTO users_promos(user_id, promo_id) VALUES(?, ?)';
			$this->db->execute($sql, array($user_id, $promo_id));
			return true;
		}
		else return false;
	}

	public function get_promo_by_type($user_id=null, $promo_type=null) {
		if (!empty($user_id) && is_numeric($promo_type)) {
			$sql = 'SELECT up.promo_id, up.created_on, p.promo_type
					FROM users_promos up, promos p
					WHERE p.promo_id = up.promo_id AND up.user_id = ? AND p.promo_type = ?';
			$res = $this->db->query($sql, array($user_id, $promo_type));
			return $res;
		}
	}

}

?>
