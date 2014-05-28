<?php

class Thread {

	public $pid = null;
	public $thread_id = null;
	public $is_running_now = null;
	public $last_started = null;
	private $thread_name = null;
	public $last_duration = null;
	private $thread_type_id = null;
	private static $pids = array();

	function __construct($thread_id = null) {
		$this->db = DB::instance();
		if (is_numeric($thread_id)) {

			$res = $this->db->query('SELECT thread_id, is_running_now, last_started, thread_name, thread_type_id, last_duration
									FROM threads where thread_id = ?', array($thread_id));

			$this->thread_id = $res[0]['thread_id'];
			$this->is_running_now = (int)$res[0]['is_running_now'];
			$this->last_started = $res[0]['last_started'];
			$this->thread_name = $res[0]['thread_name'];
			$this->last_duration = $res[0]['last_duration'];
			$this->thread_type_id = $res[0]['thread_type_id'];
		}
	}

	public function start() {
		$this->pid = count(self::$pids);
		$this->last_started = microtime(true);
		$this->is_running_now = 1;

		$a_clone = clone $this;
		unset($a_clone->db);
		self::$pids[] = $a_clone;

		if ($this->thread_type_id == 1) {
			$this->db->execute('UPDATE threads SET is_running_now = 1, last_started = now() where thread_id = ?', array($this->thread_id));
		}

		return $this->pid;
	}

	public static function stop($pid) {
		if (isset(self::$pids[$pid])) {
			$a_thread = self::$pids[$pid];
			$a_thread->pid = null;
			$a_thread->is_running_now = 0;
			$a_thread->last_duration = microtime(true) - $a_thread->last_started;

			$new_pids = array();
			foreach(self::$pids as $p) {
				if ($p->pid != $pid) {
					$new_pids[] = $p->pid;
				}
			}
			self::$pids = $new_pids;

			if ($a_thread->thread_type_id == 1) {
				$a_thread->db = DB::instance();
				$a_thread->db->execute('UPDATE threads SET is_running_now = 0, last_duration = ? where thread_id = ?',
									   array($a_thread->last_duration, $a_thread->thread_id));
			}
			return $a_thread;
		}
		else {
			return false;
		}
	}

	public function set_total_units($total_units) {
		$db = DB::instance();
		$db->execute('UPDATE threads SET units_total = ?, units_processed=0 where thread_id = ?',
								array($total_units, $this->thread_id));
	}

	public function unit_done($steps=null) {
		$db = DB::instance();
		if (!is_numeric($steps)) {
			$db->execute('UPDATE threads SET units_processed = units_processed+1 where thread_id = ?',
								array($this->thread_id));
		}
		else {
			$db->execute('UPDATE threads SET units_processed = units_processed+? where thread_id = ?',
								array(intval($steps), $this->thread_id));
		}
	}

}
