<?php

namespace Drupal\task_94\Controller;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Block\BlockManagerInterface;
use Drupal\Core\Render\RendererInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides route responses for the Task 94 module.
 */
class CustomJsBlockController implements ContainerInjectionInterface {

  /**
   * The block manager.
   *
   * @var \Drupal\Core\Block\BlockManagerInterface
   */
  protected $blockManager;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * Constructs a Interface objects.
   *
   * @param \Drupal\Core\Block\BlockManagerInterface $block_manager
   *   The block manager.
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer.
   */
  public function __construct(BlockManagerInterface $block_manager, RendererInterface $renderer) {
    $this->blockManager = $block_manager;
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.block'),
      $container->get('renderer')
    );
  }

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function content() {
    $result = $this->getCustomBlock();

    return [
      '#markup' => $result,
      '#attached' => [
        'library' => [
          'task_94/custom_script',
        ],
      ]
    ];
  }

  /**
   * Returns a custom block.
   */
  public function getCustomBlock() {
    $plugin_block = $this->blockManager->createInstance('custom-js');
    $render = $plugin_block->build();
    $result = $this->renderer->render($render)->__toString();

    return $result;
  }

}
