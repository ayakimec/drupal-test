<?php
 
/**
 * @file
 * Contains \Drupal\hellopage\Controller\HelloPageController.
 */
 
namespace Drupal\hellopage\Controller;
 
/**
 * Provides route responses for the HelloPage module.
 */
class HelloPageController {
 
  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function content() {
    $element = array(
      '#markup' => 'Hello World!',
    );
    return $element;
  }
 
}