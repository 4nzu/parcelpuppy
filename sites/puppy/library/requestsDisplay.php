<?php

class requestsDisplay extends Template {

	public $requests;

	function __construct() {
		parent::__construct();
		import('Zappy.Util');
		$this->db = DB::instance();

		import('Zappy.Cache');
		$_c = new Cache();
        $localization = $_c->get('localization_'.$_SESSION['user']->site_lang);
        $this->assign('localization', $localization);

        import('library.Requests');
        $this->requests = new Requests();
	}

    public function display_home() {

    }

    public function requests() {
    	if (isset($_REQUEST['rid']) && is_numeric($_REQUEST['rid'])) {
    		$request = $this->requests->get_request($_REQUEST['rid']);

    		$this->assign('request', $request);
        	$this->set_template('requests_show');
        }
        else {
        	header('Location: /');
        }
    }
}
?>