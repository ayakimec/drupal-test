<?php
 
/**
 * @file
 * Contains \Drupal\task_27\Controller\NodesPageController.
 */
 
namespace Drupal\task_27\Controller;
 
/**
 * Provides route responses for the HelloPage module.
 */
class NodesPageController {
 
  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function content() {

    $list = getNodes();

    foreach ( $list as $item) {
      $titles[] = $item->title;
      $date[] = $item->created;
    }

    return [
      '#theme' => 'task_27_block',
      '#titles' => $titles,
      '#date' => $date
    ];
  }
 
}

function getNodes() {
  $nodes = \Drupal::database()
  ->select('node_field_data', 'n')
  ->fields('n')
  ->range(0, 5) // <--
  ->execute()
  ->fetchAll();

  return $nodes;
}