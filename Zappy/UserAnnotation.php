<?php

class UserAnnotation {
	public $id = null;

    function __construct($a_id=null) {
		$this->db = DB::instance();
		$this->id = $a_id;
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

	public function get_next_annotation_id($p_u_id = null, $p_id = null) {
		$sql = 'SELECT MAX(annotation_id) AS annotation_id FROM protocol_steps_annotations
				WHERE protocol_id = ?
				AND protocol_user_id = ?';
		
		$res = $this->db->query($sql, array($p_id, $p_u_id));

		if (is_numeric($res[0]['annotation_id']))
			return (int)$res[0]['annotation_id']+1;
		else
			return 1;
	}

	public function post($annotation, $u_id = null, $p_u_id = null, $p_id = null, $s_id = null, $a_id = null) {
		if (is_null($p_id)) return false;
		if (isset($s_id) && is_numeric($s_id)) $step_id = $s_id;
		else $step_id = null;

		if (!isset($a_id) || !is_numeric($a_id)) {
			$a_id = $this->get_next_annotation_id($p_u_id, $p_id);
		}
		
		$sql = 'REPLACE INTO protocol_steps_annotations(annotation_id,
														protocol_id,
														protocol_user_id,
														step_id,
														by_user_id,
														annotation,
														last_updated)
				VALUES(?, ?, ?, ?, ?, ?, now())';
		$this->db->execute($sql, array($a_id, $p_id, $p_u_id, $step_id, $u_id, $annotation));

		return $a_id;
	}

