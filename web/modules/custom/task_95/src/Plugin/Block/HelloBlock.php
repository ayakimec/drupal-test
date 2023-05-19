<?php

namespace Drupal\task_95\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'HelloBlock' block.
 *
 * @Block(
 *  id = "hello_block",
 *  admin_label = @Translation("Hello block"),
 * )
 */
class HelloBlock extends BlockBase {
  /**
 * {@inheritdoc}
 */
  public function build() {
    return [
      '#theme' => 'hello_block',
      '#body_text' => [
        '#markup' => $this->t('Hello, World!'),
      ]
    ];
  }
}