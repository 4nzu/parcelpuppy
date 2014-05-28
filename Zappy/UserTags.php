<?php

class UserTags {

	public $tag_lst = null;
	public $tag_count = null;
	private $user_id = null;

	function __construct($user_id = null) {
		
		if (is_int($user_id) && $user_id > 0) $this->user_id = $user_id;
		else $this->user_id = $_SESSION['user']->id;

		import('Zappy.Util');
		import('Zappy.Cache');
		$this->db = DB::instance();
	}

	public function get_tag_count($user_id, $article_id=null) {
		if (isset($article_id) && !empty($article_id)) {
			$sql = 'SELECT count(*) AS tag_count FROM users_tags u, library_tags l
					WHERE u.user_id = ?
					AND l.article_id = ?
					AND u.user_id = l.user_id
					AND u.tag_id = l.tag_id';
			$res = $this->db->query($sql, array($user_id, $article_id));
		}
		else {
			$sql = "SELECT count(*) as tag_count FROM users_tags WHERE user_id = ?";
			$res = $this->db->query($sql, array($user_id));
		}
		return $res[0]['tag_count'];
	}

	public function get_tag_by_id($user_id, $tag_id) {
		if (isset($user_id) && isset($tag_id)) {
			$sql = "SELECT tag_name from users_tags where user_id=? AND tag_id=?";
			$res = $this->db->query($sql, array($user_id, $tag_id));
			return (count($res) > 0 ) ? $res[0]['tag_name'] : false;
		}
	}

	public function get_tags($user_id, $like_tag=null, $article_id=null, $comment_id=null, $order_by=null) {
		if (isset($order_by) && $order_by == 0 && !isset($comment_id)) $order_by = 'u.tag_name ASC'; else $order_by = 'a.tag_articles DESC';
		if (!isset($article_id) && isset($comment_id)) $order_by = 'u.tag_name DESC';
		if (isset($like_tag) && strlen($like_tag) > 0) {
			$sql = "SELECT u.tag_id, u.tag_name
					FROM users_tags u
					WHERE u.user_id = ?
					AND u.tag_name
					LIKE '".$like_tag."%'";
			$res = $this->db->query($sql, array($user_id));
		}
		elseif (isset($article_id) && !empty($article_id)) {
			if (strstr($article_id, ',')) {
				$multiple_ids = true; 
				$_connect = mysql_connect(DB_HOST, DB_USER, DB_PASS);
				mysql_select_db(DB_NAME);
				$article_id_str = mysql_real_escape_string(strtolower($article_id));
				mysql_close($_connect);
			}
			else $multiple_ids = false;
			
			if ($multiple_ids) $sql .= 'SELECT DISTINCT '; else $sql = 'SELECT ';
			$sql .= 'u.tag_id, u.tag_name
					FROM users_tags u, library_tags l
					WHERE u.user_id = ?
					AND l.user_id = u.user_id
					AND l.tag_id = u.tag_id';
			if ($multiple_ids) $sql .= ' AND l.article_id IN ('.$article_id_str.')'; else $sql .= ' AND l.article_id = ?';
			if ($multiple_ids) $res = $this->db->query($sql, array($user_id)); else $res = $this->db->query($sql, array($user_id, $article_id));
		}
		elseif (isset($comment_id) && !empty($comment_id)) {
			$sql = 'SELECT u.tag_id, u.tag_name
					FROM users_tags u, comments_tags c
					WHERE u.user_id = ? AND c.comment_id = ?
					AND u.tag_id = c.tag_id
					ORDER BY '.$order_by;
			$res = $this->db->query($sql, array(QUESTION_TAG_USER, $comment_id));
		}
		elseif ($user_id == QUESTION_TAG_USER && empty($comment_id)) {
			$sql = 'SELECT u.tag_id, u.tag_name
					FROM users_tags u
					WHERE u.user_id = ?
					ORDER BY '.$order_by;

			$res = $this->db->query($sql, array(QUESTION_TAG_USER));
		}
		else {
			$sql = "SELECT u.tag_id, u.tag_name, a.tag_articles
					FROM users_tags u, (SELECT tag_id, count(article_id) AS tag_articles
										FROM library_tags
										WHERE user_id = ?
										GROUP BY tag_id) a
					WHERE u.user_id = ?
					AND u.tag_id = a.tag_id
					ORDER BY ".$order_by;
			$res = $this->db->query($sql, array($user_id, $user_id));
		}
		return $res;
	}

