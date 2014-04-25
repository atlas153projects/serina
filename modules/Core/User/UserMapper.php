<?php
/**
 * UserMapper.php
 *
 * Date: 7/04/2014
 * Time: 9:37 PM
 */

namespace Core\User;
use \App\Mapper\Query as Query;

class UserMapper extends \App\Mapper {

	/**
	 * Define properties
	 *
	 * @return mixed|void
	 */
	protected function properties() {
		$this->setModel('\\Core\\User');
		$this->setTable('user');

		$this->addProperty('id', 'id');
		$this->addProperty('uuid', 'uuid');
		$this->addProperty('firstname', 'firstname');
		$this->addProperty('lastname', 'lastname');
		$this->addProperty('birthdate', 'birthdate');
		$this->addProperty('address', 'address_id');
		$this->addProperty('phone', 'phone_id');
		$this->addProperty('gender', 'gender_id');

		/* Join the Phone record onto the User record using
		 * the following columns as a match. otherTable and
		 * otherKey refer to the table and key being joined
		 * onto this current mapper's model
		 *
		 * These definitions, when combined into the final
		 * sql query, mustn't create a syntactically incorrect
		 * query, or it'll just snap
		 *
		 * The 'collection' key is the database column into
		 * which the final joined collections are placed
		 */
		$this->addJoin('Phone', array(
			'this' => array(
				'model' => '\Core\User\User',
				'key' => 'id',
				'collection' => 'phone_id',
			),
			'other' => array(
				'model' => '\Core\User\Phone',
				'key' => 'user_id',
			),
		));

		$this->addJoin('Address', array(
			'this' => array(
				'model' => '\Core\User\User',
				'key' => 'address_id',
				'collection' => 'address_id',
			),
			'other' => array(
				'model' => '\Core\User\Address',
				'key' => 'id',
			),
		));

		$this->addJoin('Gender', array(
			'this' => array(
				'model' => '\Core\User\User',
				'key' => 'gender_id',
				'collection' => 'gender_id',
			),
			'other' => array(
				'model' => '\Core\User\Gender',
				'key' => 'id',
			)
		));
	}

	/**
	 * Find all the users with all their extra data
	 */
	public function findAll() {
		$query = new Query();
		$query->select(
			'\Core\User\User u',
			'\Core\User\Address a',
			'\Core\User\State s',
			'\Core\User\Phone p',
			'\Core\User\Gender g'
		)
			->from('\Core\User\User u')
			->leftJoin('\Core\User\User u', 'Address a')
			->leftJoin('\Core\User\Address a', 'State s')
			->leftJoin('\Core\User\User u', 'Phone p')
			->leftJoin('\Core\User\User u', 'Gender g');

		$statement = $this->getDatabase()->prepare($query->prepare());
		$statement->execute();

		/* Use cached mapper spawns
		 */
		$userMapper = $query->getMapper('\Core\User\User', 'u');
		$addressMapper = $query->getMapper('\Core\User\Address', 'a');
		$stateMapper = $query->getMapper('\Core\User\State', 's');
		$phoneMapper = $query->getMapper('\Core\User\Phone', 'p');
		$genderMapper = $query->getMapper('\Core\User\Gender', 'g');

		$userCollection = new \App\Collection();
		$addressCollection = new \App\Collection();
		$stateCollection = new \App\Collection();
		$phoneCollection = new \App\Collection();
		$genderCollection = new \App\Collection();

		foreach($statement as $row) {
			$user = $userMapper->hydrate('u', $row);
			$userCollection->setItemAt($user->getId(), $user);

			$address = $addressMapper->hydrate('a', $row);
			$addressCollection->setItemAt($address->getId(), $address);

			$state = $stateMapper->hydrate('s', $row);
			$stateCollection->setItemAt($state->getId(), $state);

			$phone = $phoneMapper->hydrate('p', $row);
			$phoneCollection->setItemAt($phone->getId(), $phone);

			$gender = $genderMapper->hydrate('g', $row);
			$genderCollection->setItemAt($gender->getId(), $gender);
		}

		/* Build address/state relation
		 */
		$this->joinCollections($addressCollection, $stateCollection, 'State', $query);

		/* Build user/address relation
		 */
		$this->joinCollections($userCollection, $addressCollection, 'Address', $query);

		/* Build user/phone relation
		 */
		$this->joinCollections($userCollection, $phoneCollection, 'Phone', $query);

		/* Build user/gender relation
		 */
		$this->joinCollections($userCollection, $genderCollection, 'Gender', $query);

		return $userCollection;
	}
} 