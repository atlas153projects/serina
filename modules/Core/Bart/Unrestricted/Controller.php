<?php
/**
 * Controller
 *
 * Date: 23/12/13
 * Time: 11:26 PM
 */

namespace Core\Bart\Unrestricted;

class Controller extends \App\Controller\Type\Unrestricted {

	/**
	 * Experimental method
	 */
	public function getBart() {
		echo 'getBart() ran.';

		$this->output('getTest', array(
			'args' => $this->getArgs(),
			'testKey' => 'bart test'
		));
	}
}