	private function create_single_tag($user_id, $tag_name) {
		if (!$this->tag_named_exists($user_id, $tag_name)) {
			$new_tag_id = $this->get_next_tag_id($user_id);
			$sql = "INSERT INTO users_tags(user_id, tag_id, tag_name) VALUES(?, ?, ?)";
			$this->db->insert($sql, array($user_id, $new_tag_id, $tag_name));
			return $new_tag_id;
		}
		else {
			return false;
		}
	}

	public function create_new_tag($user_id, $tag_names, $attach=false, $article_id=null) {
		if (is_array($tag_names))
			foreach($tag_names as $tag_name) {
				if (($new_tag_id = $this->tag_named_exists($user_id, $tag_name)) && $attach) 
					$this->attach_tag($user_id, $new_tag_id, $article_id, strtolower($tag_name) == 'private' ? true : false);
				elseif(($new_tag_id = $this->create_single_tag($user_id, $tag_name)) && $attach)
					$this->attach_tag($user_id, $new_tag_id, $article_id, strtolower($tag_name) == 'private' ? true : false);
			}
		else
			if (($new_tag_id = $this->tag_named_exists($user_id, $tag_names)) && $attach)
					$this->attach_tag($user_id, $new_tag_id, $article_id, strtolower($tag_names) == 'private' ? true : false);
				elseif(($new_tag_id = $this->create_single_tag($user_id, $tag_names)) && $attach)
					$this->attach_tag($user_id, $new_tag_id, $article_id, strtolower($tag_names) == 'private' ? true : false);
	}

	public function create_new_comments_tag($tag_names, $attach=false, $comment_id=null) {
		$user_id = QUESTION_TAG_USER;
		if (is_array($tag_names)) {
			foreach($tag_names as $tag_name) {
				if (($new_tag_id = $this->tag_named_exists($user_id, $tag_name)) && $attach) {
					$this->attach_comment_tag($new_tag_id, $comment_id);
				}
				elseif (($new_tag_id = $this->create_single_tag($user_id, $tag_name)) && $attach) {
					$this->attach_comment_tag($new_tag_id, $comment_id);
				}
			}
		}
		else {
			if (($new_tag_id = $this->tag_named_exists($user_id, $tag_names)) && $attach)
					$this->attach_comment_tag($new_tag_id, $comment_id);
				elseif(($new_tag_id = $this->create_single_tag($user_id, $tag_names)) && $attach)
					$this->attach_comment_tag($new_tag_id, $comment_id);
		}
	}

	public function remove_tag($user_id, $tag_id) {
		if (isset($tag_id)) {
			$sql = "DELETE FROM users_tags WHERE user_id = ? AND tag_id = ?";
			$this->db->execute($sql, array($user_id, $tag_id));

			$sql = "DELETE FROM library_tags WHERE user_id = ? AND tag_id = ?";
			$this->db->execute($sql, array($user_id, $tag_id));

			// if it was Private tag, clear it
			$sql = "UPDATE libraries SET private = 0 WHERE user_id = ? AND private = ?";
			$this->db->execute($sql, array($user_id, $tag_id));
		}
	}

	public function reset_comments_tags($comment_id) {
		$sql = "DELETE FROM comments_tags WHERE comment_id = ?";
		$this->db->execute($sql, array($comment_id));
	}

	public function reset_article_tags($user_id, $article_id) {
		if (isset($article_id)) {
			$sql = "DELETE FROM library_tags WHERE user_id = ? AND article_id = ?";
			$this->db->execute($sql, array($user_id, $article_id));

			// clear private tag
			$sql = "UPDATE libraries SET private = 0 WHERE user_id = ? AND article_id = ?";
			$this->db->execute($sql, array($user_id, $article_id));
		}
	}

