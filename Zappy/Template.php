<?php

class Template {

	private $templates = array();
    public $action;
    public $request;
	public $no_header;
	public $no_footer;
	public $localization = false; // can be true or false or default
    public $header = "header";
    public $footer = "footer";
    public $override_html = false;
    private $variables;

    function __construct() {
        if (isset($_REQUEST['noheader'])) $this->no_header = true;
		if (isset($_REQUEST['nofooter'])) $this->no_footer = true;
    }

    public function assign($name, $value = false) {
        if(is_array($name) && $value == false) foreach($name as $key => $val) $this->variables[$key] = $val;
        else $this->variables[$name] = $value;
        return $this;
    }

    public function set_action($action) {
        $this->action = $action;
        $this->assign("action", $action);
        return $this->action;
    }

    public function set_request($request) {
        $this->request = $request;
        $this->assign("request", $request);
        return $this->request;
    }

    public function execute($action) {
        $action = str_replace('-', '_', $action);
        $this->action = $this->set_action($action);
        if(!method_exists($this, $action)) {
            header('HTTP/1.0 404 Not Found');
            echo "<h1>404 Not Found</h1>";
            echo "The page that you have requested could not be found.";
            die;
        }
        eval("\$this->\$action();");
        $this->render();
    }

    public function _type() {
       $resp = null;

       if (isset($this->request['response']) && $this->request['response'])
            $resp = str_replace(".", "", $this->request['response']);
        else { if(!isset($_REQUEST['page']) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') $resp = 'json';}
        return $resp;
    }

    private function _file_path($template) {
        $template_path  = str_replace("_", "/", $template);
        $complete_template_path = SITE_PATH.'templates/'.$template_path.".php";
        if(is_file($complete_template_path)) return $complete_template_path; else return false;
    }

    private function _response() {
        $res = array('results'=>$this->variables['results']);
        return $res;
    }

    public function render() {
        if (is_array($this->variables)) foreach ($this->variables as $template_variable_name => $val) $$template_variable_name = $val;
        if ($this->_type() == "json" && !$this->override_html) {
			error_reporting(E_ERROR| E_PARSE);
			ob_end_clean();
			Header("Content-type: application/json");
			echo json_encode($this->_response());
		}
		else {
			if ($this->localization) {
                import('Zappy.Cache');  
                $_c = new Cache;
				if (!$_c->get('localization_'.$_SESSION['user']->site_lang)) {
					if (strstr($this->localization, 'default')) $localization = json_decode(file_get_contents("localization/en-US", "r"));
					elseif ($this->localization){
						if (!$localization = @file_get_contents("localization/".$_SESSION['user']->site_lang."", "r")) {
							$localization = file_get_contents("localization/".DEFAULT_SITE_LANGUAGE."", "r");
						}
						$localization = json_decode($localization);
					}
					$_c->set('localization_'.$_SESSION['user']->site_lang, $localization, LOCALIZATION_CACHE_TIME);
					$this->localization = 'localization_'.$_SESSION['user']->site_lang;
				}
			}
			if(!$this->no_header) require_once($this->_file_path($this->header));
			foreach($this->templates as $name => $template_file) {
				if (is_file($template_file)) {
					require_once($template_file);
				}
			}
			if(!$this->no_footer) require_once($this->_file_path($this->footer));
			else {
				
			}
        }
    }

    public function set_template($template) {
        $full_file_path = $this->_file_path($template);
        if($full_file_path) $this->templates[$template] = $full_file_path;
        return $template;
    }

	public function is_template($is_template) {
		return isset($this->templates[$is_template]);
	}

    public function json_out($array=null, $exit=true) {
        if (is_array($array)) {
            ob_end_clean();
            if (isset($_GET['verbose'])) {
                var_dump($array);
            }
            else {
                header("Content-type: application/json");
                $json_data = json_encode($array);
                echo $json_data;
            }
            if ($exit) exit;
        }
        else {
            header($_SERVER['SERVER_PROTOCOL'] . ' 503 Not Implemented', true, 501);
            exit;
        }
    }
}
