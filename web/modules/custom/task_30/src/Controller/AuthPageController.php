<?php

/**
 * @file
 * Contains \Drupal\task_30\Controller\AuthPageController.
 */

namespace Drupal\task_30\Controller;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Access\AccessResult;


/**
 * Provides route responses for the Task 30 module.
 */
class AuthPageController {

	/**
	 * Returns a simple page.
	 *
	 * @return array
	 *   A simple renderable array.
	 */
	public function content() {

		return [ 
			'#theme' => 'task_30_block'
		];
	}

	public function access() {

		$current_user = \Drupal::currentUser();
		$roles = $current_user->getRoles();
		$manager = false;
		$even = false;

		$currentTime = new DrupalDateTime;
		$currentMinute = $currentTime->format( 'i' );


		if ( ( (int) $currentMinute % 2 ) === 0 ) {
			$even = true;
		}

		if ( $even && in_array( 'manager', $roles ) && in_array( 'authenticated', $roles ) ) {
			$manager = true;
		}

		if ( ! $even && ! in_array( 'manager', $roles ) && in_array( 'authenticated', $roles ) ) {
			$manager = false;
		}

		return AccessResult::allowedIf( $manager );
	}

}