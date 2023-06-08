<?php

namespace Drupal\task_31\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\State\StateInterface;
use Psr\Log\LoggerInterface;

/**
 * Defines a form that configures your_module’s settings.
 */
class ContactForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'contact_form';
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
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#default_value' => $this->stateInterface->get('email'),
    ];

    $form['username'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#default_value' => $this->stateInterface->get('username')
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
    $this->stateInterface->set('email', $form_state->getValue('email'));
    $this->stateInterface->set('username', $form_state->getValue('username'));

    $email = $this->stateInterface->get('email', $form_state->getValue('email'));
    $username = $this->stateInterface->get('username', $form_state->getValue('username'));

    $this->logger->info('Email-' . $email . ' and Username-' . $username);

    $this->messenger()->addMessage($this->t('The settings have been successfully saved'));
  }

}
