<?php

namespace Drupal\task_55\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a custom button block.
 *
 * @Block(
 *   id = "button_block",
 *   admin_label = @Translation("Button Block"),
 * )
 */
class ButtonBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'task_55_block',
    ];
  }

}
