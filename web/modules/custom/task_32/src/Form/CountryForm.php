<?php

namespace Drupal\task_32\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\State\StateInterface;
use Psr\Log\LoggerInterface;

/**
 * Defines a form that configures your_module’s settings.
 */
class CountryForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'country_form';
  }

  /**
   * The entity service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;


  /**
   * The state service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $stateInterface;

  /**
   * A logger instance.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * Constructs a EntityTypeManagerInterface object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The module handler service.
   * @param \Drupal\Core\State\StateInterface $state_interface
   *   The module handler service.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, StateInterface $state_interface, LoggerInterface $logger) {
    $this->entityTypeManager = $entity_type_manager;
    $this->stateInterface = $state_interface;
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('state'),
      $container->get('logger.factory')->get('action'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $vid = 'countries';
    $cid = 'cities';
    $country = $this->entityTypeManager->getStorage('taxonomy_term')->loadTree($vid);
    $city = $this->entityTypeManager->getStorage('taxonomy_term')->loadTree($cid);

    foreach ($country as $term) {
      $countries[$term->tid] = $term->name;
    }

    foreach ($city as $term) {
      $cities[$term->tid] = $term->name;
    }

    $form['country_name'] = [
      '#type' => 'select',
      '#title' => $this->t('Select Country:'),
      '#empty_option' => $this->t('--- Select Country ---'),
      '#empty_value' => '',
      '#default_value' => $this->stateInterface->get('country_name'),
      '#options' => $countries,
    ];

    $form['city_name'] = [
      '#type' => 'select',
      '#title' => $this->t('Select City:'),
      '#empty_option' => $this->t('--- Select City ---'),
      '#empty_value' => '',
      '#default_value' => $this->stateInterface->get('city_name'),
      '#options' => $cities,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->stateInterface->set('country_name', $form_state->getValue('country_name'));
      $this->stateInterface->set('city_name', $form_state->getValue('city_name'));

    $country_name = $this->stateInterface->get('country_name');
    $city_name = $this->stateInterface->get('city_name');

    $this->logger->info($country_name . '-' . $city_name);

    $this->messenger()->addMessage($this->t('The settings have been successfully saved'));
  }

}
