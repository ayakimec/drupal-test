<?php

namespace Drupal\task_94\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Provides a 'Custom Block with Js' block.
 *
 *  * @Block(
 *  id = "custom-js",
 *  admin_label = @Translation("CustomJsBlock"),
 * )
 */
class CustomJsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $date = $this->getDate();

    return [
      '#theme' => 'task_94_block',
      '#date' => [
        '#prefix' => '<div class="date"><strong>',
        '#markup' => $date,
        '#suffix' => '</strong></div>',
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
