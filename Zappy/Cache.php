<?php

class Cache {

    private $memcache = 0;
	private static $local = array();

    function __construct() {
        try {
            $this->memcache = new Memcache();
            $this->memcache->connect(MEMCACHE_SERVER, MEMCACHE_PORT);
            // $this->memcache->setServerParams(MEMCACHE_SERVER, MEMCACHE_PORT, 1, -1);
        }
        catch (Exception $e) { $this->memcache = 0; }
    }

    public function is_ready() {
        return ($this->memcache == 0) ? false : true;
    }

	// ttl = time to leave in seconds
    public function set($key, $value, $ttl) {
        $this->memcache->set(HOST_ROLE.'-'.$key, $value, false, $ttl);
    }

    public function get($key) {
        return $this->memcache->get(HOST_ROLE.'-'.$key);
    }

    public function flush() {
        return $this->memcache->flush();
    }

    public function get_stats() {
        return $this->memcache->getExtendedStats();
    }

    public function get_status() {
        return $this->memcache->getServerStatus(MEMCACHE_SERVER, MEMCACHE_PORT);
    }

	public function delete($key) {
		return $this->memcache->delete($key);
	}

	public static function set_local($key) {

	}

	public static function get_local($key) {

	}
}
?>