	public function load_protocol_annotations($u_id = null, $p_u_id = null, $p_id = null, $s_id = null) {
		if (is_null($p_id)) return false;

		$sql = 'SELECT p.annotation_id, p.step_id,
					   p.annotation,
					   IF(p.by_user_id = ?, 1, 0) AS can_edit,
					   p.created_date, p.last_updated, p.score_up, p.score_down,
					   u.profile_image, CONCAT(u.first_name, \' \', u.last_name) AS full_name,
					   u.affiliation, u.user_id
				FROM protocol_steps_annotations p, users u
				WHERE p.protocol_id = ?
				AND p.protocol_user_id = ?
				AND u.user_id = p.by_user_id';
		
		if (!empty($s_id)) {
			$sql .= ' AND step_id = ? ORDER BY p.created_date DESC';
			$res = $this->db->query($sql, array($u_id, $p_id, $p_u_id, $s_id));
		}
		else {
			$sql .= ' ORDER BY p.step_id ASC, p.created_date DESC';
			$res = $this->db->query($sql, array($u_id, $p_id, $p_u_id));
		}

		return $res;
	}

	public function load_protocol_level_annotations_only($u_id = null, $p_u_id = null, $p_id = null) {
		if (is_null($p_id)) return false;

		$sql = 'SELECT p.annotation_id, p.step_id,
					   p.annotation,
					   IF(p.by_user_id = ?, 1, 0) AS can_edit,
					   p.created_date, p.last_updated, p.score_up, p.score_down,
					   u.profile_image, CONCAT(u.first_name, \' \', u.last_name) AS full_name,
					   u.affiliation, u.user_id
				FROM protocol_steps_annotations p, users u
				WHERE p.protocol_id = ?
				AND p.protocol_user_id = ?
				AND u.user_id = p.by_user_id';
		
		$sql .= ' AND step_id IS NULL ORDER BY p.created_date DESC';
		$res = $this->db->query($sql, array($u_id, $p_id, $p_u_id));

		return $res;
	}

	public function get_annotations_count($p_u_id = null, $p_id = null) {
		if (is_null($p_id)) return false;

		$sql = 'SELECT count(p.annotation_id) ann_count
				FROM protocol_steps_annotations p
				WHERE p.protocol_id = ?
				AND p.protocol_user_id = ?';

		$res = $this->db->query($sql, array($p_id, $p_u_id));

		return is_numeric($res[0]['ann_count']) ? $res[0]['ann_count'] : '0';
	}

	public function delete($a_id = null, $u_id = null, $p_u_id = null, $p_id = null) {
		if (empty($a_id)) return false;

		$sql = 'DELETE FROM protocol_steps_annotations
				WHERE protocol_id = ?
				AND protocol_user_id = ?
				AND by_user_id = ?
				AND annotation_id = ?';

		$this->db->execute($sql, array($p_id, $p_u_id, $u_id, $a_id));
		return true;
	}

	public function vote($a_id = null, $p_u_id = null, $p_id = null, $v_u_id = null, $vote = 0) {
		if (empty($a_id) || empty($v_u_id) || empty($p_id)) return false;

		$do_vote = false;

		// $a_id = null, $p_u_id = null, $p_id = null, $v_u_id = null
		$existing_vote = $this->vote_exists($a_id, $p_u_id, $p_id, $v_u_id);
		if ($existing_vote !== false) {
			if ($existing_vote != $vote) {
				$this->remove_old_vote($a_id, $p_u_id, $p_id, $v_u_id, $existing_vote);
				$do_vote = true;
			}
		}
		else {
			$do_vote = true;
		}

		if ($do_vote) {
			$sql = 'INSERT INTO protocol_steps_annotations_votes(protocol_id,
															 protocol_user_id,
															 annotation_id,
															 vote_user_id,
															 created_date,
															 vote)
					VALUES(?, ?, ?, ?, now(), ?)';
			$this->db->execute($sql, array($p_id, $p_u_id, $a_id, $v_u_id, $vote));

			if ($vote == 1) {
				$sql1 = 'UPDATE protocol_steps_annotations
						 SET score_up = score_up + 1
						 WHERE protocol_id = ?
						 AND protocol_user_id = ?
						 AND annotation_id = ?';
			}
			elseif ($vote == -1) {
				$sql1 = 'UPDATE protocol_steps_annotations
						 SET score_down = score_down + 1
						 WHERE protocol_id = ?
						 AND protocol_user_id = ?
						 AND annotation_id = ?';
			}
			$this->db->execute($sql1, array($p_id, $p_u_id, $a_id));
		}


		$sql = 'SELECT score_up, score_down
				FROM protocol_steps_annotations
		     	WHERE protocol_id = ?
			 	AND protocol_user_id = ?
			 	AND annotation_id = ?';
	 	$res = $this->db->query($sql, array($p_id, $p_u_id, $a_id));
 	
	 	if (count($res) <= 0) {
	 		$res = array('score_up' => 0, 'score_down' => 0);
	 	}

		return $res;
	}

	public function remove_old_vote($a_id = null, $p_u_id = null, $p_id = null, $v_u_id = null, $existing_vote = 0) {
		if (empty($a_id) || empty($v_u_id)) return false;

		$sql = 'DELETE FROM protocol_steps_annotations_votes
				WHERE protocol_id = ?
				AND protocol_user_id = ?
				AND annotation_id = ?
				AND vote_user_id = ?';
		$this->db->execute($sql, array($p_id, $p_u_id, $a_id, $v_u_id));

		if ($existing_vote == 1) {
			$sql = 'UPDATE protocol_steps_annotations
					SET score_up = IF(score_up - 1 < 0, 0, score_up - 1)
					WHERE protocol_id = ?
					AND protocol_user_id = ?
					AND annotation_id = ?';
		}
		elseif ($existing_vote == -1) {
			$sql = 'UPDATE protocol_steps_annotations
					SET score_down = IF(score_down - 1 < 0, 0, score_down - 1)
					WHERE protocol_id = ?
					AND protocol_user_id = ?
					AND annotation_id = ?';
		}
		$this->db->execute($sql, array((int)$p_id, (int)$p_u_id, (int)$a_id));
		return true;
	}

	public function vote_exists($a_id = null, $p_u_id = null, $p_id = null, $v_u_id = null) {
		if (empty($a_id) || empty($v_u_id)) return false;

		$sql = 'SELECT vote FROM protocol_steps_annotations_votes
				WHERE protocol_id = ?
				AND protocol_user_id = ?
				AND annotation_id = ?
				AND vote_user_id = ?';
		$res = $this->db->query($sql, array($p_id, $p_u_id, $a_id, $v_u_id));
		
		if (isset($res[0]['vote'])) return (int)$res[0]['vote'];
		else return false;
	}

}
?>