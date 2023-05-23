<?php

/**
 * @file
 * Contains \Drupal\task_27\Controller\NodesPageController.
 */

namespace Drupal\task_27\Controller;

/**
 * Provides route responses for the Task 27 module.
 */
class NodesPageController {

	/**
	 * Returns a simple page.
	 *
	 * @return array
	 *   A simple renderable array.
	 */
	public function content() {

		$list = $this->getNodes();

		return [ 
			'#theme' => 'task_27_block',
			'#nodes' => $list
		];
	}

	function getNodes() {
		$nodes = \Drupal::database()
			->select( 'node_field_data', 'n' )
			->fields( 'n' )
			->condition( 'status', 1 )
			->range( 0, 5 )
			->execute()
			->fetchAll();

		return $nodes;
	}
}