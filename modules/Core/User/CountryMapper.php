<?php
/**
 * CountryMapper.php
 *
 * Date: 8/04/2014
 * Time: 10:46 PM
 */

namespace Core\User;


class CountryMapper extends \App\Mapper\Base {

	/**
	 * Define properties
	 *
	 * @return mixed|void
	 */
	protected function properties() {
		$this->setModel('\\Core\\User\\Country');
		$this->setTable('country');

		$this->addProperty('id', 'id', self::TYPE_INT);
		$this->addProperty('name', 'name', self::TYPE_STR);
		$this->addProperty('abbreviation', 'abbreviation', self::TYPE_STR);
	}

	/**
	 * Get a list of options for a dropdown or checkbox array
	 *
	 * @param null $preselected
	 * @return array
	 */
	public function findDropdownValues($preselected = null) {
		$items = array();

		foreach($this->findAll() as $current) {
			$selected = false;

			if ($current->getId() == $preselected) {
				$selected = true;
			}

			$items[] = array(
				'label' => $current->getName(),
				'value' => $current->getId(),
				'selected' => $selected,
			);
		}

		return $items;
	}
} 