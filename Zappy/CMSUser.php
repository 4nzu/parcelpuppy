<?php

class CMSUser {

    public $id                = null;
    public $can_see_debug     = null;
    public $can_manage_models = null;
	public $can_manage_users  = null;
    public $debug_mode_on     = null;
	public $can_manage		  = null;

    function __construct($id = null) {
		$this->db = DB::instance();

		if (isset($id)) {
			$sql = "SELECT * from cms where user_id = ?";
			$res = $this->db->query($sql, array($id), 3600);
			if (count($res) > 0) {
				$this->id                = $id;
				$this->can_see_debug     = $res[0]['can_see_debug'];
				$this->can_manage_models = $res[0]['can_manage_models'];
				$this->can_manage_users  = $res[0]['can_manage_users'];
				$this->debug_mode_on     = $res[0]['debug_mode_on'];
				$this->can_manage		 = $this->can_manage_users + $this->can_manage_models;

				$session_this 	= clone $this;
				unset($session_this->db);
				$_SESSION['cms']   = $session_this;
			}
		}
	}

	public function set_debug_mode($dmo = 0) {
		if ($this->id) {
			if ($dmo != 0 && $dmo != 1) $dmo = 0;
			$this->debug_mode_on = $dmo;
			$_SESSION['cms_user']->debug_mode_on = $dmo;
			$this->db->execute("UPDATE cms SET debug_mode_on=? WHERE user_id=?", array($dmo, $this->id));
		}

	}
}

?>
