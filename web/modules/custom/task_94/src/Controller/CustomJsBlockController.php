<?php

namespace Drupal\task_94\Controller;

use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Provides route responses for the Task 94 module.
 */
class CustomJsBlockController {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function content() {
    $date = $this->getDate();

    return [
      '#theme' => 'task_94_block',
      '#body_text' => [
        '#markup' => 'Custom Block!',
      ],
      '#date' => [
        '#markup' => $date
      ],
      '#attached' => [
        'library' => [
          'task_94/custom_script',
        ],
      ]
    ];
  }

  /**
   * Returns date.
   */
  public function getDate() {
    $current_time = new DrupalDateTime();
    $date = $current_time->format('d/m/Y');

    return $date;
  }

}