	public function attach_comment_tag($tag_id, $comment_id) {
		if (!empty($tag_id) && is_numeric($tag_id) && !empty($comment_id)) {
			$sql = "INSERT IGNORE INTO comments_tags(tag_id, comment_id) VALUES(?, ?)";
			$this->db->execute($sql, array($tag_id, $comment_id));
		}
	}

	public function detach_comment_tag($tag_id, $comment_id) {
		$user_id = QUESTION_TAG_USER;
		if (!empty($user_id) && !empty($tag_id) && is_numeric($user_id) && is_numeric($tag_id) && !empty($comment_id)) {
			$sql = "DELETE FROM comments_tags WHERE user_id = ? AND tag_id = ? AND comment_id = ?";
			$this->db->execute($sql, array($user_id, $tag_id, $comment_id));
		}
	}

	public function attach_tag($user_id, $tag_id, $article_id, $private=false) {
		if (!empty($user_id) && !empty($tag_id) && is_numeric($user_id) && is_numeric($tag_id) && !empty($article_id)) {
			$sql = "INSERT IGNORE INTO library_tags(user_id, tag_id, article_id) VALUES(?, ?, ?)";
			$this->db->execute($sql, array($user_id, $tag_id, $article_id));

			if ($private) {
				// if it was Private tag, set it
				$sql = "UPDATE libraries SET private = ? WHERE user_id = ? AND article_id = ?";
				$this->db->execute($sql, array($tag_id, $user_id, $article_id));
			}
		}
	}

	public function detach_tag($user_id, $tag_id, $article_id) {
		if (!empty($user_id) && !empty($tag_id) && is_numeric($user_id) && is_numeric($tag_id) && !empty($article_id)) {
			$sql = "DELETE FROM library_tags WHERE user_id = ? AND tag_id = ? AND article_id = ?";
			$this->db->execute($sql, array($user_id, $tag_id, $article_id));

			// if it was Private tag, clear it
			$sql = "UPDATE libraries SET private = 0 WHERE user_id = ? AND article_id = ? AND private = ?";
			$this->db->execute($sql, array($user_id, $article_id, $tag_id));
		}
	}

	public function tag_named_exists($user_id, $tag_name) {
		$sql = "SELECT tag_id FROM users_tags WHERE user_id = ? AND tag_name = ?";
		$res = $this->db->query($sql, array($user_id, $tag_name));
		return count($res) == 0 ? false : $res[0]['tag_id'];
	}

	public function get_next_tag_id($user_id) {
		$sql = "SELECT tag_id FROM users_tags WHERE user_id = ? ORDER BY tag_id DESC LIMIT 1";
		$res = $this->db->query($sql, array($user_id));
		return count($res) == 0 ? 1 : $res[0]['tag_id']+1;
	}

	public function find_tagged($user_id, $articles) {
		if (isset($user_id) && is_array($articles)) {
			$in_string = '';
			$i == 0;
			foreach($articles as $art) {
				if ($i++ == 0) $in_string = $art['_id'];
				else $in_string .= ','. $art['_id'];
			}
			$sql = 'SELECT DISTINCT article_id FROM library_tags
					WHERE user_id=? AND article_id IN ('.$in_string.')';
			$res = $this->db->query($sql, array($user_id));

			$articles_out = array();
			foreach($res as $r)
				$articles_out[] = $r['article_id'];
			return $articles_out;
		}
	}

	public function find_untagged($user_id, $articles) {
		if (isset($user_id) && is_array($articles)) {
			$in_string = '';
			$i == 0;
			foreach($articles as $art) {
				if ($i == 0) $in_string = $art['_id'];
				else $in_string .= ','. $art['_id'];
				$i++;
			}
			$sql = 'SELECT DISTINCT l.article_id, t.user_id FROM libraries l
					LEFT OUTER JOIN library_tags t
					ON t.user_id = l.user_id AND t.article_id = l.article_id
					WHERE l.user_id=? AND l.article_id IN ('.$in_string.')
					AND t.user_id IS NULL';

			$res = $this->db->query($sql, array($user_id));
			$articles_out = array();
			foreach($res as $r)
				$articles_out[] = $r['article_id'];
			return $articles_out;
		}
	}

}

?>
