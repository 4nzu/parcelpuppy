<?php

import('library.amazonAPI');

class UserFiles {

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

	public function get_stats($file_type_id = PDF_FILE_TYPE_ID) {
		$sql = 'SELECT u.user_id, u.email, u.created, u.last_login, u.email, u.first_name, u.last_name, ui.last_invite_date, ui.last_reminder_date,
				       IF(ui.last_invite_date > ui.last_reminder_date, ui.last_invite_date, ui.last_reminder_date) AS delta, count(*) AS file_count
					FROM users_files f, users u, users_invitations ui
					WHERE f.file_type = ?
					AND u.user_id = f.user_id AND ui.user_id = u.user_id
					GROUP BY f.user_id
					ORDER BY file_count DESC';
		return $this->db->query($sql, array($file_type_id), 600);
	}

	public function get_file_count($user_id, $file_type = PDF_FILE_TYPE_ID) {
		$sql = "SELECT count(*) as file_count FROM users_files WHERE user_id = ? AND file_type = ?";
		$res = $this->db->query($sql, array($user_id, $file_type));
		return $res[0]['file_count'];
	}

	public function get_files($user_id, $file_type = PDF_FILE_TYPE_ID) {
		if (HOST_NAME == HOST_PASSAGEO) {
			$sql = "SELECT file_id, original_name, step_id, trek_id FROM users_files WHERE user_id = ? AND file_type = ?";
		}
		else {
			$sql = "SELECT file_id, original_name FROM users_files WHERE user_id = ? AND file_type = ?";	
		}
		$res = $this->db->query($sql, array($user_id, $file_type));
		return $res;
	}
    
    public function get_file($user_id, $article_id, $file_type = PDF_FILE_TYPE_ID) {
		$sql = "SELECT file_id, original_name, s3_file_name FROM users_files WHERE user_id = ? AND file_type = ? AND article_id = ?";
		$res = $this->db->query($sql, array($user_id, $file_type, $article_id));
        if(count($res)>0)
            return $res[0];
        else
            return 0;
	}
    
	public function create_new_file($user_id, $article_id, $original_name, $file_type = PDF_FILE_TYPE_ID, $device_type = 0, $treks = null, $trek_id = null, $step_id = null) {
		$cache_request_key = md5($user_id.$article_id.$original_name.$file_type);
		$c_ = new Cache();
		if ($c_->get($cache_request_key) === false) {
			$new_file_id = $this->get_next_file_id($user_id, $file_type);
			
			
			if ($file_type == PDF_FILE_TYPE_ID) { 
                $new_file_name =$new_file_name = $user_id.'_'.$article_id.'_'.md5($original_name).'_'.$file_type.'.pdf';
            }
            else if($file_type == AVATAR_FILE_TYPE_ID)
                $new_file_name = $user_id.'_avatar'.substr($original_name, strrpos($original_name, '.'));
            else if ($file_type == IMAGE_UPLOAD_FILE_TYPE_ID)
            	$new_file_name = Util::encode($user_id,$new_file_id).'_img'.substr($original_name, strrpos($original_name, '.'));
            else if ($file_type == VIDEO_UPLOAD_FILE_TYPE_ID)
            	$new_file_name = Util::encode($user_id,$new_file_id).'_vid'.substr($original_name, strrpos($original_name, '.'));

            if (HOST_NAME == HOST_PASSAGEO) {
				if ($treks) {
					$sql = 'INSERT INTO users_files(user_id, file_id, s3_file_name, original_name, file_type, trek_id, step_id, device_type)
						VALUES(?, ?, ?, ?, ?, ?, ?, ?)';
					$this->db->insert($sql, array($user_id, $new_file_id, $new_file_name, $original_name, $file_type, $trek_id, $step_id, $device_type));
				}
			}
			else {
				$sql = 'INSERT INTO users_files(user_id, file_id, s3_file_name, original_name, file_type, article_id, device_type)
					VALUES(?, ?, ?, ?, ?, ?, ?)';
				$this->db->insert($sql, array($user_id, $new_file_id, $new_file_name, $original_name, $file_type, $article_id, $device_type));
			}
			
			$c_->set($cache_request_key, serialize(array('file_id' => $new_file_id, 'file_name' => $new_file_name)), 60);
			return array('file_id' => $new_file_id, 'file_name' => $new_file_name);
		}
		else {
			return unserialize($c_->get($cache_request_key));
		}
	}
    
