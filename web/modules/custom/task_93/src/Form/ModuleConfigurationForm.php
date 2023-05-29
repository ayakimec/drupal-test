<?php

namespace Drupal\task_93\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form that configures your_module’s settings.
 */
class ModuleConfigurationForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'task_93_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'task_93.admin_settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('task_93.admin_settings');

    $form['task_93'] = [
      '#type' => 'select',
      '#title' => $this->t('Select Country:'),
      '#empty_option' => $this->t('--- Select Country ---'),
      '#empty_value' => '',
      '#default_value' => $config->get('task_93'),
      '#options' => [
        'Armenia' => $this->t('Armenia'),
        'Belarus' => $this->t('Belarus'),
        'United States' => $this->t('United States')
      ],
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('task_93.admin_settings')
      ->set('task_93', $form_state->getValue('task_93'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
