<?php
/**
 * Database.php
 *
 * Date: 13/03/2014
 * Time: 9:54 PM
 */

namespace App;


class Database {

	/**
	 * Host - use 127.0.0.1 on macbook, but can use localhost
	 * 
	 * @var string
	 */
	private $host = '127.0.0.1';

	/**
	 * Database account username
	 * 
	 * @var string
	 */
	private $username = 'serina';

	/**
	 * Database account password
	 * 
	 * @var string
	 */
	private $password = 'serina';

	/**
	 * Database name
	 * 
	 * @var string
	 */
	private $database = 'serina';

	/**
	 * A generated dsn string
	 *
	 * @var string
	 */
	private $dsn = '';

	/**
	 * A PDO connection
	 *
	 * @var null
	 */
	private $connection = null;

	/**
	 * Constructor
	 */
	final public function __construct() {
		$this->setup();
		$this->connect();

		/* Throws exception upon error
		 */
		$this->debugOn();
	}

	/**
	 * Setup hook method
	 */
	private function setup() {
		$this->setDsn("mysql:host={$this->getHost()};dbname={$this->getDatabase()}");
	}

	/**
	 * Establish connection
	 */
	private function connect() {
		$this->setConnection(new \PDO($this->getDsn(), $this->getUsername(), $this->getPassword()));
	}

	/**
	 * Debugging for PDO
	 *
	 * Throw exceptions whenever a PDO error is encountered
	 */
	private function debugOn() {
		$this->getConnection()->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}

	/**
	 * Delegation method
	 *
	 * @param $query
	 */
	public function prepare($query) {
		return $this->getConnection()->prepare($query);
	}

	/**
	 * Get the last id inserted
	 *
	 * @return mixed
	 */
	public function getLastInsertId() {
		return $this->getConnection()->lastInsertId();
	}

	/* Getters/Setters
	 */

	/**
	 * Set database
	 *
	 * @param string $database
	 */
	private function setDatabase($database) {
		$this->database = $database;
	}

	/**
	 * Get database
	 *
	 * @return string
	 */
	private function getDatabase() {
		return $this->database;
	}

	/**
	 * Set host
	 *
	 * @param string $host
	 */
	private function setHost($host) {
		$this->host = $host;
	}

	/**
	 * Get host
	 *
	 * @return string
	 */
	private function getHost() {
		return $this->host;
	}

	/**
	 * Set password
	 *
	 * @param string $password
	 */
	private function setPassword($password) {
		$this->password = $password;
	}

	/**
	 * Get password
	 *
	 * @return string
	 */
	private function getPassword() {
		return $this->password;
	}

	/**
	 * Set username
	 *
	 * @param string $username
	 */
	private function setUsername($username) {
		$this->username = $username;
	}

	/**
	 * Get username
	 *
	 * @return string
	 */
	private function getUsername() {
		return $this->username;
	}

	/**
	 * Set dsn
	 *
	 * @param string $dsn
	 */
	private function setDsn($dsn) {
		$this->dsn = $dsn;
	}

	/**
	 * Get dsn
	 *
	 * @return string
	 */
	private function getDsn() {
		return $this->dsn;
	}

	/**
	 * Set connection
	 *
	 * @param null $connection
	 */
	private function setConnection($connection) {
		$this->connection = $connection;
	}

	/**
	 * Get connection
	 *
	 * @return null
	 */
	private function getConnection() {
		return $this->connection;
	}
} 