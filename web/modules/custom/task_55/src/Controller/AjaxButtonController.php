<?php

namespace Drupal\task_55\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AlertCommand;

/**
 * Controller for handling AJAX requests from the custom button.
 */
class AjaxButtonController extends ControllerBase {

  /**
   * Ajax alert response.
   */
  public function ajaxButton() {
    $response = new AjaxResponse();

    $response->addCommand(new AlertCommand('Clicked Button!'));

    return $response;
  }

}
