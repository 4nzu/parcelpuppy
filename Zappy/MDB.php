<?php

class MDB {

	private $m; // mongo client
    private $db; // database
	private $collection;
	public $_ids;
    private static $instances = array();

    public $name;
    public $host;
    public $user;
    public $pass;
    public $port;

    public $is_connected;
	public $rows_affected = 0;

    public function __construct($host=null, $port=null) {

		try {
			$this->_ids = array();

			// open connection to MongoDB server
			$this->m = new MongoClient(MONGO_HOST);

			// access database
			$this->db = $this->m->selectDB(MONGO_NAME);

			// access collection
			$this->collection = $this->db->selectCollection(MONGO_COLL);

		} catch (MongoConnectionException $e) {
			if (HOST_ROLE == HOST_DEV) { die('Error connecting to MongoDB server: '.$e->getMessage()); }
			else {
				echo '<br><rr><br><br><br><br><center>PubChase is down for maintenance. Please check back soon.</center><br><center>:)</center>';
			}
		} catch (MongoException $e) {
			die('Error: ' . $e->getMessage());
		}

	}

	public static function instance($_host=null, $_port=null) {
		$instance_id = md5($_host.$_port);
        if (!isset(self::$instances[$instance_id])) {
            try {
                self::$instances[$instance_id] = new MDB($_host, $_port);
            } catch (Exception $e) {
                error_out($e->getMessage());
            }
        }
        return self::$instances[$instance_id];
    }

    public function clear_IDs() {
		$this->_ids = array();
	}

	public function addID($id) {
		$this->_ids[] = (int)$id;
	}

	public function getMatched($ids = null) {
		if (empty($ids)) {
			$criteria = array('_id' => array('$in' => $this->_ids));
		}
		else
			$criteria = array('_id' => array('$in' => $ids));

		$articles = $this->collection->find($criteria, array('_id'));

		if (!empty($articles) && !is_array($articles))
			$articles = iterator_to_array($articles);
		elseif (is_array($articles) && count($articles) == 1)
			$articles = array($articles['_id'] => $articles);

		return $articles;
	}

	public function getArticles($ids = null) {
		// execute query
		// retrieve all documents
		//$article_id = new MongoId($pubmed_id);
		// $criteria = array('_id' => (int)$pubmed_id); array(19171939, 18628886, 21151344);
		//error_log('ID_COUNT='.count($this->_ids));
		//error_log('IDS='.implode(',',$this->_ids));
		if (empty($ids)) {
			$criteria = array('_id' => array('$in' => $this->_ids));
		}
		else {
			$criteria = array('_id' => array('$in' => $ids));
		}
		$articles = $this->collection->find($criteria, array('_id', 'abstract', 'journal_issn', 'journal_volume', 'affiliation',
															 'date_created', 'title', 'journal_title', 'journal_iso_abbreviation',
															 'author_string', 'ELocationID', 'ELocationID_attribs', 'journal_pages',
															 'list_of_authors', 'journal_date', 'reference_date'));
		if (!empty($articles) && !is_array($articles))
			$articles = iterator_to_array($articles);
		elseif (is_array($articles) && count($articles) == 1)
			$articles = array($articles['_id'] => $articles);

		// var_dump($articles);die;
		$this->clear_IDs();

		//error_log('ID_COUNT='.count($articles));
		return $articles;
		// iterate through the result set
		// print each document
		//echo $article->count() . ' document(s) found. <br/>';
		//foreach ($article as $obj) {
		//  var_dump($obj);
		//}
	}

	public function setArticles($articles = null) {
		if (isset($articles) && is_array($articles)) {
			try {
			    $this->collection->batchInsert($articles);
			    return true;
			}
			catch (MongoCursorException $e) {
			    error_log('ERROR: '.$e->getMessage().'. Error code: '.$error_code);
			    return false;
			}
		}
	}

	public function removeArticles($ids = null) {
		try {
			if (empty($ids)) {
				// error_log('MDB->removeArticles: ERROR empty ids');
				exit;
			}
			else {
				$criteria = array('_id' => array('$in' => $ids));
			}
		    $this->collection->remove($criteria);
		    return true;
		}
		catch (MongoCursorException $e) {
		    error_log('ERROR: '.$e->getMessage().'. Error code: '.$error_code);
		    return false;
		}
	}

	public function getMetaTitle($ids) {
		$criteria = array('_id' => array('$in' => $ids));
		$articles = $this->collection->find($criteria, array('_id', 'author_string', 'title', 'journal_title'));

		if (!empty($articles) && !is_array($articles))
			$articles = iterator_to_array($articles);
		elseif (is_array($articles) && count($articles) == 1)
			$articles = array($articles['_id'] => $articles);

		$out = array();
		$out = explode(' ', $articles[$ids[0]]['journal_title']);
		$out = array_merge($out, explode(' ', $articles[$ids[0]]['title']));
		$out = array_merge($out, explode(', ', $articles[$ids[0]]['author_string']));
		$this->clear_IDs();

		return $out;
	}

	public function getAuthorsList($ids) {
		$criteria = array('_id' => array('$in' => $ids));
		$articles = $this->collection->find($criteria, array('_id', 'list_of_authors', 'journal_title'));

		if (!empty($articles) && !is_array($articles))
			$articles = iterator_to_array($articles);
		elseif (is_array($articles) && count($articles) == 1)
			$articles = array($articles['_id'] => $articles);

		$this->clear_IDs();

		return $articles;
	}

	public function getArticleDate($ids) {
		$criteria = array('_id' => array('$in' => $ids));
		// $criteria = array('_id' => 17196796);
		$articles = $this->collection->find($criteria, array('_id', 'date_created'));

		if (!empty($articles) && !is_array($articles))
			$articles = iterator_to_array($articles);
		elseif (is_array($articles) && count($articles) == 1)
			$articles = array($articles['_id'] => $articles);

		$this->clear_IDs();

		return $articles;
	}
}
