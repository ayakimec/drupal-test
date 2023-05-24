<?php

/**
 * @file
 * Contains \Drupal\task_28\Controller\BlocksPageController.
 */

namespace Drupal\task_28\Controller;

/**
 * Provides route responses for the Task 28 module.
 */
class BlocksPageController {

	/**
	 * Returns a simple page.
	 *
	 * @return array
	 *   A simple renderable array.
	 */
	public function content() {
		$result = $this->getRandomBlock();
		$count = $this->getUriCount();
		$data = array_fill( 0, $count, $result );

		return [ 
			'#theme'  => 'task_28_block',
			'#blocks' => $data
		];
	}

	function getRandomBlock() {
		$block = \Drupal\block\Entity\Block::load( 'helloblock' );
		$render = \Drupal::entityTypeManager()->getViewBuilder( 'block' )->view( $block );

		return $render;
	}

	function getUriCount() {
		$current_uri = \Drupal::request()->getRequestUri();
		$uri = explode( '/', $current_uri );
		$count = (int) $uri[2];

		return $count;
	}
}