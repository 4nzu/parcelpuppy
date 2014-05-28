<?php

class DB {

    private $memcache;
    private $db;
    private $verbose;
    private static $instances = array();

    public $name;
    public $host;
    public $user;
    public $pass;
    public $port;

    public $is_connected;
	public $rows_affected = 0;

    public function __construct($host = DB_HOST, $name = DB_NAME, $port = DB_PORT, $user = DB_USER, $pass = DB_PASS) {
		$this->verbose = false;
		$this->name = $name;
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		$this->port = $port;

        // try to initialize memcache
        if (empty($this->memcache)) {
            try {
                $this->memcache = new Memcache();
                $this->memcache->connect(MEMCACHE_SERVER, MEMCACHE_PORT);
            }
            catch (Exception $e) {
                $this->memcache = "error";
            }
        }
	return $this;
    }

    public function connect() {
        if (!is_object($this->db)) {
            try {
                $this->db = null;
				try {
					$dns = "mysql:dbname=".$this->name.";host=".$this->host.";port=".$this->port;
					$this->db = new PDO($dns, $this->user, $this->pass);
					$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$this->db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
					$this->db->exec("SET NAMES 'utf8'");
					// $this->db->exec("SET time_zone='US/Pacific'");
				}
				catch (PDOException $e) {
					$this->error_out('Connection failed: ' . $e->getMessage());
				}
				if (!is_object($this->db)) {
					$this->error_out('Unable to connect: '.$this->host.':'.$this->port);
				}

                $this->is_connected = true;
                return $this;
            }
			catch (Exception $e) {
				$this->error_out($e->getMessage());
            }
        }
    }

    public static function instance($host = DB_HOST, $name = DB_NAME, $port = DB_PORT, $user = DB_USER, $pass = DB_PASS) {
		$instance_id = md5($host.$name);
        if (!isset(self::$instances[$instance_id])) {
            try {
                self::$instances[$instance_id] = new DB($host, $name, $port, $user, $pass);
            } catch (Exception $e) {
                error_out($e->getMessage());
            }
        }
        return self::$instances[$instance_id];
    }

    private function prepare($sql, &$params) {
        if (empty($sql)) {
            throw new Exception('Query can not be empty.');
        }
        if (!empty($params)) {
            if (!is_array($params)) {
                throw new Exception('Parameters must be an array, something else is provided.<br>');
            }
            if (count($params) != substr_count($sql, '?')) {
                throw new Exception('Number of parameters must be the same as number of wild cards.<br>');
            }
			$i = 0;
            foreach($params as $p) {
                if ($p === null || $p === '') {
					$params[$i] = NULL;
                    $sql = preg_replace('/\?/', "NULL", $sql, 1);
				}
                else
                    $sql = preg_replace('/\?/', "'".$p."'", $sql, 1);
				$i++;
            }
        }

	if ($this->verbose) { var_dump($sql); }
        return $sql;
    }

    public function begin() {
		$this->connect();
        return $this->db->beginTransaction();
    }

    public function commit() {
		$this->connect();
        return $this->db->commit();
    }

    public function rollback() {
		$this->connect();
        return $this->db->rollback();
    }

    private function query_db($sql, $params) {
        try {
            return $this->get_all_assoc($sql, $params);
        }
		catch (PDOException $e) {
			$this->error_out($e->getMessage(), $sql, $params);
		}
        return $cached;
    }

    public function &get_all_assoc($sql, $params = null) {
        $res = array();
        $this->connect();
        $q = $this->db->prepare($sql);
        if ($q->execute($params)) {
            while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
                $res[] = $row;
            }
        }
		return $res;
    }

    public function get_key($query) {
        return HOST_ID.":".md5($query);
    }

    // ttl = time to leave in seconds
    function query($sql, $params=null, $ttl=null) {
        try {
            $query = $this->prepare($sql, $params);
        }
        catch (Exception $e) {
            $this->error_out($e->getMessage(), $sql, $params);
        }

        if ($this->memcache == "error") {
            $ttl = null;
        }

        if (empty($ttl)) {
            return $this->query_db($sql, $params);
        }
        else {
            $cached = $this->memcache->get($this->get_key($query));
            if ($cached === false) {
                $cached = $this->query_db($sql, $params);
                $this->memcache->set($this->get_key($query), $cached, null, $ttl);
            }
            return $cached;
        }
    }

    public function insert($sql, $params) {
        try {
            $this->connect();
            $q = $this->db->prepare($sql);
            if ($q->execute($params)) {
                return $this->db->lastInsertId();
            }
        } catch (PDOException $e) {
            $this->error_out($e->getMessage(), $sql, $params);
            return null;
        }
    }

    public function execute($sql, $params=null) {
        $this->connect();
        try {
            $q = $this->db->prepare($sql);

            if (is_array($params)) {
                $res = $q->execute($params);
            } else {
                $res = $q->execute();
            }
        } catch (PDOException $e) {
            $this->error_out($e->getMessage(), $sql, $params);
            return false;
        }

        $this->rows_affected = $q->rowCount();
        return $q;
    }

    function error_out($error_message, $sql=null, $params=null) {
		if (HOST_ROLE == HOST_DEV) {
			echo '<br>&nbsp;<br>&nbsp<br>';
			echo '<span style="color: red; font-weight: bold;">ERROR: </span><span style="font-weight: bold;"> '.$error_message.'</span><br>';
			echo '<pre>Query:';
			var_dump($sql);
			echo '</pre>';
			echo '<pre>Params:';
			var_dump($params);
			echo '</pre>';
			echo '<pre>Stack Trace:';
			array_walk(debug_backtrace(), create_function( '$a,$b', 'print "<br /><b>". basename( $a[\'file\'] ). "</b> &nbsp; <font color=\"red\">{$a[\'line\']}</font> &nbsp; <font color=\"green\">{$a[\'function\']} ()</font> &nbsp; -- ". dirname( $a[\'file\'] ). "/";' ));
			echo '</pre>';
		}
		else {
			$debug = debug_backtrace();
			echo '<!-- ERROR: '.$debug[2]['file'].":".$debug[2]['line'].' '.$error_message.'<br>-->';
		}
    }

    function verbose($set_verbose = true) {
		if (HOST_ROLE == HOST_DEV) {
			$this->verbose = $set_verbose;
		}
		else {
			$this->verbose = false;
		}
    }
}
?>
