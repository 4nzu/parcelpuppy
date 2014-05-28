<?php

class UserNotifications {

	function __construct($user_id = null) {
		import('Zappy.Util');
		import('Zappy.Cache');
		$this->db = DB::instance();
	}

	public function can_email($noti_type_id, $user_id1, $user_id2, $anonymous) {
		if ((int)$anonymous == 1) return false;
		if ($user_id1 == $user_id2) return false;

		// translate into email message type id
		if ($noti_type_id == NOTI_SUBSCRIBED) $message_type_id = 2;
		elseif ($noti_type_id == NOTI_NEW_COMMENT || $noti_type_id == NOTI_NEW_RESPONSE) $message_type_id = 5;
		elseif ($noti_type_id == NOTI_COMMENT_SUSPENDED) $message_type_id = 6;
		elseif ($noti_type_id == NOTI_QUESTION_ANSWERED) $message_type_id = 7;
		elseif ($noti_type_id == NOTI_QUESTION_POSTED) $message_type_id = 8;
		elseif ($noti_type_id == NOTI_ANSWER_ACCEPTED) $message_type_id = 9;
		else return false;

		$res = $this->db->query('SELECT system FROM email_settings WHERE active = 1 AND message_type_id = ?', array($message_type_id));
		if ((int)$res[0]['system'] == 1) return true;

		$sql = 'SELECT active FROM users_email_settings WHERE user_id=? AND message_type_id=?';
		$res = $this->db->query($sql, array($user_id1, $message_type_id));

		return (isset($res[0]['active']) && (int)$res[0]['active'] == 1) ? true : false;
	}

	public function generate_notificaion($user_id1 = false, $user_id2 = false, $noti_type_id = false, $meta_id = null, $meta_table = null, $anonymous = null) {
		if (!is_numeric($noti_type_id)) return false;
		if (!is_numeric($user_id1)) return false;
		if (!is_numeric($user_id2)) return false;

		if ((int)$anonymous == 1) $user_id2 = 0;

		// generate notification
		$sql = 'INSERT INTO notifications(user_id1, user_id2, notification_type_id, meta_id, meta_table)
				VALUES(?, ?, ?, ?, ?)
				ON DUPLICATE KEY UPDATE is_read=0, date_created = now(), date_marked_read = NULL';
		$this->db->execute($sql, array($user_id1, $user_id2, $noti_type_id, $meta_id, $meta_table));

		if ($this->can_email($noti_type_id, $user_id1, $user_id2, $anonymous) && ($user_id1 < 4 || HOST_ROLE != HOST_DEV)) { // to prevent accidental spam

			$sql = 'SELECT first_name, last_name, email, user_id FROM users WHERE user_id=?';
			$target_res = $this->db->query($sql, array($user_id1));

			$sql = 'SELECT first_name, last_name, email FROM users WHERE user_id=?';
			$follow_res = $this->db->query($sql, array($user_id2));

			import('Zappy.User');
			$_u = new User();

			$data = array('target_user_name' => $target_res[0]['first_name'].' '.$target_res[0]['last_name'],
				          'target_user_email' => $target_res[0]['email'],
				          'token' => $_u->generateToken($target_res[0]['user_id']),
				          'follower_user_name' =>  $follow_res[0]['first_name'].' '.$follow_res[0]['last_name']);

			import('library.amazonAPI');
			$amazonAPI = new amazonAPI();
			if ($noti_type_id == NOTI_SUBSCRIBED) $amazonAPI->sesOnFollow($data);
			elseif ($noti_type_id == NOTI_NEW_COMMENT || $noti_type_id == NOTI_NEW_RESPONSE) {
				import('library.Comment');
				$_co = new Comment();

				$comment = $_co->get_comment_meta($meta_id);
				$data['comment'] = $comment['comment_body'];

				import('library.Essay');
				$_es = new Essay();
				$data['essay_uri'] = $_es->get_essay_uri($_co->get_essay_id($meta_id));

				$amazonAPI->sesOnComment($noti_type_id, $data);
			}
			elseif ($noti_type_id == NOTI_QUESTION_ANSWERED) {
				import('library.Comment');
				$_co = new Comment();
				$question_comment_id = $_co->get_response_id($meta_id);
				$comment = $_co->get_comment_meta($question_comment_id);

				$data['comment_uri'] = $_co->get_uri($comment['title'], $question_comment_id);

				$amazonAPI->sesOnComment($noti_type_id, $data);
			}
			elseif ($noti_type_id == NOTI_QUESTION_POSTED) {
			}
		}
	}

}
?>