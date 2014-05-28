<?php

class UserJournal {
	public $id = null;

    function __construct() {
		$this->db = DB::instance();
	}

	// returns true or false
	public function exists($a_id = null, $p_u_id = null, $p_id = null) {
		if (empty($a_id)) return false;

		$sql = 'SELECT annotation_id, by_user_id FROM protocol_steps_annotations
				WHERE annotation_id = ?
				AND protocol_id = ?
				AND protocol_user_id = ?';

		$res = $this->db->query($sql, array($a_id, $p_id, $p_u_id));

		if (isset($res[0]['annotation_id']))
			return $res;
		else
			return false;
	}

	public function get_next_record_id($p_u_id = null, $p_id = null) {
		if (empty($p_id)) return false;

		$sql = 'SELECT MAX(annotation_id) AS annotation_id FROM protocol_steps_annotations
				WHERE protocol_id = ?
				AND protocol_user_id = ?';
		
		$res = $this->db->query($sql, array($p_id, $p_u_id));

		if (is_numeric($res[0]['annotation_id']))
			return (int)$res[0]['annotation_id']+1;
		else
			return 1;
	}

}
?>