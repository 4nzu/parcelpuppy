<?php

class UserWidgets {
	private $user_id = null;

	function __construct($user_id = null) {
		
		if (is_int($user_id) && $user_id > 0) $this->user_id = $user_id;
		else $this->user_id = $_SESSION['user']->id;

		import('Zappy.Cache');
		$this->db = DB::instance();
	}

	public function generate_widget_id($widget_id, $user_id) {
		if (isset($user_id) && is_numeric($user_id) &&
			isset($widget_id) && is_numeric($widget_id)) {
			return Util::encode($_SESSION['user']->id, $widget_id);
		}
		else {
			return false;
		}
	}

	public function get_user_id($encoded_widget_id) {
		Util::decode($encoded_widget_id, $user_id, $widget_integer_id);
		return $user_id;
	}

	public function update_widget($key=null, $but=null, $and=null, $user_id=null) {
		
		if (empty($user_id)) $user_id = $_SESSION['user']->id;
		if (empty($user_id)) return false;

		$sql = 'SELECT widget_id FROM users_widgets WHERE user_id = ?';
		$res = $this->db->query($sql, array($_SESSION['user']->id));

		$widget_id = false;
		if (isset($res[0]['widget_id'])) {
			$but = explode(',', urldecode((string)$but));
			$new_but = '';
			foreach($but as $b) {
				// check if its a custom article
				if (!is_numeric($b)) {
					$uid_ = null;
					$aid_ = null;
					Util::decode($b, $uid_, $aid_);
					if ($uid_ == $user_id) {
						$sql = 'DELETE FROM users_custom_articles WHERE user_id=? AND article_id=?';
						$this->db->execute($sql, array($user_id, $aid_));
					}
				}
				else {
					if (empty($new_but)) $new_but = $b; else $new_but .= ','.$b;
				}
			}
			$widget_id = $res[0]['widget_id'];
			$this->invalidate_widget_cache($widget_id, $user_id);

			$sql = 'UPDATE users_widgets SET keywords=?, but_articles=?, and_articles=?, last_modified=now(), active=1 WHERE user_id=? AND widget_id=?';
			$this->db->execute($sql, array((string)$key, $new_but, urldecode((string)$and), $user_id, $widget_id));
			
		}
		else {
			$sql = 'INSERT INTO users_widgets (user_id, keywords, but_articles, and_articles, first_created, last_modified) VALUES(?, ?, ?, now(), now())';
			$widget_id = $this->db->insert($sql, array($user_id, (string)$key, urldecode((string)$but), urldecode((string)$and)));
		}

		$widget_id = $this->generate_widget_id($widget_id, $user_id);

		return $widget_id;
	}

	public function invalidate_widget_cache($widget_id, $user_id) {
		import('Zappy.Cache');
		$_c = new Cache();
		$_c->set('widget_'.$this->generate_widget_id($widget_id, $user_id), '', -365*24*60*60);
	}

	public function load_widget($widget_id=null, $key=null, $but=null, $and=null) {

		import('Zappy.Cache');
		$_c = new Cache();

		$days_back = 50000;
		$max_papers = 500;
		$user_id = 0;
		$show_related = false;
		$people_cluster = 3;

		$cache_key = 'widget_'.$widget_id;
		$articles = $_c->get($cache_key);
		//error_log('NUMBER OF ARTICLES FOR KEY='.$cache_key.' IS '.count($artiles));

		if (!$articles) {

			if (isset($widget_id)) {
				$user_id = null;
				$widget_integer_id = null;

				Util::decode($widget_id, $user_id, $widget_integer_id);
				$sql = 'SELECT keywords, but_articles, and_articles FROM users_widgets
						WHERE user_id = ? AND widget_id = ?';
				$widget_info = $this->db->query($sql, array($user_id, $widget_integer_id));
				if (isset($widget_info[0]['keywords'])) {
					$key = $widget_info[0]['keywords'];
					$and = $widget_info[0]['and_articles'];
					$but = $widget_info[0]['but_articles'];
				}
				else {
					return false;
				}
			}

			import('library.API');
			$api = new API('v1');

			$search_term = $key;
			$articles = $api->do_pubmed_search($search_term, $days_back, $max_papers, $show_related, $people_cluster, $user_id);

			if (isset($but) && !empty($but)) {
				$but_array = array_flip(explode(',', $but));
				$new_arts = array();
				foreach ($articles as $a) {
					if (isset($but_array[$a['article_id']])) continue;
					else $new_arts[] = $a;
				}
				$articles = $new_arts;
			}
			$arts = array();
			if (isset($and) && !empty($and)) {
				$and_array = explode(',', $and);

				import('Zappy.MDB');
				$_mon = MDB::instance();
				foreach ($and_array as $a) {
					$_mon->addID((int)$a);
				}
				$arts = $_mon->getArticles();
				foreach($arts as &$a) {
					$a['article_id'] = (string)$a['_id'];
				}
				unset($a);
			}
			
			$articles = array_merge($articles, $arts);

			// load custom articles
			$sql = 'SELECT article_id, title, source AS journal_title, author_string, article_url, article_date, article_date AS date_created
					FROM users_custom_articles WHERE user_id = ?';
			$res = $this->db->query($sql, array($_SESSION['user']->id));
			$custom_arts = array();
			foreach($res as $r) {
				$r['article_id'] = Util::encode($_SESSION['user']->id, (int)$r['article_id']);
				$r['_id'] = $r['article_id'];
				$custom_arts[$r['article_id']] = $r;
			}
			$articles = array_merge($articles, $custom_arts);
			
			$articles = Util::array_sort($articles, 'date_created', 'desc');
			$_c->set($cache_key, $articles, 3600*12);
		}
		return $articles;
	}

	public function check_widget($user_id=null) {
		
		if (empty($user_id)) $user_id = $_SESSION['user']->id;
		if (empty($user_id)) return false;

		$sql = 'SELECT keywords, widget_id, and_articles, but_articles FROM users_widgets WHERE user_id = ?';
		$res = $this->db->query($sql, array($user_id));
		$widget_out = array();

		if (isset($res[0]['widget_id'])) {
			$widget_out['widget_int_id'] = $res[0]['widget_id'];
			$widget_out['widget_id'] = $this->generate_widget_id($res[0]['widget_id'], $user_id);
			$widget_out['keywords'] = $res[0]['keywords'];
			$widget_out['and'] = $res[0]['and_articles'];
			$widget_out['but'] = $res[0]['but_articles'];
		} else {
			// enerate placeholder widget record and return the new id
			$sql = 'INSERT INTO users_widgets (user_id, first_created, last_modified) VALUES(?, now(), now())';
			$widget_id = $this->db->insert($sql, array($user_id));
			$widget_out['widget_int_id'] = $widget_id;
			$widget_out['widget_id'] = $this->generate_widget_id($widget_id, $user_id);
			$widget_out['keywords'] = '';
			$widget_out['and'] = '';
			$widget_out['but'] = '';
		}
		return $widget_out;
	}
}

?>
