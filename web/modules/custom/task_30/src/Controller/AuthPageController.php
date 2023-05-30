<?php

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

  /**
   * Returns a page according user role.
   */
  public function access() {

    $current_user = \Drupal::currentUser();
    $roles = $current_user->getRoles();
    $manager = FALSE;
    $is_even = FALSE;

    $currentTime = new DrupalDateTime();
    $currentMinute = $currentTime->format('i');

    if (((int) $currentMinute % 2) === 0) {
      $is_even = TRUE;
    }

    if ($is_even && in_array('manager', $roles) && in_array('authenticated', $roles)) {
      $manager = TRUE;
    }

    if (!$is_even && !in_array('manager', $roles) && in_array('authenticated', $roles)) {
      $manager = FALSE;
    }

    return AccessResult::allowedIf($manager);
  }

}