    public function remove_file_by_id($user_id, $file_id, $file_type = PDF_FILE_TYPE_ID) {
		$sql = 'SELECT s3_file_name, original_name FROM users_files WHERE user_id = ? AND file_id = ? AND file_type = ?';
		$res = $this->db->query($sql, array($user_id, $file_id, $file_type));

		if (isset($res[0]['s3_file_name'])) {
			$c_ = new Cache();
			$cache_request_key = md5($user_id.$article_id.$res[0]['original_name'].$file_type);
			$c_->set($cache_request_key, null, 1);
			$amazon = new amazonAPI();
			$res = $amazon->removeS3Object($res[0]['s3_file_name']);
			if ($res) {
				$sql = "DELETE FROM users_files WHERE user_id = ? AND file_id = ? AND file_type = ?";
				$this->db->execute($sql, array($user_id, $file_id, $file_type));
			}
		}
	}

	public function remove_file($user_id, $article_id, $file_type = PDF_FILE_TYPE_ID) {
		$sql = "SELECT s3_file_name, original_name FROM users_files WHERE user_id = ? AND article_id = ? AND file_type = ?";
		$res = $this->db->query($sql, array($user_id, $article_id, $file_type));

		if (isset($res[0]['s3_file_name'])) {
			$c_ = new Cache();
			$cache_request_key = md5($user_id.$article_id.$res[0]['original_name'].$file_type);
			$c_->set($cache_request_key, null, 1);
			$amazon = new amazonAPI();
			$res = $amazon->removeS3Object($res[0]['s3_file_name']);
			if ($res) {
				$sql = "DELETE FROM users_files WHERE user_id = ? AND article_id = ? AND file_type = ?";
				$this->db->execute($sql, array($user_id, $article_id, $file_type));
			}
		}
	}

	public function get_next_file_id($user_id, $file_type = PDF_FILE_TYPE_ID) {
		$sql = "SELECT file_id FROM users_files WHERE user_id = ? ORDER BY file_id DESC LIMIT 1";
		$res = $this->db->query($sql, array($user_id));
		return count($res) == 0 ? 1 : $res[0]['file_id']+1;
	}

	public function verify($user_id, $file_id, $file_type = PDF_FILE_TYPE_ID) {
		$sql = "UPDATE users_files SET verified = 1 WHERE user_id = ? AND file_id = ? AND file_type = ?";
		$this->db->execute($sql, array($user_id, $file_id, $file_type));
	}

	public function find_hasPDF($user_id, $articles) {
		if (isset($user_id) && is_array($articles)) {
			$in_string = '';
			$i == 0;
			foreach($articles as $art) {
				if ($i++ == 0) $in_string = $art['_id'];
				else $in_string .= ','. $art['_id'];
			}
			$sql = 'SELECT DISTINCT article_id FROM users_files
					WHERE user_id=? AND article_id IN ('.$in_string.')';

			$res = $this->db->query($sql, array($user_id));

			$articles_out = array();
			foreach($res as $r)
				$articles_out[] = $r['article_id'];
			return $articles_out;
		}
	}

	public function get_file_url_by_id($user_id, $file_id, $file_type = PDF_FILE_TYPE_ID) {
		if (strpos($file_id, ',') !== false) {
			$file_ids = explode(',', $file_id);
			$file_id = '';

			foreach($file_ids as $fid) {
				if (empty($file_id)) $file_id = '\''.$fid.'\'';
				else $file_id .= ',\''.$fid.'\'';
			}

			$sql = "SELECT file_id, CONCAT('https://s3.amazonaws.com/', '".IMAGE_UPLOAD_S3_BUCKET."/', s3_file_name) file_name
					FROM users_files WHERE user_id = ? AND file_id IN (".$file_id.") AND file_type = ?";
			$res = $this->db->query($sql, array($user_id, $file_type));

			if (isset($res[0]['file_name']))return $res;
			else return false;
		}
		else {
			$sql = "SELECT file_id, CONCAT('https://s3.amazonaws.com/', '".IMAGE_UPLOAD_S3_BUCKET."/', s3_file_name) file_name
				    FROM users_files WHERE user_id = ? AND file_id = ? AND file_type = ?";
			$res = $this->db->query($sql, array($user_id, $file_id, $file_type));

			if (isset($res[0]['file_name'])) return $res;
			else return false;
		}
	}

}
?>
