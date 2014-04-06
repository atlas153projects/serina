<?php
/**
 * Mapper.php
 *
 * Date: 13/03/2014
 * Time: 10:14 PM
 */

namespace App;


class Mapper {

	/**
	 * An instance of Database
	 *
	 * @var Database
	 */
	private $database = null;

	/**
	 * The model used by this mapper for automatic hydration
	 *
	 * @var string
	 */
	protected $model = '';

	/**
	 * The database table used by this mapper for automatic hydration
	 *
	 * @var string
	 */
	protected $table = '';

	/**
	 * A list of class var/db column pairs
	 *
	 * @var array
	 */
	protected $properties = array();

	/**
	 * Constructor
	 */
	final public function __construct() {
		$this->setDatabase(new Database());
		$this->properties();
		$this->setup();
	}

	/**
	 * TODO: Move to individual class mappers
	 */
	protected function properties() {
		$this->setModel('\\Core\\User');
		$this->setTable('user');

		$this->addProperty(array(
			'property' => 'id',
			'column' => 'id'
		));

		$this->addProperty(array(
			'property' => 'uuid',
			'column' => 'uuid'
		));

		$this->addProperty(array(
			'property' => 'firstname',
			'column' => 'firstname'
		));

		$this->addProperty(array(
			'property' => 'lastname',
			'column' => 'lastname'
		));

		$this->addProperty(array(
			'property' => 'birthdate',
			'column' => 'birthdate'
		));
	}

	/**
	 * Setup hook method
	 */
	protected function setup() {

	}

	/**
	 * Add a property definition
	 *
	 * @param $property
	 */
	protected function addProperty($property) {
		$this->properties[] = $property;
	}

	/**
	 * Figure out which model setter to use
	 *
	 * @param $property
	 * @return string
	 */
	protected function deriveMethod($property) {
		return 'set' . ucwords($property);
	}

	/**
	 * Populate the specified object
	 *
	 * @param $row
	 */
	protected function hydrate($row) {
		$model = $this->getModel();
		$instance = new $model();

		foreach($this->getProperties() as $definition) {
			$method = $this->deriveMethod($definition['property']);
			$column = $definition['column'];

			$instance->$method($row[$column]);
		}

		return $instance;
	}

	public function testQuery() {
		$query = "
			SELECT *
			FROM test
		";

		$statement = $this->getDatabase()->prepare($query);
		$statement->execute();

		foreach($statement as $row) {
			new \App\Probe($row);
			new \App\Probe($this->hydrate($row));
		}
	}

	/* Getters/Setters
	 */

	/**
	 * Set database
	 *
	 * @param \App\Database $database
	 */
	protected function setDatabase($database) {
		$this->database = $database;
	}

	/**
	 * Get database
	 *
	 * @return \App\Database
	 */
	protected function getDatabase() {
		return $this->database;
	}

	/**
	 * Set model
	 *
	 * @param string $model
	 */
	protected function setModel($model) {
		$this->model = $model;
	}

	/**
	 * Get model
	 *
	 * @return string
	 */
	protected function getModel() {
		return $this->model;
	}

	/**
	 * Set properties
	 *
	 * @param array $properties
	 */
	protected function setProperties($properties) {
		$this->properties = $properties;
	}

	/**
	 * Get properties
	 *
	 * @return array
	 */
	protected function getProperties() {
		return $this->properties;
	}

	/**
	 * Set table
	 *
	 * @param string $table
	 */
	protected function setTable($table) {
		$this->table = $table;
	}

	/**
	 * Get table
	 *
	 * @return string
	 */
	public function getTable() {
		return $this->table;
	}
} 