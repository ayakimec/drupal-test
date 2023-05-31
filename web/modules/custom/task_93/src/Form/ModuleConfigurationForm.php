<?php

namespace Drupal\task_93\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\State\StateInterface;

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
   * 
   * @var \Drupal\Core\State\StateInterface
   */
  protected $entityTypeManager;

	protected $stateInterface;

  /**
   * Constructs a EntityTypeManagerInterface object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The module handler service.
   * 
   * @param \Drupal\Core\State\StateInterface $state_interface
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, StateInterface $state_interface) {
    $this->entityTypeManager = $entity_type_manager;
    $this->stateInterface = $state_interface;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('state')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $vid = 'country';
    $terms = $this->entityTypeManager->getStorage('taxonomy_term')->loadTree($vid);

    foreach ($terms as $term) {
      $countries[$term->tid] = $term->name;
    }

    $form['country_name'] = [
      '#type' => 'select',
      '#title' => $this->t('Select Country:'),
      '#empty_option' => $this->t('--- Select Country ---'),
      '#empty_value' => '',
      '#default_value' => $this->stateInterface->get('country_name'),
      '#options' => $countries,
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->stateInterface->set('country_name',$form_state->getValue('country_name'));

    parent::submitForm($form, $form_state);
  }

}
