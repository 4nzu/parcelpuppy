<?php

class Requests {

	function __construct() {
		import('Zappy.Util');
		$this->db = DB::instance();
	}

	public function get_requests($user_id = null, $request_id = null) {
		$sql = 'SELECT u.email, r.description, r.created_on, r.puppy_fee,
					rg.region_name, r.city, p.name, p.price, rp.quantity
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

	public function get_request($request_id = null) {
		if (is_numeric($request_id)) {
			$sql = 'SELECT r.request_id, r.description, r.created_on, rg.region_name, st.description AS shipping, u.first_name, u.last_name, 
				    	p.name, p.brand, p.details, rp.quantity
					FROM users u, requests r, products p, requests_products rp, regions rg, shipping_types st
					WHERE u.user_id = r.user_id
					AND rp.product_id = p.product_id
					AND rp.request_id = r.request_id
					AND rg.region_id = r.region_id
					AND st.shipping_type_id = r.shipping_type_id
					AND r.request_id = ?';
			$res = $this->db->query($sql, array($request_id));
			
			if (isset($res[0]['request_id'])) {
				
				$res_out['request_id']  = $res[0]['request_id'];
				$res_out['description'] = $res[0]['description'];
				$res_out['region_name'] = $res[0]['region_name'];
				$res_out['shipping']    = $res[0]['shipping'];
				$res_out['first_name']  = $res[0]['first_name'];
				$res_out['last_name']   = $res[0]['last_name'];

				foreach($res as $r) {
					$res_out['items'][] = array('name' => $r['name'],
											   'brand' => $r['brand'],
										     'details' => $r['details'],
										    'quantity' => $r['quantity']);
				}

				return $res_out;
			}
			else {
				return false;
			}
		}
		else 
			return false;
	}

}