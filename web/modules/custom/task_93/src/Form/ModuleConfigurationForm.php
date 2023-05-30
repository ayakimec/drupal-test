<?php

namespace Drupal\task_93\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

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
   * The entity service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a EntityTypeManagerInterface object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The module handler service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('task_93.admin_settings');
    $vid = 'Country';
    $terms = $this->entityTypeManager->getStorage('taxonomy_term')->loadTree($vid);

    foreach ($terms as $term) {
      $countries[$term->name] = $term->name;
    }

    $form['task_93'] = [
      '#type' => 'select',
      '#title' => $this->t('Select Country:'),
      '#empty_option' => $this->t('--- Select Country ---'),
      '#empty_value' => '',
      '#default_value' => $config->get('task_93'),
      '#options' => $countries
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
