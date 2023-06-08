<?php

namespace Drupal\task_32\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\taxonomy\TermInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Psr\Log\LoggerInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;

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
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, LoggerInterface $logger) {
    $this->entityTypeManager = $entity_type_manager;
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('logger.factory')->get('action'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $vid = 'countries';
    $country = $this->entityTypeManager->getStorage('taxonomy_term')->loadTree($vid);

    foreach ($country as $term) {
      $countries[$term->tid] = $term->name;
    }

    $form['country'] = [
      '#type' => 'select',
      '#title' => $this->t('Select Country:'),
      '#empty_option' => $this->t('--- Select Country ---'),
      '#empty_value' => '',
      '#options' => $countries,
      '#ajax' => [
        'callback' => [$this, 'myAjaxCallback'],
        'event' => 'change'
      ]
    ];

    $cities_list = [];

    if ($country_id = $form_state->getValue('country')) {
      $cities = $this->entityTypeManager->getStorage('taxonomy_term')->loadByProperties(['field_country' => $country_id]);

      foreach ($cities as $term) {
        if ($term instanceof TermInterface) {
          $cities_list[$term->id()] = $term->getName();
        }
      }
    }

    $form['city'] = [
      '#type' => 'select',
      '#title' => $this->t('Select City:'),
      '#empty_option' => $this->t('--- Select City ---'),
      '#options' => $cities_list,
      '#prefix' => '<div id="city">',
      '#suffix' => '</div>',
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
   * Ajax callback function.
   */
  public function myAjaxCallback(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $response->addCommand(new ReplaceCommand("#city", ($form['city'])));

    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $country_id = $form_state->getValue('country');
    $city_id = $form_state->getValue('city');

    $country = $this->entityTypeManager->getStorage('taxonomy_term')->load($country_id)->getName();
    $city = $this->entityTypeManager->getStorage('taxonomy_term')->load($city_id)->getName();

    $this->logger->info($country . '-' . $city);

    $this->messenger()->addMessage($this->t('The settings have been successfully saved'));
  }

}
