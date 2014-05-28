<?php

class Requests {

	function __construct() {
		import('Zappy.Util');
		$this->db = DB::instance();
	}

	public function get_requests($user_id = null) {
		$sql = 'SELECT u.email, r.description, r.created_on, r.puppy_fee, rg.region_name, r.city, p.name, p.price
				FROM users u, requests r, products p, requests_products rp, regions rg
				WHERE u.user_id = r.user_id
				AND rp.product_id = p.product_id
				AND rp.request_id = r.request_id
				AND rg.region_id = r.region_id';

		if (isset($user_id)) { 
			$sql .= ' AND u.user_id = ?';
			$res = $this->db->query($sql, array($user_id));
		}
		else {
			$res = $this->db->query($sql);
		}
		
		return $res;
	}

}